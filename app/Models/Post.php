<?php
declare(strict_types=1);
namespace App\Models;
use App\Core\Database;
final class Post
{
    private const SELECT = 'SELECT p.*, c.name category_name, c.slug category_slug, u.name author_name FROM posts p JOIN categories c ON c.id=p.category_id JOIN users u ON u.id=p.user_id';
    public static function published(int $limit=12, ?int $categoryId=null): array { $sql=self::SELECT." WHERE p.status='published' AND p.published_at<=CURRENT_TIMESTAMP".($categoryId?' AND p.category_id=?':'').' ORDER BY p.featured DESC,p.published_at DESC LIMIT '.(int)$limit; $s=Database::connection()->prepare($sql); $s->execute($categoryId?[$categoryId]:[]); return $s->fetchAll(); }
    public static function featured(): ?array { $s=Database::connection()->query(self::SELECT." WHERE p.status='published' AND p.published_at<=CURRENT_TIMESTAMP ORDER BY p.featured DESC,p.published_at DESC LIMIT 1"); return $s->fetch() ?: null; }
    public static function findBySlug(string $slug): ?array { $s=Database::connection()->prepare(self::SELECT." WHERE p.slug=? AND p.status='published' LIMIT 1");$s->execute([$slug]);return $s->fetch()?:null; }
    public static function allAdmin(): array { return Database::connection()->query(self::SELECT.' ORDER BY p.created_at DESC')->fetchAll(); }
    public static function find(int $id): ?array { $s=Database::connection()->prepare('SELECT * FROM posts WHERE id=?');$s->execute([$id]);return $s->fetch()?:null; }
    public static function save(array $d, ?int $id=null): void { $values=[$d['category_id'],$d['user_id'],$d['title'],$d['slug'],$d['excerpt'],$d['body'],$d['image'],$d['status'],$d['featured'],$d['published_at']]; if($id){$values[]=$id;$sql='UPDATE posts SET category_id=?,user_id=?,title=?,slug=?,excerpt=?,body=?,image=?,status=?,featured=?,published_at=? WHERE id=?';}else{$sql='INSERT INTO posts(category_id,user_id,title,slug,excerpt,body,image,status,featured,published_at) VALUES(?,?,?,?,?,?,?,?,?,?)';} Database::connection()->prepare($sql)->execute($values); }
    public static function delete(int $id): void { Database::connection()->prepare('DELETE FROM posts WHERE id=?')->execute([$id]); }
}
