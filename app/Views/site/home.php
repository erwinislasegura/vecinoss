<?php
$sidePosts = array_slice($posts, 0, 3);
$latestPosts = array_slice($posts, 3, 8);
$businessPosts = array_slice(array_merge(array_reverse($posts), $posts), 0, 3);
$eventPosts = array_slice(array_merge($posts, $posts), 0, 4);
?>

<section class="shell hero" id="inicio">
    <?php if ($featured): ?>
        <article class="lead">
            <img src="<?= e(post_image($featured['image'])) ?>" alt="">
            <div class="lead-card">
                <small><?= e($featured['category_name']) ?></small>
                <h1><a href="<?= url('/noticia/' . $featured['slug']) ?>"><?= e($featured['title']) ?></a></h1>
                <p><?= e($featured['excerpt']) ?></p>
                <a class="more" href="<?= url('/noticia/' . $featured['slug']) ?>">Leer noticia →</a>
            </div>
        </article>
    <?php endif; ?>
    <aside class="side-stories">
        <?php foreach ($sidePosts as $post): ?>
            <article><img src="<?= e(post_image($post['image'])) ?>" alt=""><div><small><?= e($post['category_name']) ?></small><h2><a href="<?= url('/noticia/' . $post['slug']) ?>"><?= e($post['title']) ?></a></h2><a class="more" href="<?= url('/noticia/' . $post['slug']) ?>">Ver más →</a></div></article>
        <?php endforeach; ?>
    </aside>
</section>

<?php if (($weather['weather_enabled'] ?? '0') === '1'): ?>
<section class="weather-section" aria-labelledby="weather-title">
    <div class="shell weather-widget" data-weather-widget data-latitude="<?= e($weather['weather_fallback_latitude']) ?>" data-longitude="<?= e($weather['weather_fallback_longitude']) ?>" data-fallback-name="<?= e($weather['weather_fallback_name']) ?>">
        <div class="weather-heading"><small>PRONÓSTICO LOCAL</small><h2 id="weather-title"><?= e($weather['weather_title']) ?></h2><p data-weather-location><?= e($weather['weather_fallback_name']) ?></p></div>
        <div class="weather-current" aria-live="polite"><span class="weather-icon" data-weather-icon>◌</span><div><b data-weather-temperature>--°</b><span data-weather-description>Consultando el tiempo…</span></div></div>
        <dl class="weather-details"><div><dt>Sensación</dt><dd data-weather-apparent>--°</dd></div><div><dt>Humedad</dt><dd data-weather-humidity>--%</dd></div><div><dt>Viento</dt><dd data-weather-wind>-- km/h</dd></div></dl>
        <button class="weather-location-button" type="button" data-weather-locate>⌖ Usar mi ubicación</button>
    </div>
</section>
<?php endif; ?>

<section class="shell section" id="noticias">
    <div class="section-title"><div><small>ACTUALIDAD</small><h2>Últimas noticias</h2></div><div class="category-buttons"><?php foreach ($categories as $category): ?><a href="<?= url('/categoria/' . $category['slug']) ?>"><?= e($category['name']) ?></a><?php endforeach; ?></div></div>
    <div class="news-grid"><?php foreach ($latestPosts as $post) require __DIR__ . '/../partials/card.php'; ?></div>
</section>

<?php if ($videos): ?><section class="dark-section" id="tv"><div class="shell section">
    <div class="section-title"><div><small>EN PANTALLA</small><h2>VecinoSS TV</h2></div></div>
    <div class="video-grid">
        <?php foreach ($videos as $video): ?><article><button class="video-cover" type="button" data-video-url="<?= e($video['video_url']) ?>" data-video-title="<?= e($video['title']) ?>" aria-label="Reproducir <?= e($video['title']) ?>"><img src="<?= e(post_image($video['cover_image'])) ?>" alt="Portada de <?= e($video['title']) ?>"><span>▶</span></button><small>VECINOSS TV</small><h3><a href="<?= url('/video/'.$video['id']) ?>"><?= e($video['title']) ?></a></h3><?php if ($video['description']): ?><p><?= e($video['description']) ?></p><?php endif; ?><a class="more" href="<?= url('/video/'.$video['id']) ?>">Ver contenido →</a></article><?php endforeach; ?>
    </div>
</div></section>
<dialog class="video-dialog" data-video-dialog><button class="video-dialog-close" type="button" aria-label="Cerrar video" data-video-close>×</button><h2 data-video-dialog-title></h2><div class="video-player" data-video-player></div></dialog>
<?php endif; ?>

<section class="shell section" id="comunidad">
    <div class="section-title"><div><small>LA VOZ DE LOS BARRIOS</small><h2>Comunidad</h2></div><a href="<?= url('/categoria/comunidad') ?>">Ver la sección →</a></div>
    <div class="community-grid">
        <article class="complaints"><small>DENUNCIAS VECINALES</small><h3>Tu barrio, tu voz</h3><?php foreach (array_slice($posts, 0, 3) as $post): ?><div><b><?= e($post['category_name']) ?></b><h4><a href="<?= url('/noticia/' . $post['slug']) ?>"><?= e($post['title']) ?></a></h4></div><?php endforeach; ?><a class="more" href="mailto:prensa@vecinoss.cl">Enviar una denuncia →</a></article>
        <article class="council"><small>SERVICIO PÚBLICO</small><h3>Información útil para vecinos</h3><p>Encuentra noticias, datos y orientación sobre los servicios de la Provincia de San Antonio.</p><ul><li>Teléfonos de emergencia</li><li>Trámites municipales</li><li>Organizaciones comunitarias</li></ul><a class="more" href="mailto:prensa@vecinoss.cl">Solicitar información →</a></article>
        <nav class="community-links" aria-label="Accesos de comunidad"><a href="<?= url('/categoria/comunidad') ?>">Organizaciones <b>→</b></a><a href="<?= url('/categoria/seguridad') ?>">Seguridad <b>→</b></a><a href="<?= url('/categoria/cultura') ?>">Cultura local <b>→</b></a><a href="mailto:prensa@vecinoss.cl">Envía tu historia <b>→</b></a></nav>
    </div>
</section>

<section class="business-section" id="guia"><div class="shell section">
    <div class="section-title"><div><small>DATOS Y EMPRENDIMIENTO</small><h2>Guía local</h2></div><a href="<?= url('/categoria/emprendimiento') ?>">Explorar guía →</a></div>
    <div class="business-grid"><?php foreach ($businessPosts as $post): ?><article><img src="<?= e(post_image($post['image'])) ?>" alt=""><small><?= e($post['category_name']) ?></small><h3><a href="<?= url('/noticia/' . $post['slug']) ?>"><?= e($post['title']) ?></a></h3><p><?= e($post['excerpt']) ?></p><a class="more" href="<?= url('/noticia/' . $post['slug']) ?>">Conocer más →</a></article><?php endforeach; ?></div>
    <nav class="guide-buttons" aria-label="Categorías de guía"><a href="<?= url('/categoria/emprendimiento') ?>">Gastronomía <b>→</b></a><a href="<?= url('/categoria/emprendimiento') ?>">Comercio <b>→</b></a><a href="<?= url('/categoria/cultura') ?>">Turismo <b>→</b></a><a href="<?= url('/categoria/deportes') ?>">Deportes <b>→</b></a><a href="<?= url('/categoria/comunidad') ?>">Servicios <b>→</b></a></nav>
</div></section>

<section class="shell section events" id="eventos">
    <div class="section-title"><div><small>QUÉ HACER</small><h2>Agenda y eventos</h2></div><a href="mailto:prensa@vecinoss.cl">Publicar un evento →</a></div>
    <?php foreach ($eventPosts as $index => $post): $day = (int) date('d') + $index; ?><article><div class="event-date"><b><?= str_pad((string) $day, 2, '0', STR_PAD_LEFT) ?></b><span><?= strtoupper(date('M')) ?></span></div><div><small><?= e($post['category_name']) ?></small><h3><a href="<?= url('/noticia/' . $post['slug']) ?>"><?= e($post['title']) ?></a></h3><p>Provincia de San Antonio · Actividad abierta a la comunidad</p></div><a href="<?= url('/noticia/' . $post['slug']) ?>">→</a></article><?php endforeach; ?>
</section>
