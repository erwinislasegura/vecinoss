<?php $networks=['facebook'=>'Facebook','instagram'=>'Instagram','x'=>'X / Twitter','youtube'=>'YouTube','tiktok'=>'TikTok','whatsapp'=>'WhatsApp']; ?>
<section class="admin-panel settings-panel">
    <div class="panel-heading"><div><h2>Redes sociales del encabezado</h2><p>Configura qué perfiles aparecen en la barra superior, tanto en escritorio como en celulares.</p></div></div>
    <?php if ($error): ?><div class="form-error"><?= e($error) ?></div><?php endif; ?>
    <form method="post" action="<?= url('/admin/social') ?>">
        <?= csrf_field() ?>
        <div class="social-settings-grid">
            <?php foreach($networks as $key=>$name): ?><fieldset class="social-setting"><legend><?= e($name) ?></legend><label class="check"><input type="checkbox" name="social_<?= e($key) ?>_enabled" value="1" <?= ($social['social_'.$key.'_enabled']??'0')==='1'?'checked':'' ?>> Mostrar icono</label><label>URL del perfil<input type="url" name="social_<?= e($key) ?>_url" maxlength="500" placeholder="https://…" value="<?= e($social['social_'.$key.'_url']??'') ?>"></label></fieldset><?php endforeach; ?>
        </div>
        <p class="hint">Los iconos sin una URL válida no se mostrarán, aunque la casilla esté marcada.</p>
        <button class="primary-button" type="submit">Guardar redes sociales</button>
    </form>
</section>
