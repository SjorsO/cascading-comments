<?php

namespace App\Lcc;

use App\Lcc\Enums\CommentType;
use Illuminate\Support\Str;
use RuntimeException;

class CascadingCommentCandidate
{
    public CommentType $type;

    public array $lines;

    public bool $isActuallyACascadingComment;

    public function __construct($candidateLines, public int $startsAtLineNumber, private array $allLines)
    {
        $this->type = CommentType::fromLine($candidateLines[0]);

        $this->lines = array_map(function ($line) {
            foreach (CommentType::COMMENT_PREFIXES as $prefix) {
                if (str_starts_with($line, $prefix)) {
                    return Str::after($line, $prefix);
                }
            }

            throw new RuntimeException('Not all lines have a prefix');
        }, $candidateLines);

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

        // Method DocBlocks don't contain cascading comments.
        if ($this->isDocBlock()) {
            return false;
        }

        return true;
    }

    private function isDocBlock()
    {
        $linesStartingWithAt = array_filter($this->lines, fn ($line) => str_starts_with($line, '@'));

        // If every line of this comment starts with an "@", then this is a "@param" list inside a DocBlock.
        if (count($linesStartingWithAt) === count($this->lines)) {
            return true;
        }

        $index = $this->startsAtLineNumber + count($this->lines);

        while (true) {
            $line = trim($this->allLines[$index++] ?? '');

            if (! str_starts_with($line, '*')) {
                return false;
            }

            if (str_contains($line, '* @return')) {
                return true;
            }
        }
    }

    public function toString()
    {
        return implode("\n", $this->lines);
    }
}
