<section class="page-head compact">
    <span class="eyebrow">Admin</span>
    <h1>Типы недвижимости</h1>
</section>
<?php require __DIR__ . '/_nav.php'; ?>

<section class="panel two-col">
    <form class="admin-form" method="post" action="<?= url('admin/types/save') ?>">
        <h2><?= $editType ? 'Редактировать тип' : 'Добавить тип' ?></h2>
        <input type="hidden" name="id" value="<?= e($editType['id'] ?? '') ?>">
        <label>Название <input name="name" required value="<?= e($editType['name'] ?? '') ?>"></label>
        <label>Описание <textarea name="description" rows="4"><?= e($editType['description'] ?? '') ?></textarea></label>
        <button class="btn primary" type="submit">Сохранить</button>
    </form>
    <div class="table-wrap">
        <table>
            <thead><tr><th>Название</th><th>Описание</th><th></th></tr></thead>
            <tbody>
            <?php foreach ($types as $type): ?>
                <tr>
                    <td><?= e($type['name']) ?></td>
                    <td><?= e($type['description']) ?></td>
                    <td class="table-actions">
                        <a class="btn small" href="<?= url('admin/types?edit=' . $type['id']) ?>">Edit</a>
                        <form method="post" action="<?= url('admin/types/delete') ?>" onsubmit="return confirm('Удалить тип?')">
                            <input type="hidden" name="id" value="<?= e($type['id']) ?>">
                            <button class="btn danger small" type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>

