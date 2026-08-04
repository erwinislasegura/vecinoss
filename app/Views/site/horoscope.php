<section class="horoscope-hero"><div class="shell"><div><small>ASTROS Y BIENESTAR</small><h1><?= e($horoscope['horoscope_page_title']) ?></h1><p><?= e($horoscope['horoscope_page_intro']) ?></p><time datetime="<?= date('Y-m-d') ?>"><?= e(date_es(date('Y-m-d H:i:s'))) ?></time></div><span aria-hidden="true">☾</span></div></section>
<section class="shell horoscope-section" aria-label="Predicciones por signo">
  <p class="horoscope-disclaimer">Contenido de entretenimiento y orientación general.</p>
  <div class="zodiac-grid"><?php foreach(($horoscope['signs'] ?? []) as $sign): ?><article><span class="zodiac-symbol" aria-hidden="true"><?= e($sign['symbol'] ?? '') ?></span><div><small><?= e($sign['range'] ?? '') ?></small><h2><?= e($sign['name'] ?? '') ?></h2><p><?= e($sign['text'] ?? '') ?></p></div></article><?php endforeach; ?></div>
</section>
