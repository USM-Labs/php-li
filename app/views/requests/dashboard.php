<section class="page-head compact">
    <span class="eyebrow">Profile</span>
    <h1>Личный кабинет</h1>
    <p><?= e(currentUser()['name']) ?>, здесь отображаются ваши заявки на просмотр объектов.</p>
</section>

<?php if ($requests): ?>
    <section class="panel">
        <div class="table-wrap">
            <table>
                <thead><tr><th>Объект</th><th>Локация</th><th>Время связи</th><th>Комментарий</th><th>Статус</th><th>Создано</th></tr></thead>
                <tbody>
                <?php foreach ($requests as $request): ?>
                    <tr>
                        <td><?= e($request['title']) ?></td>
                        <td><?= e($request['city']) ?>, <?= e($request['district']) ?></td>
                        <td><?= e($request['preferred_contact_time']) ?></td>
                        <td><?= e($request['message']) ?></td>
                        <td><span class="status status-<?= e($request['status']) ?>"><?= e($request['status']) ?></span></td>
                        <td><?= e($request['created_at']) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>
<?php else: ?>
    <section class="empty-state">
        <h2>Заявок пока нет</h2>
        <p>Выберите объект в каталоге и оставьте заявку на просмотр.</p>
        <a class="btn primary" href="<?= url('properties') ?>">Открыть каталог</a>
    </section>
<?php endif; ?>
