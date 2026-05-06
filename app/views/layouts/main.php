<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title ?? APP_NAME) ?> | <?= APP_NAME ?></title>
    <link rel="stylesheet" href="<?= url('assets/css/style.css') ?>">
</head>
<body>
<header class="site-header">
    <a class="brand" href="<?= url('') ?>">UrbanNest<span>Estate</span></a>
    <button class="menu-toggle" type="button" data-menu-toggle>Menu</button>
    <nav class="main-nav" data-menu>
        <a href="<?= url('properties') ?>">Объекты</a>
        <?php if (isAuthenticated()): ?>
            <a href="<?= url('dashboard') ?>">Кабинет</a>
            <?php if (isAdmin()): ?><a href="<?= url('admin') ?>">Admin</a><?php endif; ?>
            <a href="<?= url('logout') ?>">Выход</a>
        <?php else: ?>
            <a href="<?= url('login') ?>">Вход</a>
            <a class="nav-cta" href="<?= url('register') ?>">Регистрация</a>
        <?php endif; ?>
    </nav>
</header>

<main>
    <?php if (!empty($_SESSION['flash'])): ?>
        <section class="flash-wrap">
            <?php foreach ($_SESSION['flash'] as $flash): ?>
                <div class="flash <?= e($flash['type']) ?>"><?= e($flash['message']) ?></div>
            <?php endforeach; unset($_SESSION['flash']); ?>
        </section>
    <?php endif; ?>

    <?php require $viewFile; ?>
</main>

<footer class="site-footer">
    <strong>UrbanNest Estate</strong>
    <span>Современная недвижимость с понятной структурой и спокойным выбором.</span>
</footer>
<script src="<?= url('assets/js/app.js') ?>"></script>
</body>
</html>

