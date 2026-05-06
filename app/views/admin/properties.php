<section class="page-head compact">
    <span class="eyebrow">Admin</span>
    <h1>Управление объектами недвижимости</h1>
</section>
<?php require __DIR__ . '/_nav.php'; ?>

<section class="panel">
    <h2><?= $editProperty ? 'Редактировать объект' : 'Добавить объект' ?></h2>
    <form class="admin-form" method="post" action="<?= url('admin/properties/save') ?>" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= e($editProperty['id'] ?? '') ?>">
        <input type="hidden" name="current_image" value="<?= e($editProperty['image'] ?? '') ?>">
        <div class="form-grid">
            <label>Название <input name="title" required value="<?= e($editProperty['title'] ?? '') ?>"></label>
            <label>Тип
                <select name="property_type_id" required>
                    <?php foreach ($types as $type): ?>
                        <option value="<?= e($type['id']) ?>" <?= (string)($editProperty['property_type_id'] ?? '') === (string)$type['id'] ? 'selected' : '' ?>><?= e($type['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
            <label>Сделка
                <select name="deal_type">
                    <option value="sale" <?= ($editProperty['deal_type'] ?? 'sale') === 'sale' ? 'selected' : '' ?>>Продажа</option>
                    <option value="rent" <?= ($editProperty['deal_type'] ?? '') === 'rent' ? 'selected' : '' ?>>Аренда</option>
                </select>
            </label>
            <label>Статус
                <select name="status">
                    <?php foreach (['available', 'reserved', 'sold', 'rented', 'hidden'] as $status): ?>
                        <option value="<?= e($status) ?>" <?= ($editProperty['status'] ?? 'available') === $status ? 'selected' : '' ?>><?= e($status) ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
            <label>Город <input name="city" required value="<?= e($editProperty['city'] ?? '') ?>"></label>
            <label>Район <input name="district" required value="<?= e($editProperty['district'] ?? '') ?>"></label>
            <label>Адрес <input name="address" required value="<?= e($editProperty['address'] ?? '') ?>"></label>
            <label>Комнаты <input type="number" name="rooms" min="0" value="<?= e($editProperty['rooms'] ?? '1') ?>"></label>
            <label>Площадь <input type="number" step="0.1" name="area" required value="<?= e($editProperty['area'] ?? '') ?>"></label>
            <label>Этаж <input type="number" name="floor" value="<?= e($editProperty['floor'] ?? '1') ?>"></label>
            <label>Всего этажей <input type="number" name="total_floors" value="<?= e($editProperty['total_floors'] ?? '1') ?>"></label>
            <label>Цена <input type="number" step="0.01" name="price" required value="<?= e($editProperty['price'] ?? '') ?>"></label>
            <label>Изображение <input type="file" name="image_file" accept="image/jpeg,image/png,image/webp" <?= $editProperty ? '' : 'required' ?>></label>
        </div>
        <?php if (!empty($editProperty['image'])): ?>
            <div class="image-preview">
                <img src="<?= e(mediaUrl($editProperty['image'])) ?>" alt="<?= e($editProperty['title']) ?>">
                <span>Текущее изображение будет сохранено, если не выбрать новый файл.</span>
            </div>
        <?php endif; ?>
        <label>Описание <textarea name="description" required rows="4"><?= e($editProperty['description'] ?? '') ?></textarea></label>
        <button class="btn primary" type="submit">Сохранить</button>
    </form>
</section>

<section class="panel">
    <h2>Список объектов</h2>
    <div class="table-wrap">
        <table>
            <thead><tr><th>Объект</th><th>Тип</th><th>Город</th><th>Цена</th><th>Статус</th><th>Действия</th></tr></thead>
            <tbody>
            <?php foreach ($properties as $property): ?>
                <tr>
                    <td><?= e($property['title']) ?></td>
                    <td><?= e($property['type_name']) ?></td>
                    <td><?= e($property['city']) ?></td>
                    <td>€<?= e(number_format((float)$property['price'], 0, '.', ' ')) ?></td>
                    <td><span class="status status-<?= e($property['status']) ?>"><?= e($property['status']) ?></span></td>
                    <td class="table-actions">
                        <a class="btn small" href="<?= url('admin/properties?edit=' . $property['id']) ?>">Edit</a>
                        <form method="post" action="<?= url('admin/properties/delete') ?>" onsubmit="return confirm('Удалить объект?')">
                            <input type="hidden" name="id" value="<?= e($property['id']) ?>">
                            <button class="btn danger small" type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>

