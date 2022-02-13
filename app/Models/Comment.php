<?php

namespace App\Models;

use App\Lcc\Enums\CommentType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $casts = [
        'release_id' => 'int',
        'index' => 'int',
        'zip_index' => 'int',
        'type' => CommentType::class,
        'starts_at_line_number' => 'int',
        'number_of_lines' => 'int',
        'is_perfect' => 'bool',
    ];
}
