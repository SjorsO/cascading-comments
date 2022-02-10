<?php

namespace App\Lcc;

use App\Lcc\Enums\CommentType;
use Illuminate\Support\Str;

class ReleaseFile
{
    /** @var CascadingComment[] */
    private $comments = [];

    public function __construct(public $filePath, public int $zipIndex, $contents)
    {
        $lines = explode("\n", $contents);

        $candidateLines = array_map(function ($line) {
            $line = trim($line);

            return Str::startsWith($line, CommentType::COMMENT_PREFIXES) ? $line : null;
        }, $lines);

        $candidateCommentLines = [];

        $candidateStartsAtLineNumber = null;

        foreach ($candidateLines as $lineNumber => $line) {
            if (! $candidateCommentLines && ! $line) {
                continue;
            }

            if ($candidateCommentLines && ! $line) {
                $candidate = new CascadingCommentCandidate(
                    $candidateCommentLines,
                    $candidateStartsAtLineNumber,
                    $lines,
                );

                if ($candidate->isActuallyACascadingComment) {
                    $this->comments[] = new CascadingComment(
                        $candidate->toString(),
                        $candidate->type,
                        $candidate->startsAtLineNumber,
                    );
                }

                $candidateCommentLines = [];

                $candidateStartsAtLineNumber = null;

                continue;
            }

            if ($line) {
                if ($candidateStartsAtLineNumber === null) {
                    $candidateStartsAtLineNumber = $lineNumber;
                }

                $candidateCommentLines[] = $line;
            }
        }
    }

    public function comments()
    {
        return $this->comments;
    }
}
