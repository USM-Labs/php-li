<section class="hero">
    <div class="hero-copy">
        <span class="eyebrow">Real estate catalog</span>
        <h1>Современная недвижимость для жизни, инвестиций и бизнеса.</h1>
        <p>Найдите квартиру, дом или коммерческое помещение с понятными условиями и прозрачной информацией.</p>
        <form class="quick-search" method="get" action="<?= url('properties') ?>">
            <input name="q" placeholder="Город, район или название">
            <select name="property_type_id">
                <option value="">Тип объекта</option>
                <?php foreach ($types as $type): ?>
                    <option value="<?= e($type['id']) ?>"><?= e($type['name']) ?></option>
                <?php endforeach; ?>
            </select>
            <select name="deal_type">
                <option value="">Тип сделки</option>
                <option value="sale">Продажа</option>
                <option value="rent">Аренда</option>
            </select>
            <input type="number" name="max_price" placeholder="Бюджет до €">
            <button class="btn primary" type="submit">Найти</button>
        </form>
        <div class="actions">
            <a class="btn ghost" href="<?= url('properties') ?>">Смотреть объекты</a>
        </div>
    </div>
    <div class="hero-media">
        <img src="<?= e(mediaUrl('assets/img/properties/apartment-central-residence.jpg')) ?>" alt="UrbanNest property">
    </div>
</section>

<section class="section">
    <div class="section-head">
        <span class="eyebrow">Selected spaces</span>
        <h2>Объекты недели</h2>
    </div>
    <div class="property-grid">
        <?php foreach ($properties as $property): ?>
            <?php require __DIR__ . '/../properties/_card.php'; ?>
        <?php endforeach; ?>
    </div>
</section>

<section class="section">
    <div class="section-head">
        <span class="eyebrow">Why UrbanNest</span>
        <h2>Спокойный процесс выбора</h2>
    </div>
    <div class="feature-grid">
        <article><strong>Проверенные объекты</strong><span>В каталоге только структурированные предложения с понятными параметрами.</span></article>
        <article><strong>Удобный поиск</strong><span>Фильтры помогают быстро сузить выбор по бюджету, типу и району.</span></article>
        <article><strong>Заявки на просмотр</strong><span>Пользователь оставляет заявку и видит ее статус в кабинете.</span></article>
        <article><strong>Деловой интерфейс</strong><span>Без визуального шума, с акцентом на цену, площадь и условия.</span></article>
    </div>
</section>

<section class="cta">
    <h2>Выберите объект и запланируйте просмотр.</h2>
    <a class="btn primary" href="<?= url('properties') ?>">Открыть каталог</a>
</section>

