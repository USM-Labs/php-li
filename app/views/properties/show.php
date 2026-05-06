<section class="property-detail">
    <div class="detail-media">
        <img src="<?= e(mediaUrl($property['image'])) ?>" alt="<?= e($property['title']) ?>">
    </div>
    <div class="detail-info">
        <div class="badges">
            <span><?= e($property['type_name']) ?></span>
            <span class="deal deal-<?= e($property['deal_type']) ?>"><?= $property['deal_type'] === 'sale' ? 'Продажа' : 'Аренда' ?></span>
            <span class="status status-<?= e($property['status']) ?>"><?= e($property['status']) ?></span>
        </div>
        <h1><?= e($property['title']) ?></h1>
        <p class="lead"><?= e($property['description']) ?></p>
        <div class="spec-grid">
            <div><span>Город</span><strong><?= e($property['city']) ?></strong></div>
            <div><span>Район</span><strong><?= e($property['district']) ?></strong></div>
            <div><span>Адрес</span><strong><?= e($property['address']) ?></strong></div>
            <div><span>Площадь</span><strong><?= e($property['area']) ?> m²</strong></div>
            <div><span>Комнаты</span><strong><?= e($property['rooms']) ?></strong></div>
            <div><span>Этаж</span><strong><?= e($property['floor']) ?> / <?= e($property['total_floors']) ?></strong></div>
            <div><span>Тип</span><strong><?= e($property['type_name']) ?></strong></div>
            <div><span>Сделка</span><strong><?= $property['deal_type'] === 'sale' ? 'Продажа' : 'Аренда' ?></strong></div>
        </div>
        <div class="price-line">
            <strong>€<?= e(number_format((float)$property['price'], 0, '.', ' ')) ?></strong>
            <?php if ($property['status'] === 'available'): ?>
                <a class="btn primary" href="<?= url('request?property_id=' . $property['id']) ?>">Оставить заявку</a>
            <?php endif; ?>
        </div>
    </div>
</section>

