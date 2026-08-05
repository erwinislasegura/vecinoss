<section class="admin-panel">
    <div class="panel-heading"><div><h2>Publicidad</h2><p>Administra los anuncios del carrusel ubicado después de la portada principal.</p></div><a class="primary-button" href="<?= url('/admin/advertisements/create') ?>">＋ Nueva publicidad</a></div>
    <div class="table-wrap"><table><thead><tr><th>Anuncio</th><th>Destino</th><th>Orden</th><th>Estado</th><th>Vigencia</th><th></th></tr></thead><tbody>
    <?php foreach ($advertisements as $advertisement): ?><tr>
        <td><div class="table-story"><img src="<?= e(post_image($advertisement['image'])) ?>" alt=""><b><?= e($advertisement['name']) ?></b></div></td>
        <td><a href="<?= e($advertisement['target_url']) ?>" target="_blank" rel="noopener">Abrir enlace ↗</a></td><td><?= (int)$advertisement['sort_order'] ?></td>
        <td><span class="status <?= e($advertisement['status']) ?>"><?= $advertisement['status']==='published'?'Publicado':'Borrador' ?></span></td>
        <td><?= e(date_es($advertisement['starts_at'])) ?><?php if($advertisement['ends_at']): ?><br><small>hasta <?= e(date_es($advertisement['ends_at'])) ?></small><?php endif; ?></td>
        <td class="actions"><a href="<?= url('/admin/advertisements/'.$advertisement['id'].'/edit') ?>">Editar</a><form method="post" action="<?= url('/admin/advertisements/'.$advertisement['id']) ?>" onsubmit="return confirm('¿Eliminar esta publicidad?')"><?= csrf_field() ?><input type="hidden" name="_method" value="DELETE"><button>Eliminar</button></form></td>
    </tr><?php endforeach; ?>
    <?php if(!$advertisements): ?><tr><td colspan="6" class="empty">Aún no hay publicidad. Crea el primer anuncio para activar el carrusel.</td></tr><?php endif; ?>
    </tbody></table></div>
</section>
