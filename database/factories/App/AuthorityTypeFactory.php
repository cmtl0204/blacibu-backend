<?php

namespace Database\Factories\App;

use App\Models\App\AuthorityType;
use Illuminate\Database\Eloquent\Factories\Factory;

class AuthorityTypeFactory extends Factory
{
    protected $model = AuthorityType::class;

    public function definition()
    {
        return [
            'name' => $this->faker->jobTitle,
        ];
    }
}
