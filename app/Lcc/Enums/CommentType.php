<?php

namespace App\Lcc\Enums;

enum CommentType: int
{
    case SLASH_COMMENT = 1;

    case MULTILINE_COMMENT = 2;

    case LUA_COMMENT = 3;

    case PIPE_COMMENT = 4;

    public static function fromLine($line)
    {
        $type = match (true) {
            str_starts_with($line, '// ') => CommentType::SLASH_COMMENT,
            str_starts_with($line, '* ') => CommentType::MULTILINE_COMMENT,
            str_starts_with($line, '-- ') => CommentType::LUA_COMMENT,
            str_starts_with($line, '| ') => CommentType::PIPE_COMMENT,
        };

        return [
            $type, CommentType::COMMENT_PREFIXES[$type->value]
        ];
    }

    public const COMMENT_PREFIXES = [
        1 => '// ', // SLASH_COMMENT
        2 => '* ', // MULTILINE_COMMENT
        3 => '-- ', // LUA_COMMENT
        4 => '| ', // PIPE_COMMENT
    ];
}
