<?php
$signs = [
 ['Aries','♈','21 mar — 19 abr','Tu iniciativa abre una conversación importante. Avanza con decisión, pero escucha antes de responder.'],
 ['Tauro','♉','20 abr — 20 may','Un asunto práctico comienza a ordenarse. Prioriza lo simple y evita cargar con tareas ajenas.'],
 ['Géminis','♊','21 may — 20 jun','Las ideas fluyen con rapidez. Anota lo esencial y elige una sola dirección para convertirla en acción.'],
 ['Cáncer','♋','21 jun — 22 jul','Tu intuición estará especialmente activa. Reserva tiempo para tu entorno cercano y protege tus límites.'],
 ['Leo','♌','23 jul — 22 ago','Tu presencia inspira a otros. Comparte el protagonismo y una colaboración dará mejores resultados.'],
 ['Virgo','♍','23 ago — 22 sep','Una pequeña mejora tendrá un gran efecto. Ordena pendientes y deja espacio para lo inesperado.'],
 ['Libra','♎','23 sep — 22 oct','El equilibrio llega cuando expresas lo que necesitas. Una conversación honesta aliviará tensiones.'],
 ['Escorpio','♏','23 oct — 21 nov','Observa antes de tomar una decisión definitiva. Hay información valiosa en los detalles que otros pasan por alto.'],
 ['Sagitario','♐','22 nov — 21 dic','Una oportunidad invita a ampliar tus horizontes. Revisa bien las condiciones y atrévete a explorar.'],
 ['Capricornio','♑','22 dic — 19 ene','La constancia empieza a mostrar resultados. Reconoce lo avanzado y ajusta la siguiente meta.'],
 ['Acuario','♒','20 ene — 18 feb','Tu mirada diferente resuelve un problema antiguo. Explica tu idea con claridad y suma aliados.'],
 ['Piscis','♓','19 feb — 20 mar','La sensibilidad será una fortaleza si la acompañas de límites claros. Confía en tu percepción.'],
];
?>
<section class="horoscope-hero"><div class="shell"><div><small>ASTROS Y BIENESTAR</small><h1><?= e($horoscope['horoscope_page_title']) ?></h1><p><?= e($horoscope['horoscope_page_intro']) ?></p><time datetime="<?= date('Y-m-d') ?>"><?= e(date_es(date('Y-m-d H:i:s'))) ?></time></div><span aria-hidden="true">☾</span></div></section>
<section class="shell horoscope-section" aria-label="Predicciones por signo">
  <p class="horoscope-disclaimer">Contenido de entretenimiento y orientación general.</p>
  <div class="zodiac-grid"><?php foreach($signs as [$name,$symbol,$range,$text]): ?><article><span class="zodiac-symbol" aria-hidden="true"><?= $symbol ?></span><div><small><?= e($range) ?></small><h2><?= e($name) ?></h2><p><?= e($text) ?></p></div></article><?php endforeach; ?></div>
</section>
