<?php

namespace App\Lcc;

use App\Lcc\Enums\CommentType;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ReleaseFile
{
    /** @var CascadingComment[] */
    public readonly array $comments;

    public function __construct(public $filePath, public int $zipIndex, $contents)
    {
        $allLines = explode("\n", $contents);

        $this->comments = collect($allLines)
            ->mapToGroups(function ($line, $lineNumber) {
                static $group = 1;

                return Str::startsWith($trimmed = trim($line), CommentType::COMMENT_PREFIXES)
                    ? [$group => [$lineNumber => $trimmed]]
                    : [0 => $group++];
            })
            ->forget(0)
            ->map->mapWithKeys(fn (array $group) => $group) // Flatten but keep the array keys
            ->mapWithKeys(fn (Collection $group) => [$group->keys()->first() => $group->values()])
            ->filter(function (Collection $candidateLines, $startsAtLineNumber) use ($allLines) {
                return $this->isCascadingComment($candidateLines, $startsAtLineNumber, $allLines);
            })
            ->map(function (Collection $candidateLines, $startsAtLineNumber) use ($filePath) {
                [$type, $prefix] = CommentType::fromLine($candidateLines[0]);

                return new CascadingComment(
                    $candidateLines->map(fn ($line) => Str::after($line, $prefix))->toArray(),
                    $type,
                    $startsAtLineNumber,
                    $filePath,
                );
            })
            ->values()
            ->toArray();
    }

    private function isCascadingComment(Collection $candidateLines, int $startsAtLineNumber, array $allLines): bool
    {
        if ($candidateLines->count() < 3 || $candidateLines->count() > 5) {
            return false;
        }

        $lineLengthsAreWayOff = $candidateLines
            ->map(fn ($line) => mb_strlen($line))
            ->sliding()
            ->mapSpread(fn ($previousLineLength, $nextLineLength) => abs($nextLineLength - $previousLineLength))
            ->contains(fn ($lengthDiff) => $lengthDiff > 8);

        if ($lineLengthsAreWayOff) {
            return false;
        }

        // Check if our candidate is a "@param" list inside a DocBlock.
        if ($candidateLines->every(fn ($line) => str_starts_with($line, '* @'))) {
            return false;
        }

        $isSomewhereInDocBlock = collect($allLines)
            ->skip($candidateLines->count() + $startsAtLineNumber)
            ->map(fn ($line) => ltrim($line))
            ->takeWhile(fn ($line) => str_starts_with($line, '*'))
            ->contains(fn ($line) => str_starts_with($line, '* @return'));

        if ($isSomewhereInDocBlock) {
            return false;
        }

        return true;
    }
}
