<section class="page-head"><div class="shell"><small>SECCIÓN</small><h1><?= e($category['name']) ?></h1><p><?= e($category['description'] ?? 'Noticias y actualidad de nuestra comunidad.') ?></p></div></section>
<section class="shell section"><div class="news-grid"><?php foreach($posts as $post) require __DIR__.'/../partials/card.php'; ?></div><?php if(!$posts): ?><div class="empty">Pronto publicaremos noticias en esta categoría.</div><?php endif; ?></section>

