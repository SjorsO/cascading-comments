<?php

namespace Database\Seeders;

use App\Models\Repository;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        Repository::create([
            'owner' => 'laravel',
            'name' => 'laravel',
            'next_poll_at' => now()->startOfDay(),
            'last_polled_at' => now()->subYear()->startOfYear(),
        ]);

        Repository::create([
            'owner' => 'laravel',
            'name' => 'framework',
            'next_poll_at' => now()->startOfDay(),
            'last_polled_at' => now()->subYear()->startOfYear(),
        ]);
    }
}
