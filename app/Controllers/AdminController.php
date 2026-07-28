<?php
declare(strict_types=1);
namespace App\Controllers;
use App\Core\{Auth,Controller,Csrf,Database}; use App\Models\{Category,Post};
final class AdminController extends Controller
{
    public function login(): void { if(Auth::check())$this->redirect('/admin'); $this->render('admin/login',['title'=>'Acceso editorial','error'=>null],'auth'); }
    public function authenticate(): void { Csrf::verify(); if(Auth::attempt(trim($_POST['email']??''),$_POST['password']??''))$this->redirect('/admin'); $this->render('admin/login',['title'=>'Acceso editorial','error'=>'Correo o contraseña incorrectos.'],'auth'); }
    public function logout(): void { Csrf::verify(); Auth::logout(); $this->redirect('/'); }
    public function dashboard(): void { Auth::requireLogin(); $db=Database::connection(); $stats=['posts'=>(int)$db->query('SELECT COUNT(*) FROM posts')->fetchColumn(),'published'=>(int)$db->query('SELECT COUNT(*) FROM posts WHERE status="published"')->fetchColumn(),'categories'=>(int)$db->query('SELECT COUNT(*) FROM categories')->fetchColumn()]; $this->render('admin/dashboard',['title'=>'Panel editorial','stats'=>$stats,'posts'=>array_slice(Post::allAdmin(),0,6)],'admin'); }
    public function posts(): void { Auth::requireLogin(); $this->render('admin/posts/index',['title'=>'Noticias','posts'=>Post::allAdmin()],'admin'); }
    public function create(): void { Auth::requireLogin(); $this->form(null); }
    public function edit(int $id): void { Auth::requireLogin(); $this->form(Post::find($id)); }
    private function form(?array $post): void { $this->render('admin/posts/form',['title'=>$post?'Editar noticia':'Nueva noticia','post'=>$post,'categories'=>Category::all(),'error'=>null],'admin'); }
    public function store(?int $id=null): void { Auth::requireLogin(); Csrf::verify(); $title=trim($_POST['title']??''); $body=trim($_POST['body']??''); if($title===''||$body===''){ $this->render('admin/posts/form',['title'=>$id?'Editar noticia':'Nueva noticia','post'=>array_merge($_POST,['id'=>$id]),'categories'=>Category::all(),'error'=>'El título y el contenido son obligatorios.'],'admin');return;} $slug=$this->slug($_POST['slug']?:$title); $image=trim($_POST['image']??''); if(isset($_FILES['upload'])&&$_FILES['upload']['error']===UPLOAD_ERR_OK){$image=$this->upload($_FILES['upload']);} Post::save(['category_id'=>(int)$_POST['category_id'],'user_id'=>(int)Auth::user()['id'],'title'=>$title,'slug'=>$slug,'excerpt'=>trim($_POST['excerpt']??''),'body'=>$body,'image'=>$image,'status'=>in_array($_POST['status']??'draft',['draft','published'],true)?$_POST['status']:'draft','featured'=>isset($_POST['featured'])?1:0,'published_at'=>str_replace('T',' ',$_POST['published_at']??date('Y-m-d H:i')).':00'],$id); $_SESSION['flash']='Noticia guardada correctamente.';$this->redirect('/admin/posts'); }
    public function destroy(int $id): void { Auth::requireLogin();Csrf::verify();Post::delete($id);$_SESSION['flash']='Noticia eliminada.';$this->redirect('/admin/posts'); }
    private function slug(string $text): string { $text=iconv('UTF-8','ASCII//TRANSLIT',$text)?:$text;return trim(preg_replace('/[^a-z0-9]+/','-',strtolower($text))??'','-'); }
    private function upload(array $file): string { $mime=(new \finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);$types=['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp'];if(!isset($types[$mime])||$file['size']>5_000_000)throw new \RuntimeException('Imagen inválida (JPG, PNG o WebP; máximo 5 MB).');$name=bin2hex(random_bytes(12)).'.'.$types[$mime];move_uploaded_file($file['tmp_name'],dirname(__DIR__,2).'/public/uploads/'.$name);return 'uploads/'.$name; }
}
