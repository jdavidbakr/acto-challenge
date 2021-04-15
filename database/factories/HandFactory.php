<?php

namespace Database\Factories;

use App\Models\Hand;
use Illuminate\Database\Eloquent\Factories\Factory;

class HandFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Hand::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name'=>$this->faker->name,
            'user_play'=>rand(2, 10),
            'user_score'=>rand(0, 5),
            'computer_play'=>rand(2, 10),
            'computer_score'=>rand(0, 5),
            'user_won'=>rand(0, 1),
        ];
    }
}
