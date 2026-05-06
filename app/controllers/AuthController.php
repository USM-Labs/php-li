<?php

class AuthController
{
    private User $users;

    public function __construct()
    {
        $this->users = new User();
    }

    /**
     * Отображает форму входа.
     *
     * @return void
     */
    public function loginForm(): void
    {
        view('auth/login', ['title' => 'Вход']);
    }

    /**
     * Выполняет аутентификацию пользователя.
     *
     * @return void
     */
    public function login(): void
    {
        $email = post('email');
        $password = post('password');
        $user = $this->users->findByEmail($email);

        if (!$user || !password_verify($password, $user['password_hash'])) {
            flash('error', 'Неверный email или пароль.');
            redirect('login');
        }

        $_SESSION['user'] = [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'phone' => $user['phone'],
            'role' => $user['role'],
        ];
        flash('success', 'Добро пожаловать в UrbanNest Estate.');
        redirect('dashboard');
    }

    /**
     * Отображает форму регистрации.
     *
     * @return void
     */
    public function registerForm(): void
    {
        view('auth/register', ['title' => 'Регистрация']);
    }

    /**
     * Регистрирует нового пользователя.
     *
     * @return void
     */
    public function register(): void
    {
        $data = [
            'name' => post('name'),
            'email' => post('email'),
            'phone' => post('phone'),
            'password' => post('password'),
            'role' => 'user',
        ];
        $errors = $this->validate($data);

        if ($this->users->findByEmail($data['email'])) {
            $errors[] = 'Пользователь с таким email уже существует.';
        }

        if ($errors) {
            view('auth/register', ['title' => 'Регистрация', 'errors' => $errors, 'old' => $data]);
            return;
        }

        $this->users->create($data);
        flash('success', 'Аккаунт создан. Теперь можно войти.');
        redirect('login');
    }

    /**
     * Завершает сеанс текущего пользователя.
     *
     * @return void
     */
    public function logout(): void
    {
        session_destroy();
        session_start();
        flash('success', 'Вы вышли из аккаунта.');
        redirect('');
    }

    /**
     * Проверяет введенные данные пользователя.
     *
     * @param array $data Данные регистрации.
     * @return array Ошибки валидации.
     */
    public function validate(array $data): array
    {
        $errors = [];
        if (mb_strlen($data['name']) < 2) {
            $errors[] = 'Имя должно содержать минимум 2 символа.';
        }
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Введите корректный email.';
        }
        if (mb_strlen($data['phone']) < 6) {
            $errors[] = 'Введите корректный телефон.';
        }
        if (mb_strlen($data['password']) < 6) {
            $errors[] = 'Пароль должен содержать минимум 6 символов.';
        }
        return $errors;
    }
}
