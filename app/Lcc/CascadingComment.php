<?php

namespace App\Lcc;

class CascadingComment
{
    public readonly bool $is_perfect;

    public readonly array $lines;

    public readonly int $lines_count;

    public function __construct($string)
    {
        $this->lines = explode("\n", $string);

        $this->lines_count = count($this->lines);

        // This is not actually a rule, just a sanity check.
        throw_if($this->lines_count !== 3 && $this->lines_count !== 4, 'Invalid cascading comment length, expected 3 or 4 lines, actual: '.$this->lines_count);

        $this->is_perfect = $this->isPerfect();
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
