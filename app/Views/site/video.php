<article class="article video-detail shell">
    <div class="breadcrumbs"><a href="<?= url('/') ?>">Inicio</a> / <a href="<?= url('/#tv') ?>">VecinoSS TV</a></div>
    <header><small>VECINOSS TV</small><h1><?= e($video['title']) ?></h1><?php if ($video['description']): ?><p class="standfirst"><?= e($video['description']) ?></p><?php endif; ?><div class="byline">Publicado el <?= e(date_es($video['published_at'])) ?></div></header>
    <button class="video-detail-cover" type="button" data-video-url="<?= e($video['video_url']) ?>" data-video-title="<?= e($video['title']) ?>"><img src="<?= e(post_image($video['cover_image'])) ?>" alt="Portada de <?= e($video['title']) ?>"><span>▶</span><b>Reproducir video</b></button>
</article>

<dialog class="video-dialog" data-video-dialog><button class="video-dialog-close" type="button" aria-label="Cerrar video" data-video-close>×</button><h2 data-video-dialog-title></h2><div class="video-player" data-video-player></div></dialog>

<?php if ($related): ?><section class="dark-section video-related"><div class="shell section"><div class="section-title"><div><small>CONTINÚA VIENDO</small><h2>Más de VecinoSS TV</h2></div></div><div class="video-grid">
<?php foreach ($related as $item): ?><article><a class="video-cover" href="<?= url('/video/'.$item['id']) ?>"><img src="<?= e(post_image($item['cover_image'])) ?>" alt="Portada de <?= e($item['title']) ?>"><span>▶</span></a><small>VECINOSS TV</small><h3><a href="<?= url('/video/'.$item['id']) ?>"><?= e($item['title']) ?></a></h3><a class="more" href="<?= url('/video/'.$item['id']) ?>">Ver contenido →</a></article><?php endforeach; ?>
</div></div></section><?php endif; ?>
