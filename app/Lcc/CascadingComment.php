<?php

namespace App\Lcc;

use App\Lcc\Enums\CommentType;
use LogicException;

class CascadingComment
{
    public bool $isPerfect;

    public array $lines;

    public int $numberOfLines;

    public function __construct($lines, public CommentType $type, public int $startsAtLineNumber, public $filePath)
    {
        $this->lines = is_array($lines) ? $lines : explode("\n", $lines);

        $this->numberOfLines = count($this->lines);

        // This is not actually a rule, just a sanity check.
        if ($this->numberOfLines !== 3 && $this->numberOfLines !== 4) {
            throw new LogicException(
                sprintf(
                    "Invalid cascading comment length, expected 3 or 4 lines, actual: %s\n\nFile: %s\n\n%s",
                    $this->numberOfLines,
                    $this->filePath,
                    implode("\n", $this->lines)
                )
            );
        }

        $this->isPerfect = $this->isPerfect();
    }

    private function isPerfect()
    {
        $lines = $this->lines;

        $previousLineLength = mb_strlen(
            array_shift($lines)
        );

        $lastLine = array_pop($lines);

        foreach ($lines as $line) {
            $currentLineLength = mb_strlen($line);

            if ($previousLineLength - 3 !== $currentLineLength) {
                return false;
            }

            $previousLineLength = $currentLineLength;
        }

        if ($previousLineLength - 2 !== mb_strlen($lastLine)) {
            return false;
        }

        $lastCharacter = mb_substr($lastLine, -1);

        return in_array($lastCharacter, ['.', '!']);
    }

    public function toString()
    {
        return implode("\n", $this->lines);
    }
}
