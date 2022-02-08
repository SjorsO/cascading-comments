<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $casts = [
        'release_id' => 'int',
        'zip_index' => 'int',
        'starts_at_line_number' => 'int',
        'number_of_lines' => 'int',
        'is_perfect' => 'bool',
    ];
}
