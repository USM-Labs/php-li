<?php

class PropertyRequest
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    /**
     * Создает заявку на просмотр объекта недвижимости.
     *
     * @param array $data Данные заявки.
     * @return bool Результат операции.
     */
    public function create(array $data): bool
    {
        $stmt = $this->db->prepare("INSERT INTO property_requests (user_id, property_id, name, phone, email, message, preferred_contact_time, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, 'new', NOW())");
        return $stmt->execute([
            $data['user_id'], $data['property_id'], $data['name'], $data['phone'], $data['email'], $data['message'], $data['preferred_contact_time'],
        ]);
    }

    /**
     * Возвращает заявки, созданные одним пользователем.
     *
     * @param int $userId Идентификатор пользователя.
     * @return array Строки заявок.
     */
    public function forUser(int $userId): array
    {
        $stmt = $this->db->prepare('SELECT property_requests.*, properties.title, properties.city, properties.district FROM property_requests JOIN properties ON properties.id = property_requests.property_id WHERE property_requests.user_id = ? ORDER BY property_requests.created_at DESC');
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    /**
     * Возвращает все заявки для администрирования.
     *
     * @return array Строки заявок.
     */
    public function all(): array
    {
        return $this->db->query('SELECT property_requests.*, users.name AS user_name, properties.title, properties.city FROM property_requests JOIN users ON users.id = property_requests.user_id JOIN properties ON properties.id = property_requests.property_id ORDER BY property_requests.created_at DESC')->fetchAll();
    }

    /**
     * Возвращает последние заявки.
     *
     * @param int $limit Количество строк.
     * @return array Строки заявок.
     */
    public function latest(int $limit = 5): array
    {
        $stmt = $this->db->prepare('SELECT property_requests.*, users.name AS user_name, properties.title FROM property_requests JOIN users ON users.id = property_requests.user_id JOIN properties ON properties.id = property_requests.property_id ORDER BY property_requests.created_at DESC LIMIT ?');
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Обновляет статус заявки.
     *
     * @param int $id Идентификатор заявки.
     * @param string $status Новый статус.
     * @return bool Результат операции.
     */
    public function updateStatus(int $id, string $status): bool
    {
        $stmt = $this->db->prepare('UPDATE property_requests SET status = ? WHERE id = ?');
        return $stmt->execute([$status, $id]);
    }

    /**
     * Подсчитывает заявки с необязательным фильтром по статусу.
     *
     * @param string|null $status Необязательный статус.
     * @return int Количество заявок.
     */
    public function count(?string $status = null): int
    {
        if ($status) {
            $stmt = $this->db->prepare('SELECT COUNT(*) FROM property_requests WHERE status = ?');
            $stmt->execute([$status]);
            return (int)$stmt->fetchColumn();
        }
        return (int)$this->db->query('SELECT COUNT(*) FROM property_requests')->fetchColumn();
    }
}
