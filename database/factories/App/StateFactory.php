<?php

namespace Database\Factories\App;


use Illuminate\Database\Eloquent\Factories\Factory;

class StateFactory extends Factory
{
    protected $model = State::class;

    public function definition()
    {
        return [
            'code' => $this->faker->word,
            'name' => $this->faker->sentence,
        ];
    }
}
