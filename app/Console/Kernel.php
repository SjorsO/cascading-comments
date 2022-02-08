<?php

namespace App\Console;

use App\Jobs\DownloadReleaseJob;
use App\Jobs\PollReleasesJob;
use App\Jobs\ProcessReleaseJob;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(fn () => PollReleasesJob::run())->everyMinute();

        $schedule->call(fn () => DownloadReleaseJob::run())->everyMinute();

        $schedule->call(fn () => ProcessReleaseJob::run())->everyMinute();
    }
}
