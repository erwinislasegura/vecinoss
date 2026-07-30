<section class="admin-panel settings-panel">
    <div class="panel-heading"><div><h2>Sección de horóscopo</h2><p>Administra la visibilidad y los textos del llamado de portada y de su página independiente.</p></div></div>
    <?php if ($error): ?><div class="form-error"><?= e($error) ?></div><?php endif; ?>
    <form method="post" action="<?= url('/admin/horoscope') ?>">
        <?= csrf_field() ?>
        <label class="check"><input type="checkbox" name="horoscope_enabled" value="1" <?= ($horoscope['horoscope_enabled'] ?? '0') === '1' ? 'checked' : '' ?>> Mostrar el CTA y habilitar la página pública</label>
        <label>Antetítulo del CTA<input name="horoscope_cta_eyebrow" maxlength="60" value="<?= e($horoscope['horoscope_cta_eyebrow'] ?? '') ?>"></label>
        <label>Título del CTA<input name="horoscope_cta_title" maxlength="100" required value="<?= e($horoscope['horoscope_cta_title'] ?? '') ?>"></label>
        <label>Texto del CTA<textarea name="horoscope_cta_text" rows="3" maxlength="240"><?= e($horoscope['horoscope_cta_text'] ?? '') ?></textarea></label>
        <label>Texto del botón<input name="horoscope_cta_button" maxlength="60" required value="<?= e($horoscope['horoscope_cta_button'] ?? '') ?>"></label>
        <label>Título de la página<input name="horoscope_page_title" maxlength="100" required value="<?= e($horoscope['horoscope_page_title'] ?? '') ?>"></label>
        <label>Introducción de la página<textarea name="horoscope_page_intro" rows="3" maxlength="300"><?= e($horoscope['horoscope_page_intro'] ?? '') ?></textarea></label>
        <button class="primary-button" type="submit">Guardar configuración</button>
    </form>
</section>
