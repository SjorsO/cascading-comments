<?php

namespace Database\Seeders;

use App\Jobs\DownloadReleaseJob;
use App\Jobs\PollReleasesJob;
use App\Jobs\ProcessReleaseJob;
use App\Models\Release;
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

        $this->command->info('Getting release from the GitHub api...');

        PollReleasesJob::run();

        while ($releasesLeft = Release::where('has_downloaded_release', false)->count()) {
            $this->command->info('Downloading release zips, '.$releasesLeft.' left...');

            DownloadReleaseJob::run();
        }

        while ($releasesLeft = Release::where('is_processed', false)->count()) {
            $this->command->info('Processing releases, '.$releasesLeft.' left...');

            ProcessReleaseJob::run();
        }
    }
}
