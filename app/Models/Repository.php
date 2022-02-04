<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Repository extends Model
{
    use HasFactory;

    protected $casts = [
        'next_poll_at' => 'datetime',
        'last_polled_at' => 'datetime',
    ];

    public function releases()
    {
        return $this->hasMany(Release::class);
    }
}
