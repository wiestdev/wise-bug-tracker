<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    // отключаем реальное использование таблицы
    public function getTable(): string
    {
        return '';
    }

    public $timestamps = false;
}
