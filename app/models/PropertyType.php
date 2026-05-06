<?php

class PropertyType
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    /**
     * Возвращает все типы недвижимости.
     *
     * @return array Строки типов недвижимости.
     */
    public function all(): array
    {
        return $this->db->query('SELECT * FROM property_types ORDER BY name')->fetchAll();
    }

    /**
     * Находит тип недвижимости по идентификатору.
     *
     * @param int $id Идентификатор типа.
     * @return array|null Строка типа или null.
     */
    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM property_types WHERE id = ?');
        $stmt->execute([$id]);
        $type = $stmt->fetch();
        return $type ?: null;
    }

    /**
     * Создает тип недвижимости.
     *
     * @param array $data Данные типа.
     * @return bool Результат операции.
     */
    public function create(array $data): bool
    {
        $stmt = $this->db->prepare('INSERT INTO property_types (name, description, created_at) VALUES (?, ?, NOW())');
        return $stmt->execute([$data['name'], $data['description']]);
    }

    /**
     * Обновляет тип недвижимости.
     *
     * @param int $id Идентификатор типа.
     * @param array $data Данные типа.
     * @return bool Результат операции.
     */
    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare('UPDATE property_types SET name = ?, description = ? WHERE id = ?');
        return $stmt->execute([$data['name'], $data['description'], $id]);
    }

    /**
     * Удаляет тип недвижимости.
     *
     * @param int $id Идентификатор типа.
     * @return bool Результат операции.
     */
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM property_types WHERE id = ?');
        return $stmt->execute([$id]);
    }

    /**
     * Подсчитывает количество типов недвижимости.
     *
     * @return int Количество типов.
     */
    public function count(): int
    {
        return (int)$this->db->query('SELECT COUNT(*) FROM property_types')->fetchColumn();
    }
}
