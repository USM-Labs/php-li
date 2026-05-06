<?php

class Property
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    /**
     * Возвращает объекты недвижимости с учетом фильтров и сортировки.
     *
     * @param array $filters Фильтры поиска.
     * @return array Строки объектов недвижимости.
     */
    public function search(array $filters = []): array
    {
        $sql = 'SELECT properties.*, property_types.name AS type_name FROM properties LEFT JOIN property_types ON property_types.id = properties.property_type_id WHERE 1=1';
        $params = [];

        if (!empty($filters['public'])) {
            $sql .= " AND properties.status <> 'hidden'";
        }
        if (!empty($filters['q'])) {
            $sql .= ' AND (properties.title LIKE ? OR properties.city LIKE ? OR properties.district LIKE ?)';
            $like = '%' . $filters['q'] . '%';
            $params[] = $like;
            $params[] = $like;
            $params[] = $like;
        }
        if (!empty($filters['property_type_id'])) {
            $sql .= ' AND properties.property_type_id = ?';
            $params[] = (int)$filters['property_type_id'];
        }
        if (!empty($filters['deal_type'])) {
            $sql .= ' AND properties.deal_type = ?';
            $params[] = $filters['deal_type'];
        }
        if (!empty($filters['rooms'])) {
            $sql .= ' AND properties.rooms = ?';
            $params[] = (int)$filters['rooms'];
        }
        if (!empty($filters['status'])) {
            $sql .= ' AND properties.status = ?';
            $params[] = $filters['status'];
        }
        if (!empty($filters['max_price'])) {
            $sql .= ' AND properties.price <= ?';
            $params[] = (float)$filters['max_price'];
        }
        if (!empty($filters['min_area'])) {
            $sql .= ' AND properties.area >= ?';
            $params[] = (float)$filters['min_area'];
        }

        $sortMap = [
            'price_asc' => 'properties.price ASC',
            'price_desc' => 'properties.price DESC',
            'area_desc' => 'properties.area DESC',
            'date_desc' => 'properties.created_at DESC',
            'title_asc' => 'properties.title ASC',
        ];
        $sql .= ' ORDER BY ' . ($sortMap[$filters['sort'] ?? ''] ?? 'properties.created_at DESC');

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Возвращает избранные объекты для главной страницы.
     *
     * @param int $limit Количество объектов.
     * @return array Строки объектов недвижимости.
     */
    public function featured(int $limit = 6): array
    {
        $stmt = $this->db->prepare("SELECT properties.*, property_types.name AS type_name FROM properties LEFT JOIN property_types ON property_types.id = properties.property_type_id WHERE properties.status = 'available' ORDER BY properties.created_at DESC LIMIT ?");
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Находит объект недвижимости по идентификатору.
     *
     * @param int $id Идентификатор объекта.
     * @return array|null Строка объекта или null.
     */
    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT properties.*, property_types.name AS type_name FROM properties LEFT JOIN property_types ON property_types.id = properties.property_type_id WHERE properties.id = ?');
        $stmt->execute([$id]);
        $property = $stmt->fetch();
        return $property ?: null;
    }

    /**
     * Создает объект недвижимости.
     *
     * @param array $data Данные объекта.
     * @return bool Результат операции.
     */
    public function create(array $data): bool
    {
        $stmt = $this->db->prepare('INSERT INTO properties (title, property_type_id, deal_type, city, district, address, rooms, area, floor, total_floors, price, status, image, description, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())');
        return $stmt->execute($this->payload($data));
    }

    /**
     * Обновляет объект недвижимости.
     *
     * @param int $id Идентификатор объекта.
     * @param array $data Данные объекта.
     * @return bool Результат операции.
     */
    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare('UPDATE properties SET title = ?, property_type_id = ?, deal_type = ?, city = ?, district = ?, address = ?, rooms = ?, area = ?, floor = ?, total_floors = ?, price = ?, status = ?, image = ?, description = ?, updated_at = NOW() WHERE id = ?');
        $payload = $this->payload($data);
        $payload[] = $id;
        return $stmt->execute($payload);
    }

    /**
     * Удаляет объект недвижимости.
     *
     * @param int $id Идентификатор объекта.
     * @return bool Результат операции.
     */
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM properties WHERE id = ?');
        return $stmt->execute([$id]);
    }

    /**
     * Подсчитывает объекты недвижимости с дополнительным фильтром.
     *
     * @param string|null $field Имя поля.
     * @param string|null $value Значение поля.
     * @return int Количество объектов.
     */
    public function count(?string $field = null, ?string $value = null): int
    {
        if ($field && $value !== null && in_array($field, ['status', 'deal_type'], true)) {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM properties WHERE {$field} = ?");
            $stmt->execute([$value]);
            return (int)$stmt->fetchColumn();
        }
        return (int)$this->db->query('SELECT COUNT(*) FROM properties')->fetchColumn();
    }

    /**
     * Преобразует данные формы в набор значений для базы данных.
     *
     * @param array $data Данные объекта.
     * @return array Подготовленные значения.
     */
    private function payload(array $data): array
    {
        return [
            $data['title'], (int)$data['property_type_id'], $data['deal_type'], $data['city'], $data['district'], $data['address'],
            (int)$data['rooms'], (float)$data['area'], (int)$data['floor'], (int)$data['total_floors'],
            (float)$data['price'], $data['status'], $data['image'], $data['description'],
        ];
    }
}
