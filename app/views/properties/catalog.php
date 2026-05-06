<section class="page-head">
    <span class="eyebrow">Urban catalog</span>
    <h1>Каталог недвижимости</h1>
    <p>Ищите объекты по городу, району, типу недвижимости, цене, площади и формату сделки.</p>
</section>

<form class="filter-bar" method="get" action="<?= url('properties') ?>">
    <input type="search" name="q" placeholder="Локация" value="<?= e($filters['q']) ?>">
    <select name="property_type_id">
        <option value="">Все типы</option>
        <?php foreach ($types as $type): ?>
            <option value="<?= e($type['id']) ?>" <?= (string)$filters['property_type_id'] === (string)$type['id'] ? 'selected' : '' ?>><?= e($type['name']) ?></option>
        <?php endforeach; ?>
    </select>
    <select name="deal_type">
        <option value="">Любая сделка</option>
        <option value="sale" <?= $filters['deal_type'] === 'sale' ? 'selected' : '' ?>>Продажа</option>
        <option value="rent" <?= $filters['deal_type'] === 'rent' ? 'selected' : '' ?>>Аренда</option>
    </select>
    <select name="rooms">
        <option value="">Комнаты</option>
        <?php foreach ([1, 2, 3, 4, 5] as $rooms): ?>
            <option value="<?= e($rooms) ?>" <?= (string)$filters['rooms'] === (string)$rooms ? 'selected' : '' ?>><?= e($rooms) ?></option>
        <?php endforeach; ?>
    </select>
    <select name="status">
        <option value="">Любой статус</option>
        <?php foreach (['available', 'reserved', 'sold', 'rented'] as $status): ?>
            <option value="<?= e($status) ?>" <?= $filters['status'] === $status ? 'selected' : '' ?>><?= e($status) ?></option>
        <?php endforeach; ?>
    </select>
    <input type="number" name="max_price" placeholder="Цена до €" value="<?= e($filters['max_price']) ?>">
    <input type="number" name="min_area" placeholder="Площадь от m²" value="<?= e($filters['min_area']) ?>">
    <select name="sort">
        <option value="">Сначала новые</option>
        <option value="price_asc" <?= $filters['sort'] === 'price_asc' ? 'selected' : '' ?>>Цена ↑</option>
        <option value="price_desc" <?= $filters['sort'] === 'price_desc' ? 'selected' : '' ?>>Цена ↓</option>
        <option value="area_desc" <?= $filters['sort'] === 'area_desc' ? 'selected' : '' ?>>Площадь</option>
    </select>
    <button class="btn primary" type="submit">Найти</button>
</form>

<?php if ($properties): ?>
    <div class="property-grid catalog-grid">
        <?php foreach ($properties as $property): ?>
            <?php require __DIR__ . '/_card.php'; ?>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <section class="empty-state">
        <h2>Объекты не найдены</h2>
        <p>Попробуйте изменить фильтры или вернуться позже.</p>
    </section>
<?php endif; ?>

