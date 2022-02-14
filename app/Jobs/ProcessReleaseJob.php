<?php

namespace App\Jobs;

use App\Models\Release;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Support\Str;

class ProcessReleaseJob extends BaseJob implements ShouldBeUnique
{
    public function __construct(public Release $release)
    {
        //
    }

    public function handle()
    {
        if ($this->release->is_processed) {
            return;
        }

        $records = [];

        $index = 1;

        foreach ($this->release->files() as $file) {
            foreach ($file->comments as $comment) {
                $records[] = [
                    'release_id' => $this->release->id,
                    'index' => $index++,
                    'file_path' => $file->filePath,
                    'type' => $comment->type,
                    'starts_at_line_number' => $comment->startsAtLineNumber,
                    'number_of_lines' => $comment->numberOfLines,
                    'zip_index' => $file->zipIndex,
                    'is_perfect' => $comment->isPerfect,
                    'text' => $comment->toString(),
                    'updated_at' => now(),
                    'created_at' => now(),
                ];
            }
        }

        foreach (array_chunk($records, 100) as $chunk) {
            $this->release->comments()->insert($chunk);
        }

        $this->release->update([
            'is_processed' => true,
            'comments_count' => $this->release->comments()->count(),
            'perfect_comments_count' => $this->release->comments()->where('is_perfect', true)->count(),
            'imperfect_comments_count' => $this->release->comments()->where('is_perfect', false)->count(),
        ]);
    }

    public function uniqueId()
    {
        // The "ShouldBeUnique" interface can cause the seeder to get stuck.
        return is_production() ? $this->release->id : Str::random();
    }

    public static function run()
    {
        Release::query()
            ->where('has_downloaded_release', true)
            ->where('is_processed', false)
            ->take(20)
            ->get()
            ->each(function (Release $release) {
                ProcessReleaseJob::dispatch($release);
            });
    }
}
