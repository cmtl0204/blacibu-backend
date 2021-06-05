<?php

namespace Database\Factories\App;

use App\Models\App\Institution;
use Illuminate\Database\Eloquent\Factories\Factory;

class InstitutionFactory extends Factory
{
    protected $model = Institution::class;

    public function definition()
    {
        return [
            'denomination' => 'INSTITUTO SUPERIOR TECNOLÓGICO',
        ];
    }
}
