<?php $advertisement=$advertisement??[]; ?>
<form class="editor-form" method="post" enctype="multipart/form-data" action="<?= !empty($advertisement['id'])?url('/admin/advertisements/'.$advertisement['id']):url('/admin/advertisements') ?>"><?= csrf_field() ?>
<?php if($error): ?><div class="form-error full"><?= e($error) ?></div><?php endif; ?>
<section class="form-main admin-panel"><h2>Contenido del anuncio</h2>
    <label>Nombre interno *<input name="name" required maxlength="140" value="<?= e($advertisement['name']??'') ?>" placeholder="Ej.: Campaña verano Comercio Local"></label>
    <label>Enlace de destino *<input type="url" name="target_url" required maxlength="1000" value="<?= e($advertisement['target_url']??'') ?>" placeholder="https://..."></label>
    <label>Texto alternativo *<input name="alt_text" required maxlength="180" value="<?= e($advertisement['alt_text']??'') ?>" placeholder="Describe el logo o anuncio para accesibilidad"></label>
    <label class="check"><input type="checkbox" name="open_new_tab" value="1" <?= !isset($advertisement['open_new_tab'])||(int)$advertisement['open_new_tab']===1?'checked':'' ?>> Abrir el enlace en otra pestaña</label>
</section>
<aside><section class="admin-panel"><h2>Publicación</h2>
    <label>Estado<select name="status"><option value="draft" <?= ($advertisement['status']??'')==='draft'?'selected':'' ?>>Borrador</option><option value="published" <?= ($advertisement['status']??'published')==='published'?'selected':'' ?>>Publicado</option></select></label>
    <label>Orden<input type="number" min="0" max="999" name="sort_order" value="<?= e($advertisement['sort_order']??0) ?>"></label>
    <label>Inicio<input type="datetime-local" name="starts_at" required value="<?= e(isset($advertisement['starts_at'])?date('Y-m-d\TH:i',strtotime($advertisement['starts_at'])):date('Y-m-d\TH:i')) ?>"></label>
    <label>Término opcional<input type="datetime-local" name="ends_at" value="<?= e(!empty($advertisement['ends_at'])?date('Y-m-d\TH:i',strtotime($advertisement['ends_at'])):'') ?>"></label>
    <button class="primary-button">Guardar publicidad →</button>
</section><section class="admin-panel"><h2>Imagen o logotipo</h2>
    <?php if(!empty($advertisement['image'])): ?><img class="advertisement-preview" src="<?= e(post_image($advertisement['image'])) ?>" alt="Vista previa"><?php endif; ?>
    <label>Subir imagen<input type="file" name="upload" accept="image/jpeg,image/png,image/webp"></label><span class="hint">JPG, PNG o WebP. Máximo 5 MB. Recomendado: 800 × 360 px.</span>
    <label>o URL externa<input type="url" name="image" value="<?= e($advertisement['image']??'') ?>" placeholder="https://..."></label>
</section></aside></form>
