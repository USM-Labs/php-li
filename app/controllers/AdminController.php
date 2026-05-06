<?php

class AdminController
{
    private Property $properties;
    private PropertyType $types;
    private PropertyRequest $requests;
    private User $users;

    public function __construct()
    {
        $this->properties = new Property();
        $this->types = new PropertyType();
        $this->requests = new PropertyRequest();
        $this->users = new User();
    }

    /**
     * Отображает административную панель со статистикой.
     *
     * @return void
     */
    public function dashboard(): void
    {
        requireAdmin();
        view('admin/dashboard', [
            'title' => 'Admin Dashboard',
            'stats' => [
                'properties' => $this->properties->count(),
                'newRequests' => $this->requests->count('new'),
                'sale' => $this->properties->count('deal_type', 'sale'),
                'rent' => $this->properties->count('deal_type', 'rent'),
                'users' => $this->users->count(),
                'types' => $this->types->count(),
            ],
            'latestRequests' => $this->requests->latest(),
        ]);
    }

    /**
     * Отображает управление объектами недвижимости.
     *
     * @return void
     */
    public function properties(): void
    {
        requireAdmin();
        view('admin/properties', [
            'title' => 'Управление объектами',
            'properties' => $this->properties->search([]),
            'types' => $this->types->all(),
            'editProperty' => isset($_GET['edit']) ? $this->properties->find((int)$_GET['edit']) : null,
        ]);
    }

    /**
     * Сохраняет объект недвижимости.
     *
     * @return void
     */
    public function saveProperty(): void
    {
        requireAdmin();
        $data = $this->propertyData();
        $errors = [];
        $uploadedImage = $this->storePropertyImage();
        if ($uploadedImage === false) {
            $errors[] = 'Изображение не было сохранено. Выберите JPG, PNG или WEBP файл до 4 MB и размером минимум 900x900 пикселей.';
        }
        if (is_string($uploadedImage)) {
            $data['image'] = $uploadedImage;
        }
        $errors = array_merge($errors, $this->validateProperty($data));

        if ($errors) {
            foreach ($errors as $error) {
                flash('error', $error);
            }
            redirect('admin/properties');
        }

        if ((int)post('id') > 0) {
            $this->properties->update((int)post('id'), $data);
            flash('success', 'Объект недвижимости обновлен.');
        } else {
            $this->properties->create($data);
            flash('success', 'Объект недвижимости добавлен.');
        }
        redirect('admin/properties');
    }

    /**
     * Удаляет объект недвижимости.
     *
     * @return void
     */
    public function deleteProperty(): void
    {
        requireAdmin();
        $this->properties->delete((int)($_POST['id'] ?? 0));
        flash('success', 'Объект недвижимости удален.');
        redirect('admin/properties');
    }

    /**
     * Отображает управление типами недвижимости.
     *
     * @return void
     */
    public function types(): void
    {
        requireAdmin();
        view('admin/types', [
            'title' => 'Типы недвижимости',
            'types' => $this->types->all(),
            'editType' => isset($_GET['edit']) ? $this->types->find((int)$_GET['edit']) : null,
        ]);
    }

    /**
     * Сохраняет тип недвижимости.
     *
     * @return void
     */
    public function saveType(): void
    {
        requireAdmin();
        $data = ['name' => post('name'), 'description' => post('description')];
        if (mb_strlen($data['name']) < 2) {
            flash('error', 'Название типа недвижимости слишком короткое.');
            redirect('admin/types');
        }
        if ((int)post('id') > 0) {
            $this->types->update((int)post('id'), $data);
            flash('success', 'Тип недвижимости обновлен.');
        } else {
            $this->types->create($data);
            flash('success', 'Тип недвижимости добавлен.');
        }
        redirect('admin/types');
    }

    /**
     * Удаляет тип недвижимости.
     *
     * @return void
     */
    public function deleteType(): void
    {
        requireAdmin();
        try {
            $this->types->delete((int)($_POST['id'] ?? 0));
            flash('success', 'Тип недвижимости удален.');
        } catch (PDOException) {
            flash('error', 'Тип нельзя удалить, пока к нему привязаны объекты.');
        }
        redirect('admin/types');
    }

    /**
     * Отображает управление заявками.
     *
     * @return void
     */
    public function requests(): void
    {
        requireAdmin();
        view('admin/requests', ['title' => 'Заявки', 'requests' => $this->requests->all()]);
    }

    /**
     * Обновляет статус заявки.
     *
     * @return void
     */
    public function requestStatus(): void
    {
        requireAdmin();
        $allowed = ['new', 'contacted', 'scheduled', 'closed', 'cancelled'];
        $status = post('status');
        if (in_array($status, $allowed, true)) {
            $this->requests->updateStatus((int)post('id'), $status);
            flash('success', 'Статус заявки обновлен.');
        }
        redirect('admin/requests');
    }

    /**
     * Отображает управление пользователями.
     *
     * @return void
     */
    public function users(): void
    {
        requireAdmin();
        view('admin/users', ['title' => 'Пользователи', 'users' => $this->users->all()]);
    }

    /**
     * Создает нового администратора.
     *
     * @return void
     */
    public function createAdmin(): void
    {
        requireAdmin();
        $auth = new AuthController();
        $data = [
            'name' => post('name'),
            'email' => post('email'),
            'phone' => post('phone'),
            'password' => post('password'),
            'role' => 'admin',
        ];
        $errors = $auth->validate($data);
        if ($this->users->findByEmail($data['email'])) {
            $errors[] = 'Такой email уже занят.';
        }
        if ($errors) {
            foreach ($errors as $error) {
                flash('error', $error);
            }
        } else {
            $this->users->create($data);
            flash('success', 'Новый администратор создан.');
        }
        redirect('admin/users');
    }

    /**
     * Обновляет роль пользователя.
     *
     * @return void
     */
    public function updateRole(): void
    {
        requireAdmin();
        $role = post('role') === 'admin' ? 'admin' : 'user';
        $this->users->updateRole((int)post('id'), $role);
        flash('success', 'Роль пользователя обновлена.');
        redirect('admin/users');
    }

    /**
     * Собирает данные формы объекта недвижимости.
     *
     * @return array Данные объекта.
     */
    private function propertyData(): array
    {
        return [
            'title' => post('title'),
            'property_type_id' => post('property_type_id'),
            'deal_type' => post('deal_type', 'sale'),
            'city' => post('city'),
            'district' => post('district'),
            'address' => post('address'),
            'rooms' => post('rooms'),
            'area' => post('area'),
            'floor' => post('floor'),
            'total_floors' => post('total_floors'),
            'price' => post('price'),
            'status' => post('status', 'available'),
            'image' => post('current_image'),
            'description' => post('description'),
        ];
    }

    /**
     * Сохраняет загруженное изображение объекта недвижимости.
     *
     * @return string|null|false Сохраненный путь, null при отсутствии файла или false при ошибке.
     */
    private function storePropertyImage(): string|null|false
    {
        if (empty($_FILES['image_file']['name']) || ($_FILES['image_file']['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            return null;
        }
        if ($_FILES['image_file']['error'] !== UPLOAD_ERR_OK || ($_FILES['image_file']['size'] ?? 0) > 4 * 1024 * 1024) {
            return false;
        }
        $allowed = ['image/jpeg', 'image/png', 'image/webp'];
        $mime = mime_content_type($_FILES['image_file']['tmp_name']);
        if (!in_array($mime, $allowed, true)) {
            return false;
        }
        $size = getimagesize($_FILES['image_file']['tmp_name']);
        if (!$size || $size[0] < 900 || $size[1] < 900) {
            return false;
        }

        $filename = date('YmdHis') . '-' . bin2hex(random_bytes(4)) . '.jpg';
        $targetDir = __DIR__ . '/../../public/uploads/properties';
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0775, true);
        }
        $target = $targetDir . '/' . $filename;
        if (!$this->cropImageToSquare($_FILES['image_file']['tmp_name'], $target, $mime)) {
            return false;
        }
        return 'uploads/properties/' . $filename;
    }

    /**
     * Обрезает загруженное изображение по центру и сохраняет его как JPG 900x900.
     *
     * @param string $source Путь к исходному изображению.
     * @param string $target Путь для итогового JPG-файла.
     * @param string $mime MIME-тип исходного файла.
     * @return bool Результат операции.
     */
    private function cropImageToSquare(string $source, string $target, string $mime): bool
    {
        $image = match ($mime) {
            'image/jpeg' => imagecreatefromjpeg($source),
            'image/png' => imagecreatefrompng($source),
            'image/webp' => imagecreatefromwebp($source),
            default => false,
        };
        if (!$image) {
            return false;
        }

        $image = $this->fixJpegOrientation($image, $source, $mime);
        $width = imagesx($image);
        $height = imagesy($image);
        if ($width < 900 || $height < 900) {
            imagedestroy($image);
            return false;
        }

        $side = min($width, $height);
        $sourceX = (int)(($width - $side) / 2);
        $sourceY = (int)(($height - $side) / 2);
        $result = imagecreatetruecolor(900, 900);
        imagefill($result, 0, 0, imagecolorallocate($result, 242, 240, 234));
        imagecopyresampled($result, $image, 0, 0, $sourceX, $sourceY, 900, 900, $side, $side);

        $saved = imagejpeg($result, $target, 88);
        imagedestroy($image);
        imagedestroy($result);
        return $saved;
    }

    /**
     * Применяет EXIF-ориентацию для JPEG-фотографий, сделанных на телефон.
     *
     * @param GdImage $image Исходное изображение.
     * @param string $source Путь к исходному изображению.
     * @param string $mime MIME-тип исходного файла.
     * @return GdImage Изображение с правильной ориентацией.
     */
    private function fixJpegOrientation(GdImage $image, string $source, string $mime): GdImage
    {
        if ($mime !== 'image/jpeg' || !function_exists('exif_read_data')) {
            return $image;
        }
        $exif = @exif_read_data($source);
        $orientation = (int)($exif['Orientation'] ?? 1);
        $rotated = match ($orientation) {
            3 => imagerotate($image, 180, 0),
            6 => imagerotate($image, -90, 0),
            8 => imagerotate($image, 90, 0),
            default => false,
        };
        if (!$rotated) {
            return $image;
        }
        imagedestroy($image);
        return $rotated;
    }

    /**
     * Проверяет данные формы объекта недвижимости.
     *
     * @param array $data Данные объекта.
     * @return array Ошибки валидации.
     */
    private function validateProperty(array $data): array
    {
        $errors = [];
        foreach (['title', 'property_type_id', 'city', 'district', 'address', 'area', 'price', 'description'] as $field) {
            if ($data[$field] === '') {
                $errors[] = 'Заполните обязательные поля объекта недвижимости.';
                break;
            }
        }
        if ($data['image'] === '') {
            $errors[] = 'Загрузите изображение объекта недвижимости.';
        }
        if ((float)$data['area'] <= 0 || (float)$data['price'] <= 0) {
            $errors[] = 'Площадь и цена должны быть больше нуля.';
        }
        return $errors;
    }
}
