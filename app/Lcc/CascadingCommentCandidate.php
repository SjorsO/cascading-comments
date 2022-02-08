<?php

namespace App\Lcc;

use App\Lcc\Enums\CommentType;
use Illuminate\Support\Str;
use RuntimeException;

class CascadingCommentCandidate
{
    public const COMMENT_PREFIXES = [
        '// ',
        '* ',
        '| ',
    ];

    public CommentType $type;

    public array $lines;

    public bool $isActuallyACascadingComment;

    public function __construct($lines)
    {
        $this->type = CommentType::fromLine($lines[0]);

        $this->lines = array_map(function ($line) {
            foreach (static::COMMENT_PREFIXES as $prefix) {
                if (str_starts_with($line, $prefix)) {
                    return Str::after($line, $prefix);
                }
            }

            throw new RuntimeException('Not all lines have a prefix');
        }, $lines);

        $this->isActuallyACascadingComment = $this->isForReal();
    }

    private function isForReal()
    {
        if (count($this->lines) < 3) {
            return false;
        }

        $lastLength = mb_strlen($this->lines[0]);

        for ($i = 1; $i < count($this->lines); $i++) {
            $length = mb_strlen($this->lines[$i]);

            $diff = $lastLength - $length;

            if ($diff >= 6 || $diff <= -6) {
                return false;
            }

            $lastLength = $length;
        }

        return true;
    }

    public function toString()
    {
        return implode("\n", $this->lines);
    }
}
