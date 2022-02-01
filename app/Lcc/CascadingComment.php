<?php

namespace App\Lcc;

class CascadingComment
{
    public readonly bool $is_perfect;

    public function __construct($string)
    {
        $this->is_perfect = $this->isPerfect($string);
    }

    private function isPerfect($string)
    {
        $lines = explode("\n", $string);

        // This is not actually a rule, just a sanity check.
        throw_if(count($lines) !== 3 && count($lines) !== 4, 'Invalid cascading comment length, expected 3 or 4 lines, actual: '.count($lines));

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
}
