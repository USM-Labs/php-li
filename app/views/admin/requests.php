<section class="page-head compact">
    <span class="eyebrow">Admin</span>
    <h1>Заявки на просмотр</h1>
</section>
<?php require __DIR__ . '/_nav.php'; ?>

<section class="panel">
    <div class="table-wrap">
        <table>
            <thead><tr><th>Клиент</th><th>Объект</th><th>Телефон</th><th>Email</th><th>Время связи</th><th>Комментарий</th><th>Статус</th></tr></thead>
            <tbody>
            <?php foreach ($requests as $request): ?>
                <tr>
                    <td><?= e($request['name']) ?><br><span class="muted"><?= e($request['user_name']) ?></span></td>
                    <td><?= e($request['title']) ?><br><span class="muted"><?= e($request['city']) ?></span></td>
                    <td><?= e($request['phone']) ?></td>
                    <td><?= e($request['email']) ?></td>
                    <td><?= e($request['preferred_contact_time']) ?></td>
                    <td><?= e($request['message']) ?></td>
                    <td>
                        <form method="post" action="<?= url('admin/requests/status') ?>">
                            <input type="hidden" name="id" value="<?= e($request['id']) ?>">
                            <select name="status" onchange="this.form.submit()">
                                <?php foreach (['new', 'contacted', 'scheduled', 'closed', 'cancelled'] as $status): ?>
                                    <option value="<?= e($status) ?>" <?= $request['status'] === $status ? 'selected' : '' ?>><?= e($status) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>

