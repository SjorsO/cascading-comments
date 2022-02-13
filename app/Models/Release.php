<?php

namespace App\Models;

use App\Lcc\ReleaseFile;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ZipArchive;

class Release extends Model
{
    use HasFactory;

    protected $casts = [
        'repository_id' => 'int',
        'published_at' => 'datetime',
        'has_downloaded_release' => 'bool',
        'is_processed' => 'bool',
    ];

    public function repository()
    {
        return $this->belongsTo(Repository::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function getZipStoragePathAttribute()
    {
        return sprintf('releases/%s/%s/%s.zip', $this->commit_hash[0], $this->commit_hash[1], $this->commit_hash);
    }

    /** @return ReleaseFile[] */
    public function files()
    {
        $files = [];

        $zip = new ZipArchive();

        $zip->open(
            Storage::path($this->zip_storage_path)
        );

        for ($i = 0; $i < $zip->numFiles; $i++) {
            $stat = $zip->statIndex($i);

            $zipIndex = $stat['index'];

            $filePath = $stat['name'];

            // Skip directories
            if (str_ends_with($filePath, '/')) {
                continue;
            }

            $contents = $zip->getFromIndex($zipIndex);

            $files[] = new ReleaseFile(
                Str::after($filePath, '/'),
                $zipIndex,
                $contents
            );
        }

        return $files;
    }
}
