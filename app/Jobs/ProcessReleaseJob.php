<?php

namespace App\Jobs;

use App\Models\Release;
use Illuminate\Contracts\Queue\ShouldBeUnique;

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

        foreach ($this->release->files() as $file) {
            foreach ($file->comments() as $comment) {
                $records[] = [
                    'release_id' => $this->release->id,
                    'file_path' => $file->filePath,
                    'starts_at_line_number' => $comment->startsAtLineNumber,
                    'number_of_lines' => $comment->lines_count,
                    'zip_index' => $file->zipIndex,
                    'is_perfect' => $comment->is_perfect,
                ];
            }
        }

        foreach (array_chunk($records, 100) as $chunk) {
            $this->release->comments()->insert($chunk);
        }

        $this->release->update(['is_processed' => true]);
    }

    public function uniqueId()
    {
        return $this->release->id;
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
