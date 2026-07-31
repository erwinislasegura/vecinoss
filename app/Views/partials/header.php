<?php
$socialSettings = App\Models\Setting::social();
$socialNetworks = [
  'facebook' => ['Facebook', '<path d="M14 8h3V4h-3c-3 0-5 2-5 5v3H6v4h3v8h4v-8h3l1-4h-4V9c0-.7.3-1 1-1Z"/>'],
  'instagram' => ['Instagram', '<rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" class="icon-fill"/>'],
  'x' => ['X', '<path d="m5 4 14 16M19 4 5 20"/>'],
  'youtube' => ['YouTube', '<path d="M21 8.2c-.2-1.4-1-2.2-2.4-2.4C16.7 5.5 14.5 5.5 12 5.5s-4.7 0-6.6.3C4 6 3.2 6.8 3 8.2A23 23 0 0 0 3 15.8c.2 1.4 1 2.2 2.4 2.4 1.9.3 4.1.3 6.6.3s4.7 0 6.6-.3c1.4-.2 2.2-1 2.4-2.4a23 23 0 0 0 0-7.6Z"/><path d="m10 9 5 3-5 3Z" class="icon-fill"/>'],
  'whatsapp' => ['WhatsApp', '<path d="M20 11.6a8 8 0 0 1-11.8 7L4 20l1.4-4A8 8 0 1 1 20 11.6Z"/><path d="M9 8c.5 3 2 4.5 5 5"/>'],
];
?>
<div class="utility">
  <div class="shell">
    <div class="utility-location"><span class="utility-place">Provincia de San Antonio</span><span><?= e(date_es(date('Y-m-d H:i:s'))) ?></span></div>
    <div class="header-socials" aria-label="Redes sociales"><?php foreach($socialNetworks as $key=>$network): ?><?php if(($socialSettings['social_'.$key.'_enabled']??'0')==='1'&&!empty($socialSettings['social_'.$key.'_url'])): ?><a href="<?= e($socialSettings['social_'.$key.'_url']) ?>" target="_blank" rel="noopener noreferrer" aria-label="Síguenos en <?= e($network[0]) ?>"><svg viewBox="0 0 24 24" aria-hidden="true"><?= $network[1] ?></svg></a><?php endif; ?><?php endforeach; ?></div>
    <div class="utility-actions"><span class="utility-edition">Edición digital</span><button class="contrast-toggle" type="button" data-contrast-toggle aria-pressed="false"><span class="contrast-icon" aria-hidden="true">◐</span><span data-contrast-label>Alto contraste</span></button></div>
  </div>
</div>
<header class="site-header">
  <div class="shell brandbar">
    <button class="menu-button" aria-label="Abrir menú" aria-controls="main-menu" aria-expanded="false"><span></span><span></span><span></span></button>
    <a href="<?= url('/') ?>" class="brand" aria-label="VecinoSS, ir al inicio"><img src="<?= url('/logo/logo.png') ?>" alt="VecinoSS — La voz de nuestra gente"></a>
    <form class="header-search" action="<?= url('/buscar') ?>" method="get" role="search" data-header-search>
      <label class="visually-hidden" for="header-search">Buscar en VecinoSS</label>
      <input id="header-search" type="search" name="q" value="<?= e(is_string($_GET['q'] ?? null) ? $_GET['q'] : '') ?>" placeholder="Buscar noticias, comunas o temas" maxlength="100" enterkeyhint="search" autocomplete="off" required>
      <button type="submit" aria-label="Buscar"><span aria-hidden="true"></span></button>
    </form>
    <div class="brand-end"><a class="send" href="mailto:prensa@vecinoss.cl"><span>Envía tu noticia</span><b aria-hidden="true">→</b></a><button class="contrast-toggle contrast-mobile" type="button" data-contrast-toggle aria-pressed="false" aria-label="Activar alto contraste">◐</button></div>
  </div>
  <nav class="main-nav" id="main-menu" aria-label="Navegación principal"><div class="shell">
    <a class="nav-home" href="<?= url('/') ?>">Inicio</a>
    <?php if ((App\Models\Setting::horoscope()['horoscope_enabled'] ?? '0') === '1'): ?><a href="<?= url('/horoscopo') ?>">Horóscopo</a><?php endif; ?>
    <?php foreach (array_slice($categories ?? App\Models\Category::topLevel(), 0, 6) as $navCategory): ?><?php $navHref = $navCategory['slug']==='vecinoss-tv' ? '/videos' : ($navCategory['slug']==='eventos' ? '/eventos' : '/categoria/'.$navCategory['slug']); ?><a href="<?= url($navHref) ?>"><?= e($navCategory['name']) ?></a><?php endforeach; ?>
  </div></nav>
</header>
<?php $tickerPosts = $tickerPosts ?? App\Models\Post::publishedToday(); ?>
<div class="ticker" aria-label="Últimas noticias del día"><div class="shell">
  <b><span aria-hidden="true"></span>AHORA</b>
  <div class="ticker-window"><?php if ($tickerPosts): ?><div class="ticker-track"><?php for ($copy=0;$copy<2;$copy++): ?><div class="ticker-items" <?= $copy?'aria-hidden="true"':'' ?>><?php foreach($tickerPosts as $tickerPost): ?><a href="<?= url('/noticia/'.$tickerPost['slug']) ?>"><?= e($tickerPost['title']) ?></a><?php endforeach; ?></div><?php endfor; ?></div><?php else: ?><span class="ticker-empty">VecinoSS informa: la actualidad local, cerca de ti.</span><?php endif; ?></div>
  <time class="ticker-clock" data-live-clock datetime="<?= date('c') ?>"><span aria-hidden="true"></span><b><?= date('H:i') ?></b></time>
</div></div>
