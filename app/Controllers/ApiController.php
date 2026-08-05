<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Database;
use DateTimeImmutable;
use PDO;
use Throwable;

final class ApiController
{
    private const COMMUNES = ['Santo Domingo','San Antonio','Cartagena','El Tabo','El Quisco','Algarrobo'];

    public function health(): void
    {
        $this->authenticate();
        $this->respond(['ok'=>true,'service'=>'VecinoSS News API','version'=>'1.0']);
    }

    public function categories(): void
    {
        $this->authenticate();
        $rows=Database::connection()->query('SELECT id,name,slug,parent_id FROM categories ORDER BY COALESCE(parent_id,id),parent_id IS NOT NULL,name')->fetchAll();
        $this->respond(['ok'=>true,'categories'=>$rows,'communes'=>self::COMMUNES]);
    }

    public function check(): void
    {
        $this->authenticate();
        $this->ensureSchema();
        $data=$this->json();
        $sourceUrl=trim((string)($data['source_url']??''));
        $title=trim((string)($data['title']??''));
        $body=trim((string)($data['body']??''));
        $fingerprint=$this->fingerprint($title,$body);
        $db=Database::connection();
        $query=$db->prepare('SELECT post_id,source_url FROM api_news_imports WHERE (source_url<>"" AND source_url=?) OR content_hash=? LIMIT 1');
        $query->execute([$sourceUrl,$fingerprint]);
        $match=$query->fetch();
        if(!$match&&$title!==''){
            $query=$db->prepare('SELECT id AS post_id,NULL AS source_url FROM posts WHERE LOWER(title)=LOWER(?) LIMIT 1');
            $query->execute([$title]);
            $match=$query->fetch();
        }
        $this->respond(['ok'=>true,'duplicate'=>(bool)$match,'match'=>$match?:null]);
    }

    public function store(): void
    {
        $this->authenticate();
        $this->ensureSchema();
        $data=$this->json();
        $title=trim((string)($data['title']??''));
        $body=trim((string)($data['body']??''));
        $excerpt=trim((string)($data['excerpt']??''));
        $commune=$this->validCommune((string)($data['commune']??''));
        $categoryId=filter_var($data['category_id']??null,FILTER_VALIDATE_INT);
        $sourceUrl=trim((string)($data['source_url']??''));
        $sourceName=mb_substr(trim((string)($data['source_name']??'')),0,180);
        $image=trim((string)($data['image']??''));
        $errors=[];
        if($title===''||mb_strlen($title)>180)$errors[]='title es obligatorio y admite hasta 180 caracteres.';
        if($body==='')$errors[]='body es obligatorio.';
        if(!$categoryId)$errors[]='category_id debe ser un entero válido.';
        if($commune===null)$errors[]='commune no es válida.';
        if(!$this->safeHttpUrl($sourceUrl))$errors[]='source_url debe ser una URL http/https válida.';
        if($image===''||!$this->safeHttpUrl($image)||mb_strlen($image)>500)$errors[]='image debe ser una URL http/https válida de hasta 500 caracteres.';
        if($errors)$this->respond(['ok'=>false,'errors'=>$errors],422);
        $db=Database::connection();
        $category=$db->prepare('SELECT id FROM categories WHERE id=? LIMIT 1');
        $category->execute([(int)$categoryId]);
        if(!$category->fetchColumn())$this->respond(['ok'=>false,'errors'=>['La categoría no existe.']],422);
        $fingerprint=$this->fingerprint($title,$body);
        $duplicate=$db->prepare('SELECT post_id FROM api_news_imports WHERE source_url=? OR content_hash=? LIMIT 1');
        $duplicate->execute([$sourceUrl,$fingerprint]);
        if($existing=$duplicate->fetchColumn())$this->respond(['ok'=>false,'duplicate'=>true,'post_id'=>(int)$existing],409);
        $duplicate=$db->prepare('SELECT id FROM posts WHERE LOWER(title)=LOWER(?) LIMIT 1');
        $duplicate->execute([$title]);
        if($existing=$duplicate->fetchColumn())$this->respond(['ok'=>false,'duplicate'=>true,'post_id'=>(int)$existing],409);
        $config=require dirname(__DIR__,2).'/config/api.php';
        $userId=(int)($config['user_id']??1);
        $publishedAt=$this->publishedAt((string)($data['published_at']??''));
        $slug=$this->uniqueSlug($title);
        if($excerpt==='')$excerpt=mb_substr(trim(preg_replace('/\s+/u',' ',strip_tags($body))??''),0,350);
        $excerpt=mb_substr($excerpt,0,350);
        $cleanBody=$this->editorHtml($body);
        try{
            $db->beginTransaction();
            $insert=$db->prepare("INSERT INTO posts(category_id,user_id,title,slug,excerpt,body,image,status,featured,published_at) VALUES(?,?,?,?,?,?,?,?,?,?)");
            $insert->execute([(int)$categoryId,$userId,$title,$slug,$excerpt,$cleanBody,$image,'published',!empty($data['featured'])?1:0,$publishedAt]);
            $postId=(int)$db->lastInsertId();
            $meta=$db->prepare('INSERT INTO api_news_imports(post_id,source_url,source_name,commune,content_hash) VALUES(?,?,?,?,?)');
            $meta->execute([$postId,$sourceUrl,$sourceName,$commune,$fingerprint]);
            $this->attachTag($db,$postId,$commune);
            $db->commit();
            $this->respond(['ok'=>true,'post_id'=>$postId,'slug'=>$slug,'status'=>'published','url'=>url('/noticia/'.$slug)],201);
        }catch(Throwable $error){
            if($db->inTransaction())$db->rollBack();
            error_log('VecinoSS API: '.$error->getMessage());
            $this->respond(['ok'=>false,'error'=>'No se pudo guardar la noticia.'],500);
        }
    }

    private function authenticate(): void
    {
        $configFile=dirname(__DIR__,2).'/config/api.php';
        if(!is_file($configFile))$this->respond(['ok'=>false,'error'=>'API no configurada.'],503);
        $config=require $configFile;
        $expected=(string)($config['token_hash']??'');
        $header=(string)($_SERVER['HTTP_AUTHORIZATION']??'');
        if(!preg_match('/^Bearer\s+(.+)$/i',$header,$match))$this->respond(['ok'=>false,'error'=>'No autorizado.'],401);
        $actual=hash('sha256',trim($match[1]));
        if($expected===''||!hash_equals($expected,$actual))$this->respond(['ok'=>false,'error'=>'No autorizado.'],401);
    }

    private function ensureSchema(): void
    {
        Database::connection()->exec("CREATE TABLE IF NOT EXISTS api_news_imports (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            post_id BIGINT UNSIGNED NOT NULL,
            source_url VARCHAR(1000) NOT NULL,
            source_name VARCHAR(180) NOT NULL DEFAULT '',
            commune VARCHAR(40) NOT NULL,
            content_hash CHAR(64) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            UNIQUE KEY uq_api_source_url (source_url(255)),
            UNIQUE KEY uq_api_content_hash (content_hash),
            INDEX idx_api_commune (commune),
            INDEX idx_api_created_at (created_at),
            CONSTRAINT fk_api_post FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    }

    private function json(): array
    {
        $data=json_decode(file_get_contents('php://input')?:'',true);
        if(!is_array($data))$this->respond(['ok'=>false,'error'=>'JSON inválido.'],400);
        return $data;
    }

    private function validCommune(string $value): ?string
    {
        foreach(self::COMMUNES as $commune)if(mb_strtolower(trim($value))===mb_strtolower($commune))return $commune;
        return null;
    }

    private function safeHttpUrl(string $url): bool
    {
        return filter_var($url,FILTER_VALIDATE_URL)!==false&&in_array(strtolower((string)parse_url($url,PHP_URL_SCHEME)),['http','https'],true);
    }

    private function fingerprint(string $title,string $body): string
    {
        return hash('sha256',mb_strtolower(trim(preg_replace('/\s+/u',' ',$title.' '.strip_tags($body))??'')));
    }

    private function publishedAt(string $value): string
    {
        if($value==='')return date('Y-m-d H:i:s');
        $date=DateTimeImmutable::createFromFormat(DateTimeImmutable::ATOM,$value)?:DateTimeImmutable::createFromFormat('Y-m-d H:i:s',$value);
        return $date?$date->format('Y-m-d H:i:s'):date('Y-m-d H:i:s');
    }

    private function uniqueSlug(string $title): string
    {
        $ascii=iconv('UTF-8','ASCII//TRANSLIT',$title)?:$title;
        $base=trim((string)preg_replace('/[^a-z0-9]+/','-',strtolower($ascii)),'-');
        $base=mb_substr($base!==''?$base:'noticia',0,175);
        $slug=$base;
        $query=Database::connection()->prepare('SELECT 1 FROM posts WHERE slug=? LIMIT 1');
        for($counter=2;;$counter++){
            $query->execute([$slug]);
            if(!$query->fetchColumn())return $slug;
            $slug=$base.'-'.$counter;
        }
    }

    private function attachTag(PDO $db,int $postId,string $tag): void
    {
        $ascii=iconv('UTF-8','ASCII//TRANSLIT',$tag)?:$tag;
        $slug=trim((string)preg_replace('/[^a-z0-9]+/','-',strtolower($ascii)),'-');
        $db->prepare('INSERT INTO tags(name,slug) VALUES(?,?) ON DUPLICATE KEY UPDATE name=VALUES(name)')->execute([$tag,$slug]);
        $query=$db->prepare('SELECT id FROM tags WHERE slug=?');
        $query->execute([$slug]);
        $db->prepare('INSERT IGNORE INTO post_tags(post_id,tag_id) VALUES(?,?)')->execute([$postId,(int)$query->fetchColumn()]);
    }

    private function editorHtml(string $html): string
    {
        $html=strip_tags($html,'<p><br><strong><b><em><i><u><h2><h3><ul><ol><li><blockquote><a>');
        $html=preg_replace('/\s+on[a-z]+\s*=\s*(["\']).*?\1/iu','',$html)??$html;
        $html=preg_replace('/<(?!a\b)([a-z0-9]+)\s+[^>]*>/iu','<$1>',$html)??$html;
        $html=preg_replace_callback('/<a\s+([^>]*)>/iu',function(array $match):string{
            preg_match('/href\s*=\s*(["\'])(.*?)\1/iu',$match[1],$href);
            $url=html_entity_decode($href[2]??'');
            return $this->safeHttpUrl($url)?'<a href="'.e($url).'" target="_blank" rel="noopener noreferrer">':'<a>';
        },$html)??$html;
        return trim($html);
    }

    private function respond(array $payload,int $status=200): never
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        header('Cache-Control: no-store');
        echo json_encode($payload,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
        exit;
    }
}

