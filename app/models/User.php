<?php

class User
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    /**
     * Находит пользователя по email.
     *
     * @param string $email Email пользователя.
     * @return array|null Строка пользователя или null.
     */
    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    /**
     * Находит пользователя по идентификатору.
     *
     * @param int $id Идентификатор пользователя.
     * @return array|null Строка пользователя или null.
     */
    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT id, name, email, phone, role, created_at FROM users WHERE id = ?');
        $stmt->execute([$id]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    /**
     * Создает учетную запись пользователя.
     *
     * @param array $data Введенные данные пользователя.
     * @return bool Результат операции.
     */
    public function create(array $data): bool
    {
        $stmt = $this->db->prepare('INSERT INTO users (name, email, phone, password_hash, role, created_at) VALUES (?, ?, ?, ?, ?, NOW())');
        return $stmt->execute([
            $data['name'],
            $data['email'],
            $data['phone'],
            password_hash($data['password'], PASSWORD_DEFAULT),
            $data['role'] ?? 'user',
        ]);
    }

    /**
     * Возвращает всех пользователей для администрирования.
     *
     * @return array Строки пользователей.
     */
    public function all(): array
    {
        return $this->db->query('SELECT id, name, email, phone, role, created_at FROM users ORDER BY created_at DESC')->fetchAll();
    }

    /**
     * Обновляет роль пользователя.
     *
     * @param int $id Идентификатор пользователя.
     * @param string $role Новая роль.
     * @return bool Результат операции.
     */
    public function updateRole(int $id, string $role): bool
    {
        $stmt = $this->db->prepare('UPDATE users SET role = ? WHERE id = ?');
        return $stmt->execute([$role, $id]);
    }

    /**
     * Подсчитывает всех пользователей.
     *
     * @return int Количество пользователей.
     */
    public function count(): int
    {
        return (int)$this->db->query('SELECT COUNT(*) FROM users')->fetchColumn();
    }
}
