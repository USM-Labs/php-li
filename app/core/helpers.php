<?php

/**
 * Экранирует значение перед выводом в HTML.
 *
 * @param mixed $value Значение для экранирования.
 * @return string Безопасная HTML-строка.
 */
function e(mixed $value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

/**
 * Формирует URL внутри приложения.
 *
 * @param string $path Путь внутри приложения.
 * @return string Абсолютный локальный URL-путь.
 */
function url(string $path = ''): string
{
    return BASE_URL . ($path ? '/' . ltrim($path, '/') : '');
}

/**
 * Формирует URL медиафайла из внешнего или локального пути.
 *
 * @param string|null $path Сохраненный путь к медиафайлу.
 * @return string URL, готовый для использования в браузере.
 */
function mediaUrl(?string $path): string
{
    $path = trim((string)$path);
    if ($path === '') {
        return url('assets/img/properties/apartment-central-residence.jpg');
    }
    if (preg_match('/^https?:\/\//', $path)) {
        return $path;
    }
    return url($path);
}

/**
 * Перенаправляет пользователя на путь внутри приложения.
 *
 * @param string $path Путь внутри приложения.
 * @return void
 */
function redirect(string $path): void
{
    header('Location: ' . url($path));
    exit;
}

/**
 * Отображает представление внутри основного шаблона.
 *
 * @param string $view Имя представления.
 * @param array $data Переменные представления.
 * @return void
 */
function view(string $view, array $data = []): void
{
    extract($data, EXTR_SKIP);
    $viewFile = __DIR__ . '/../views/' . $view . '.php';
    require __DIR__ . '/../views/layouts/main.php';
}

/**
 * Сохраняет flash-сообщение для следующего запроса.
 *
 * @param string $type Тип сообщения.
 * @param string $message Текст сообщения.
 * @return void
 */
function flash(string $type, string $message): void
{
    $_SESSION['flash'][] = ['type' => $type, 'message' => $message];
}

/**
 * Возвращает данные текущего авторизованного пользователя.
 *
 * @return array|null Данные пользователя или null.
 */
function currentUser(): ?array
{
    return $_SESSION['user'] ?? null;
}

/**
 * Проверяет состояние авторизации.
 *
 * @return bool True, если пользователь вошел в систему.
 */
function isAuthenticated(): bool
{
    return isset($_SESSION['user']);
}

/**
 * Проверяет роль администратора.
 *
 * @return bool True, если текущий пользователь является администратором.
 */
function isAdmin(): bool
{
    return ($_SESSION['user']['role'] ?? '') === 'admin';
}

/**
 * Требует авторизованного пользователя.
 *
 * @return void
 */
function requireAuth(): void
{
    if (!isAuthenticated()) {
        flash('error', 'Войдите в аккаунт, чтобы открыть защищенный раздел.');
        redirect('login');
    }
}

/**
 * Требует пользователя с ролью администратора.
 *
 * @return void
 */
function requireAdmin(): void
{
    requireAuth();
    if (!isAdmin()) {
        flash('error', 'У вас нет доступа к административной панели.');
        redirect('dashboard');
    }
}

/**
 * Возвращает очищенное значение из POST.
 *
 * @param string $key Имя поля.
 * @param string $default Значение по умолчанию.
 * @return string Значение без лишних пробелов.
 */
function post(string $key, string $default = ''): string
{
    return trim($_POST[$key] ?? $default);
}
