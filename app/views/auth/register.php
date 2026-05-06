<section class="auth-page">
    <form class="form-card" method="post" action="<?= url('register') ?>">
        <span class="eyebrow">Join UrbanNest</span>
        <h1>Регистрация</h1>
        <?php if (!empty($errors)): ?><div class="form-errors"><?php foreach ($errors as $error): ?><p><?= e($error) ?></p><?php endforeach; ?></div><?php endif; ?>
        <label>Имя <input type="text" name="name" required minlength="2" value="<?= e($old['name'] ?? '') ?>"></label>
        <label>Email <input type="email" name="email" required value="<?= e($old['email'] ?? '') ?>"></label>
        <label>Телефон <input type="tel" name="phone" required value="<?= e($old['phone'] ?? '') ?>"></label>
        <label>Пароль <input type="password" name="password" required minlength="6"></label>
        <button class="btn primary" type="submit">Создать аккаунт</button>
    </form>
</section>
