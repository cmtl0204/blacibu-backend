<?php

namespace Database\Factories\App;

use App\Models\App\Professional;
use Illuminate\Database\Eloquent\Factories\Factory;

class TeacherFactory extends Factory
{
    protected $model = Professional::class;

    public function definition()
    {
        return [
            'state_id' => 1
        ];
    }
}
