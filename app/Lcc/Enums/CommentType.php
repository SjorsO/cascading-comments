<?php

namespace App\Lcc\Enums;

enum CommentType: int
{
    case SLASH_COMMENT = 1;

    case MULTILINE_COMMENT = 2;

    case LUA_COMMENT = 3;

    case PIPE_COMMENT = 4;

    public static function fromLine($line): CommentType
    {
        return match (true) {
            str_starts_with($line, '// ') => CommentType::SLASH_COMMENT,
            str_starts_with($line, '* ') => CommentType::MULTILINE_COMMENT,
            str_starts_with($line, '-- ') => CommentType::LUA_COMMENT,
            str_starts_with($line, '| ') => CommentType::PIPE_COMMENT,
        };
    }

    public const COMMENT_PREFIXES = [
        '// ', // SLASH_COMMENT
        '* ', // MULTILINE_COMMENT
        '-- ', // LUA_COMMENT
        '| ', // PIPE_COMMENT
    ];
}
