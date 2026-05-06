<article class="property-card">
    <img src="<?= e(mediaUrl($property['image'])) ?>" alt="<?= e($property['title']) ?>">
    <div class="card-body">
        <div class="badges">
            <span><?= e($property['type_name'] ?? 'Property') ?></span>
            <span class="deal deal-<?= e($property['deal_type']) ?>"><?= $property['deal_type'] === 'sale' ? 'Продажа' : 'Аренда' ?></span>
            <span class="status status-<?= e($property['status']) ?>"><?= e($property['status']) ?></span>
        </div>
        <h3><?= e($property['title']) ?></h3>
        <p><?= e($property['city']) ?>, <?= e($property['district']) ?> · <?= e($property['area']) ?> m² · <?= e($property['rooms']) ?> комн.</p>
        <div class="card-bottom">
            <strong>€<?= e(number_format((float)$property['price'], 0, '.', ' ')) ?></strong>
            <a class="btn small" href="<?= url('properties/' . $property['id']) ?>">Подробнее</a>
        </div>
    </div>
</article>

