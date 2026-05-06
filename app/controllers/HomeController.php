<?php

class HomeController
{
    /**
     * Отображает главную страницу.
     *
     * @return void
     */
    public function index(): void
    {
        view('home/index', [
            'title' => 'Современный каталог недвижимости',
            'properties' => (new Property())->featured(6),
            'types' => (new PropertyType())->all(),
        ]);
    }
}
