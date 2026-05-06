<section class="page-head compact">
    <span class="eyebrow">Admin</span>
    <h1>Пользователи</h1>
</section>
<?php require __DIR__ . '/_nav.php'; ?>

<section class="panel two-col">
    <form class="admin-form" method="post" action="<?= url('admin/users/admin') ?>">
        <h2>Создать администратора UrbanNest</h2>
        <label>Имя <input name="name" required minlength="2"></label>
        <label>Email <input type="email" name="email" required></label>
        <label>Телефон <input name="phone" required></label>
        <label>Пароль <input type="password" name="password" required minlength="6"></label>
        <button class="btn primary" type="submit">Создать admin</button>
    </form>
    <div class="table-wrap">
        <table>
            <thead><tr><th>Имя</th><th>Email</th><th>Телефон</th><th>Роль</th></tr></thead>
            <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= e($user['name']) ?></td>
                    <td><?= e($user['email']) ?></td>
                    <td><?= e($user['phone']) ?></td>
                    <td>
                        <form method="post" action="<?= url('admin/users/role') ?>">
                            <input type="hidden" name="id" value="<?= e($user['id']) ?>">
                            <select name="role" onchange="this.form.submit()">
                                <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>user</option>
                                <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>admin</option>
                            </select>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
