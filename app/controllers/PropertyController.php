<?php

class PropertyController
{
    private Property $properties;
    private PropertyType $types;

    public function __construct()
    {
        $this->properties = new Property();
        $this->types = new PropertyType();
    }

    /**
     * Отображает публичный каталог недвижимости.
     *
     * @return void
     */
    public function catalog(): void
    {
        $filters = [
            'public' => true,
            'q' => trim($_GET['q'] ?? ''),
            'property_type_id' => $_GET['property_type_id'] ?? '',
            'deal_type' => $_GET['deal_type'] ?? '',
            'rooms' => $_GET['rooms'] ?? '',
            'status' => $_GET['status'] ?? '',
            'max_price' => $_GET['max_price'] ?? '',
            'min_area' => $_GET['min_area'] ?? '',
            'sort' => $_GET['sort'] ?? '',
        ];
        view('properties/catalog', [
            'title' => 'Каталог недвижимости',
            'properties' => $this->properties->search($filters),
            'types' => $this->types->all(),
            'filters' => $filters,
        ]);
    }

    /**
     * Отображает страницу объекта недвижимости.
     *
     * @param int $id Идентификатор объекта.
     * @return void
     */
    public function show(int $id): void
    {
        $property = $this->properties->find($id);
        if (!$property || $property['status'] === 'hidden') {
            http_response_code(404);
            view('home/not_found', ['title' => 'Объект не найден']);
            return;
        }
        view('properties/show', ['title' => $property['title'], 'property' => $property]);
    }
}
