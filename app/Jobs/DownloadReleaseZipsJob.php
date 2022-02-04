<?php

namespace App\Jobs;

use App\Models\Release;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class DownloadReleaseZipsJob extends BaseJob
{
    public function __construct(public Release $release)
    {
        //
    }

    public function handle()
    {
        if ($this->release->has_downloaded_release) {
            return;
        }

        $filePath = Storage::path($this->release->zip_storage_path);

        Storage::makeDirectory(
            dirname($this->release->zip_storage_path)
        );

        Http::timeout(5)->sink($filePath)->get($this->release->download_url);

        $this->release->update(['has_downloaded_release' => true]);
    }

    public static function run()
    {
        Release::query()
            ->where('has_downloaded_release', false)
            ->take(50)
            ->get()
            ->each(function (Release $release, $i) {
                // Add a delay to each job to prevent rate limit issues.
                DownloadReleaseZipsJob::dispatch($release)->delay(
                    now()->addSeconds($i * 5)
                );
            });
    }
}
