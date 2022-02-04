<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Release extends Model
{
    protected $casts = [
        'repository_id' => 'int',
        'published_at' => 'datetime',
        'is_processed' => 'bool',
        'has_downloaded_release' => 'bool',
    ];

    public function repository()
    {
        return $this->belongsTo(Repository::class);
    }

    public function getZipStoragePathAttribute()
    {
        return sprintf('releases/%s/%s/%s.zip', $this->commit_hash[0], $this->commit_hash[1], $this->id);
    }
}
