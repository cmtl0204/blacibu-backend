<?php

namespace Database\Factories\Authentication;

use App\Models\Authentication\Route;
use Illuminate\Database\Eloquent\Factories\Factory;

class RouteFactory extends Factory
{
    protected $model = Route::class;

    public function definition()
    {
        return [
            'description' => $this->faker->word,
        ];
    }
}
