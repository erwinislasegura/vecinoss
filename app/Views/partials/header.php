<div class="utility"><div class="shell"><span><?= e(date_es(date('Y-m-d H:i:s'))) ?> · Provincia de San Antonio</span><span>Edición digital</span></div></div>
<header class="site-header">
  <div class="shell brandbar"><button class="menu-button" aria-label="Abrir menú" aria-expanded="false">☰</button><a href="<?= url('/') ?>" class="brand"><img src="<?= url('/logo/logo.png') ?>" alt="VecinoSS"></a><a class="send" href="mailto:prensa@vecinoss.cl">Envía tu noticia <b>→</b></a></div>
  <nav class="main-nav" aria-label="Navegación principal"><div class="shell">
    <a href="<?= url('/') ?>">Inicio</a>
    <?php foreach (array_slice($categories ?? App\Models\Category::topLevel(), 0, 6) as $navCategory): ?><a href="<?= url('/categoria/'.$navCategory['slug']) ?>"><?= e($navCategory['name']) ?></a><?php endforeach; ?>
  </div></nav>
</header>
<div class="ticker"><div class="shell"><b>AHORA</b><span>VecinoSS informa: la actualidad local, cerca de ti.</span><time><?= date('H:i') ?></time></div></div>

