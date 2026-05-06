<section class="auth-page request-page">
    <form class="form-card wide" method="post" action="<?= url('request') ?>">
        <span class="eyebrow">Viewing request</span>
        <h1>Заявка на объект</h1>
        <?php if (!empty($errors)): ?><div class="form-errors"><?php foreach ($errors as $error): ?><p><?= e($error) ?></p><?php endforeach; ?></div><?php endif; ?>
        <label>Объект недвижимости
            <select name="property_id" required>
                <option value="">Выберите объект</option>
                <?php foreach ($properties as $item): ?>
                    <?php $selected = (string)($old['property_id'] ?? $property['id'] ?? '') === (string)$item['id']; ?>
                    <option value="<?= e($item['id']) ?>" <?= $selected ? 'selected' : '' ?>><?= e($item['title'] . ' - ' . $item['city'] . ', €' . number_format((float)$item['price'], 0, '.', ' ')) ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <div class="form-grid">
            <label>Имя <input name="name" required minlength="2" value="<?= e($old['name'] ?? currentUser()['name'] ?? '') ?>"></label>
            <label>Телефон <input type="tel" name="phone" required value="<?= e($old['phone'] ?? currentUser()['phone'] ?? '') ?>"></label>
        </div>
        <label>Email <input type="email" name="email" required value="<?= e($old['email'] ?? currentUser()['email'] ?? '') ?>"></label>
        <label>Удобное время связи <input name="preferred_contact_time" required placeholder="Например: будни после 15:00" value="<?= e($old['preferred_contact_time'] ?? '') ?>"></label>
        <label>Комментарий <textarea name="message" rows="4"><?= e($old['message'] ?? '') ?></textarea></label>
        <button class="btn primary" type="submit">Отправить заявку</button>
    </form>
</section>

