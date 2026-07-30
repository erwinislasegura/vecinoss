<section class="search-hero">
  <div class="shell">
    <nav class="breadcrumbs breadcrumbs-dark" aria-label="Migas de pan"><a href="<?= url('/') ?>">Inicio</a><span>›</span><span>Buscar</span></nav>
    <small>HEMEROTECA LOCAL</small>
    <h1>¿Qué quieres encontrar?</h1>
    <form class="search-page-form" action="<?= url('/buscar') ?>" method="get" role="search">
      <label class="visually-hidden" for="page-search">Buscar noticias, eventos y comunidad</label>
      <input id="page-search" type="search" name="q" value="<?= e($query) ?>" placeholder="Escribe una palabra o tema…" maxlength="100" autofocus required>
      <button type="submit">Buscar <span aria-hidden="true">→</span></button>
    </form>
  </div>
</section>

<section class="shell section search-results" aria-live="polite">
  <?php if ($query === ''): ?>
    <div class="search-welcome"><b>Explora VecinoSS</b><p>Busca por una noticia, una comuna, un evento o un tema de interés para la comunidad.</p></div>
  <?php elseif ($posts): ?>
    <div class="search-results-heading"><div><small>RESULTADOS DE BÚSQUEDA</small><h2>“<?= e($query) ?>”</h2></div><b><?= count($posts) ?><span><?= count($posts) === 1 ? 'resultado' : 'resultados' ?></span></b></div>
    <div class="news-grid search-results-grid"><?php foreach ($posts as $post): ?><?php require __DIR__ . '/../partials/card.php'; ?><?php endforeach; ?></div>
  <?php else: ?>
    <div class="search-empty"><b>Sin resultados para “<?= e($query) ?>”</b><p>Prueba con menos palabras, revisa la escritura o busca un tema más general.</p><a class="black-button" href="<?= url('/') ?>">Volver al inicio</a></div>
  <?php endif; ?>
</section>
