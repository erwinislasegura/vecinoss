<?php
declare(strict_types=1);
namespace App\Controllers;
use App\Core\Controller; use App\Models\Category; use App\Models\Post;
final class SiteController extends Controller
{
    public function home(): void { $posts=Post::published(40); $this->render('site/home',['title'=>'La voz de nuestra gente','featured'=>array_shift($posts),'posts'=>$posts,'categories'=>Category::all()]); }
    public function article(string $slug): void { $post=Post::findBySlug($slug); if(!$post){http_response_code(404);$this->render('site/404',['title'=>'Noticia no encontrada']);return;} $this->render('site/article',['title'=>$post['title'],'post'=>$post,'related'=>Post::published(4,(int)$post['category_id']),'categories'=>Category::all()]); }
    public function category(string $slug): void { $category=Category::findBySlug($slug); if(!$category){http_response_code(404);$this->render('site/404',['title'=>'Categoría no encontrada']);return;} $this->render('site/category',['title'=>$category['name'],'category'=>$category,'posts'=>Post::published(24,(int)$category['id']),'categories'=>Category::all()]); }
    public function notFound(): void { $this->render('site/404',['title'=>'Página no encontrada','categories'=>Category::all()]); }
}
