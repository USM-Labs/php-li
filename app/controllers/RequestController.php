<?php

class RequestController
{
    private PropertyRequest $requests;
    private Property $properties;

    public function __construct()
    {
        $this->requests = new PropertyRequest();
        $this->properties = new Property();
    }

    /**
     * Отображает форму заявки на объект недвижимости.
     *
     * @return void
     */
    public function form(): void
    {
        requireAuth();
        $propertyId = (int)($_GET['property_id'] ?? 0);
        $property = $propertyId ? $this->properties->find($propertyId) : null;
        view('requests/form', [
            'title' => 'Заявка на просмотр',
            'property' => $property,
            'properties' => $this->properties->search(['public' => true, 'status' => 'available']),
        ]);
    }

    /**
     * Сохраняет заявку на объект недвижимости.
     *
     * @return void
     */
    public function store(): void
    {
        requireAuth();
        $property = $this->properties->find((int)post('property_id'));
        $data = [
            'name' => post('name'),
            'phone' => post('phone'),
            'email' => post('email'),
            'preferred_contact_time' => post('preferred_contact_time'),
            'message' => post('message'),
        ];
        $errors = [];

        if (!$property || $property['status'] === 'hidden') {
            $errors[] = 'Выберите доступный объект недвижимости.';
        }
        if (mb_strlen($data['name']) < 2) {
            $errors[] = 'Имя должно содержать минимум 2 символа.';
        }
        if (mb_strlen($data['phone']) < 6) {
            $errors[] = 'Укажите корректный телефон.';
        }
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Введите корректный email.';
        }
        if ($data['preferred_contact_time'] === '') {
            $errors[] = 'Укажите удобное время связи.';
        }

        if ($errors) {
            view('requests/form', [
                'title' => 'Заявка на просмотр',
                'errors' => $errors,
                'old' => $_POST,
                'property' => $property,
                'properties' => $this->properties->search(['public' => true, 'status' => 'available']),
            ]);
            return;
        }

        $this->requests->create([
            'user_id' => currentUser()['id'],
            'property_id' => $property['id'],
            'name' => $data['name'],
            'phone' => $data['phone'],
            'email' => $data['email'],
            'preferred_contact_time' => $data['preferred_contact_time'],
            'message' => $data['message'],
        ]);
        flash('success', 'Заявка отправлена. Менеджер свяжется с вами в указанное время.');
        redirect('dashboard');
    }

    /**
     * Отображает личный кабинет пользователя.
     *
     * @return void
     */
    public function dashboard(): void
    {
        requireAuth();
        view('requests/dashboard', [
            'title' => 'Личный кабинет',
            'requests' => $this->requests->forUser(currentUser()['id']),
        ]);
    }
}
