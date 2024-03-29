<?php

namespace App\Http\Requests\App;

class AppFormRequest
{
    public static function rules($rules = [])
    {
        return array_merge($rules, [
            'per_page' => [
                'integer',
                'min:1',
                'max:100',
            ],
            'page' => [
                'integer',
            ],
            'search' => [
                'min:1',
                'max:100',
            ],
        ]);
    }

    public static function attributes($attributes = [])
    {
        return array_merge($attributes, [
            'per_page' => 'por página',
            'page' => 'página',
            'search' => 'búsqueda',
        ]);
    }
}
