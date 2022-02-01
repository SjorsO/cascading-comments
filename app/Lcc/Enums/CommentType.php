<?php

namespace App\Lcc\Enums;

use RuntimeException;

enum CommentType: int
{
    case SLASH_COMMENT = 1;

    case MULTILINE_COMMENT = 2;

    case LUA_COMMENT = 3;

    case PIPE_COMMENT = 4;

    public static function fromLine($line)
    {
        return match (true) {
            str_starts_with($line, '// ') => CommentType::SLASH_COMMENT,
            str_starts_with($line, '* ') => CommentType::MULTILINE_COMMENT,
            str_starts_with($line, '| ') => CommentType::PIPE_COMMENT,
            default => throw new RuntimeException('Unknown comment prefix: '.$line),
        };
    }
}
