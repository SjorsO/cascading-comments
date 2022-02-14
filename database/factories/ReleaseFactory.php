<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ReleaseFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => $name = $this->faker->unique()->numerify('v#.#.#'),
            'order' => $name,
            'commit_hash' => $hash = $this->faker->unique()->sha1,
            'download_url' => 'https://example.com/'.$hash.'.zip',
            'published_at' => $this->faker->dateTimeBetween('-8 years', 'today'),
            'has_downloaded_release' => false,
            'is_processed' => false,
        ];
    }
}
