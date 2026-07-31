<div class="utility">
  <div class="shell">
    <span class="utility-location">Provincia de San Antonio</span>
    <span><?= e(date_es(date('Y-m-d H:i:s'))) ?></span>
    <span class="utility-edition">Información local · Edición digital</span>
  </div>
</div>
<header class="site-header">
  <div class="shell brandbar">
    <button class="menu-button" aria-label="Abrir menú" aria-controls="main-navigation" aria-expanded="false"><span></span><span></span><span></span></button>
    <a href="<?= url('/') ?>" class="brand" aria-label="VecinoSS, ir al inicio"><img src="<?= url('/logo/logo.png') ?>" alt="VecinoSS — La voz de nuestra gente"></a>
    <div class="brand-actions">
      <form class="header-search" action="<?= url('/buscar') ?>" method="get" role="search">
        <label class="sr-only" for="header-search">Buscar noticias</label>
        <input id="header-search" type="search" name="q" value="<?= e($query ?? '') ?>" placeholder="Buscar en VecinoSS" aria-label="Buscar noticias" required>
        <button type="submit" aria-label="Buscar"><span aria-hidden="true"></span></button>
      </form>
      <a class="send" href="mailto:prensa@vecinoss.cl"><span>Envía tu noticia</span><b aria-hidden="true">→</b></a>
    </div>
  </div>
  <nav class="main-nav" id="main-navigation" aria-label="Navegación principal"><div class="shell">
    <a class="nav-home" href="<?= url('/') ?>">Inicio</a>
    <?php foreach (array_slice($categories ?? App\Models\Category::topLevel(), 0, 6) as $navCategory): ?><a href="<?= url('/categoria/'.$navCategory['slug']) ?>"><?= e($navCategory['name']) ?></a><?php endforeach; ?>
    <a class="nav-submit" href="mailto:prensa@vecinoss.cl">Enviar noticia <b>→</b></a>
  </div></nav>
</header>
<?php $tickerPosts = $tickerPosts ?? App\Models\Post::publishedToday(); ?>
<div class="ticker" aria-label="Últimas noticias del día"><div class="shell">
  <b><span aria-hidden="true"></span> AHORA</b>
  <div class="ticker-window"><?php if ($tickerPosts): ?><div class="ticker-track"><?php for ($copy=0;$copy<2;$copy++): ?><div class="ticker-items" <?= $copy?'aria-hidden="true"':'' ?>><?php foreach($tickerPosts as $tickerPost): ?><a href="<?= url('/noticia/'.$tickerPost['slug']) ?>"><?= e($tickerPost['title']) ?></a><?php endforeach; ?></div><?php endfor; ?></div><?php else: ?><span class="ticker-empty">VecinoSS informa: la actualidad local, cerca de ti.</span><?php endif; ?></div>
  <time class="ticker-clock" data-live-clock datetime="<?= date('c') ?>"><span aria-hidden="true"></span><b><?= date('H:i') ?></b></time>
</div></div>
