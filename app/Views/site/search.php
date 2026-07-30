<section class="search-head">
  <div class="shell">
    <small>HEMEROTECA VECINOSS</small>
    <h1>Busca en nuestras noticias</h1>
    <form class="search-page-form" action="<?= url('/buscar') ?>" method="get" role="search">
      <label class="sr-only" for="page-search">¿Qué noticia buscas?</label>
      <input id="page-search" type="search" name="q" value="<?= e($query) ?>" placeholder="Escribe una comuna, tema o palabra clave" aria-label="¿Qué noticia buscas?" required autofocus>
      <button type="submit"><span aria-hidden="true"></span> Buscar</button>
    </form>
  </div>
</section>
<section class="shell section search-results">
  <?php if ($query !== ''): ?>
    <div class="search-summary"><div><small>RESULTADOS</small><h2>“<?= e($query) ?>”</h2></div><b><?= count($posts) ?> <?= count($posts) === 1 ? 'noticia encontrada' : 'noticias encontradas' ?></b></div>
    <?php if ($posts): ?><div class="news-grid"><?php foreach($posts as $post) require __DIR__.'/../partials/card.php'; ?></div>
    <?php else: ?><div class="search-empty"><b aria-hidden="true">?</b><h2>No encontramos coincidencias</h2><p>Prueba con menos palabras o busca por el nombre de una comuna, categoría o protagonista.</p></div><?php endif; ?>
  <?php else: ?>
    <div class="search-empty"><b aria-hidden="true">⌕</b><h2>La actualidad local, a un clic</h2><p>Usa el buscador para encontrar noticias, eventos y temas de la Provincia de San Antonio.</p></div>
  <?php endif; ?>
</section>
