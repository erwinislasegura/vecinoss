<?php
$weekStart = new DateTimeImmutable('monday this week');
$weekEnd = $weekStart->modify('+6 days');
$weekLabel = 'Semana del ' . $weekStart->format('d.m.Y') . ' al ' . $weekEnd->format('d.m.Y');
?>
<section class="horoscope-hero"><div class="shell"><div><small>ASTROS Y BIENESTAR</small><h1><?= e($horoscope['horoscope_page_title']) ?></h1><p><?= e($horoscope['horoscope_page_intro']) ?></p><time datetime="<?= e($weekStart->format('Y-m-d')) ?>/<?= e($weekEnd->format('Y-m-d')) ?>"><?= e($weekLabel) ?></time></div><span aria-hidden="true">☾</span></div></section>
<section class="shell horoscope-section" aria-label="Predicciones semanales por signo">
  <p class="horoscope-disclaimer">Contenido de entretenimiento y orientación general.</p>
  <div class="zodiac-grid"><?php foreach(($horoscope['signs'] ?? []) as $sign): ?><article><span class="zodiac-symbol" aria-hidden="true"><?= e($sign['symbol'] ?? '') ?></span><div><small><?= e($sign['range'] ?? '') ?></small><h2><?= e($sign['name'] ?? '') ?></h2><p><?= e($sign['text'] ?? '') ?></p></div></article><?php endforeach; ?></div>
</section>
