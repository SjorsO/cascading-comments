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

    public function latestRelease()
    {
        return $this->hasOne(Release::class)->ofMany(
            ['order' => 'max'],
            fn ($query) => $query->whereNotNull('is_processed')
        );
    }

    public function getLogoUrlAttribute()
    {
        return match ($this->display_name) {
            'laravel/laravel', 'laravel/framework' => asset('images/repository-logos/laravel.svg'),
            'laravel/dusk' => asset('images/repository-logos/dusk.png'),
            'laravel/horizon' => asset('images/repository-logos/horizon.png'),
            'laravel/sanctum' => asset('images/repository-logos/sanctum.png'),
            'laravel/sail' => asset('images/repository-logos/sail.png'),
            'laravel/breeze' => asset('images/repository-logos/breeze.png'),
            'laravel/envoy' => asset('images/repository-logos/envoyer.svg'),
            default => asset('favicon.png'),
        };
    }

    public function getDisplayNameAttribute()
    {
        return $this->owner.'/'.$this->name;
    }
}
