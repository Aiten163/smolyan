<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Worker>
 */
class WorkerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => $this->faker->firstName('male'),
            'second_name' => $this->faker->lastName('male'),
            'middle_name' => $this->faker->firstName('male'),
            'birthday' => $this->faker->date('Y-m-d', '2000-01-01'),
            'position' => $this->faker->jobTitle
        ];
    }
}
