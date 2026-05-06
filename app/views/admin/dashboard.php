<section class="page-head compact">
    <span class="eyebrow">Admin</span>
    <h1>Dashboard</h1>
    <p>Статистика объектов, заявок и пользователей UrbanNest Estate.</p>
</section>
<?php require __DIR__ . '/_nav.php'; ?>

<section class="stats-grid">
    <article><span>Объекты</span><strong><?= e($stats['properties']) ?></strong></article>
    <article><span>Новые заявки</span><strong><?= e($stats['newRequests']) ?></strong></article>
    <article><span>Продажа</span><strong><?= e($stats['sale']) ?></strong></article>
    <article><span>Аренда</span><strong><?= e($stats['rent']) ?></strong></article>
    <article><span>Пользователи</span><strong><?= e($stats['users']) ?></strong></article>
    <article><span>Типы</span><strong><?= e($stats['types']) ?></strong></article>
</section>

<section class="panel">
    <h2>Последние заявки</h2>
    <?php if ($latestRequests): ?>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Клиент</th><th>Объект</th><th>Время связи</th><th>Статус</th></tr></thead>
                <tbody>
                <?php foreach ($latestRequests as $request): ?>
                    <tr>
                        <td><?= e($request['user_name']) ?></td>
                        <td><?= e($request['title']) ?></td>
                        <td><?= e($request['preferred_contact_time']) ?></td>
                        <td><span class="status status-<?= e($request['status']) ?>"><?= e($request['status']) ?></span></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p class="muted">Новых заявок пока нет.</p>
    <?php endif; ?>
</section>

