<section class="auth-page">
    <form class="form-card" method="post" action="<?= url('login') ?>">
        <span class="eyebrow">UrbanNest account</span>
        <h1>Вход</h1>
        <label>Email <input type="email" name="email" required></label>
        <label>Пароль <input type="password" name="password" required minlength="6"></label>
        <button class="btn primary" type="submit">Войти</button>
        <p>Нет аккаунта? <a href="<?= url('register') ?>">Зарегистрироваться</a></p>
    </form>
</section>
