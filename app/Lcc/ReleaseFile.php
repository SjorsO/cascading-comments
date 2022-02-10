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
            ->map->mapWithKeys(fn ($group) => $group) // Flatten but keep the array keys
            ->map(function (Collection $candidateLines) use ($allLines) {
                return new CascadingCommentCandidate(
                    $candidateLines->values()->toArray(),
                    $candidateLines->keys()->first(),
                    $allLines,
                );
            })
            ->filter(fn (CascadingCommentCandidate $candidate) => $candidate->isActuallyACascadingComment)
            ->map(function (CascadingCommentCandidate $candidate) {
                return new CascadingComment(
                    $candidate->toString(),
                    $candidate->type,
                    $candidate->startsAtLineNumber,
                );
            })
            ->values()
            ->toArray();
    }
}
