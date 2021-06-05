<?php

namespace Database\Factories\Authentication;

use App\Models\Authentication\SecurityQuestion;
use Illuminate\Database\Eloquent\Factories\Factory;

class SecurityQuestionFactory extends Factory
{
    protected $model = SecurityQuestion::class;

    public function definition()
    {
        return [
            'name' => $this->faker->realText($maxNbChars = 200, $indexSize = 2)
        ];
    }
}
