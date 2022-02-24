<?php

namespace App\Jobs;

use App\Lcc\Github\GithubApi;
use App\Lcc\Github\Records\TagRecord;
use App\Models\Release;
use App\Models\Repository;
use Illuminate\Support\Facades\Storage;

class PollReleasesJob extends BaseJob
{
    public function __construct(public Repository $repository)
    {
        //
    }

    public function handle()
    {
        $releases = (new GithubApi)->getReleases($this->repository->owner, $this->repository->name);

        if (! $releases) {
            return;
        }

        $existingReleaseNames = $this->repository->releases()->pluck('name');

        $records = array_map(function (TagRecord $record) use ($existingReleaseNames) {
            if ($existingReleaseNames->contains($record->name)) {
                return null;
            }

            $hasDownloadedRelease = Storage::exists(
                Release::make(['commit_hash' => $record->commitHash])->zip_storage_path
            );

            return [
                'repository_id' => $this->repository->id,
                'name' => $record->name,
                'order' => $record->nameForOrdering,
                'commit_hash' => $record->commitHash,
                'download_url' => $record->downloadUrl,
                'published_at' => $record->publishedAt,
                'has_downloaded_release' => $hasDownloadedRelease,
                'updated_at' => now(),
                'created_at' => now(),
            ];
        }, $releases);

        $this->repository->releases()->insert(
            array_filter($records)
        );

        $this->repository->update(['last_polled_at' => now()]);
    }

    public static function run()
    {
        Repository::query()
            ->where('next_poll_at', '<=', now())
            ->get()
            ->each(function (Repository $repository) {
                $repository->update([
                    // Add minutes so that we don't poll multiple repositories at the same time. That
                    // could cause rate limit issues with the GitHub api.
                    'next_poll_at' => now()->addHour()->addMinutes($repository->id % 12 * 5),
                ]);

                PollReleasesJob::dispatch($repository);
            });
    }
}
