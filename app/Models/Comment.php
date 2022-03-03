<?php

namespace App\Models;

use App\Lcc\Enums\CommentType;
use Illuminate\Database\Eloquent\Casts\Attribute;
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

    public function release()
    {
        return $this->belongsTo(Release::class);
    }

    public function githubPermalink(): Attribute
    {
        return new Attribute(
            get: fn () => sprintf(
                'https://github.com/%s/%s/blob/%s/%s#L%s-L%s',
                $this->release->repository->owner,
                $this->release->repository->name,
                $this->release->commit_hash,
                $this->file_path,
                $this->starts_at_line_number + 1,
                $this->starts_at_line_number + $this->number_of_lines
            ),
        );
    }
}
