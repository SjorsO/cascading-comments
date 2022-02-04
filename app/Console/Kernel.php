<?php

namespace App\Console;

use App\Jobs\DownloadReleaseZipsJob;
use App\Jobs\PollReleasesJob;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(fn () => PollReleasesJob::run())->everyMinute();

        $schedule->call(fn () => DownloadReleaseZipsJob::run())->everyMinute();
    }
}
