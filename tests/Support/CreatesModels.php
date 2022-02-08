<?php

namespace Tests\Support;

use App\Models\Release;
use App\Models\Repository;
use Illuminate\Support\Facades\Storage;

class CreatesModels
{
    public static function release($repository, $zipFilePath = null): Release
    {
        if (is_string($repository)) {
            [$repository, $zipFilePath] = [$zipFilePath, $repository];
        }

        $repository ??= Repository::factory()->create();

        $repository->releases()->save(
            $release = Release::factory()->make(['has_downloaded_release' => true])
        );

        Storage::makeDirectory(
            dirname($release->zip_storage_path)
        );

        copy(
            $zipFilePath,
            Storage::path($release->zip_storage_path)
        );

        return $release->refresh();
    }
}
