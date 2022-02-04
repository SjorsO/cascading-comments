<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RepositoryFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'owner' => $this->faker->word,
            'last_polled_at' => now()->subYear()->startOfYear(),
            'next_poll_at' => now()->subMinutes(5),
        ];
    }
}
