<?php

namespace App\Lcc;

use Illuminate\Support\Str;

class ReleaseFile
{
    /** @var CascadingComment[] */
    private $comments = [];

    public function __construct(public $filePath, public int $zipIndex, $contents)
    {
        $lines = array_map(function ($line) {
            $line = trim($line);

            return Str::startsWith($line, CascadingCommentCandidate::COMMENT_PREFIXES) ? $line : null;
        }, explode("\n", $contents));

        $chunk = [];

        $chunkStartsAtLineNumber = null;

        foreach ($lines as $lineNumber => $line) {
            if (! $chunk && ! $line) {
                continue;
            }

            if ($chunk && ! $line) {
                $candidate = new CascadingCommentCandidate($chunk);

                if ($candidate->isActuallyACascadingComment) {
                    $this->comments[] = new CascadingComment(
                        $candidate->toString(),
                        $chunkStartsAtLineNumber
                    );
                }

                $chunk = [];

                $chunkStartsAtLineNumber = null;

                continue;
            }

            if ($line) {
                if ($chunkStartsAtLineNumber === null) {
                    $chunkStartsAtLineNumber = $lineNumber;
                }

                $chunk[] = $line;
            }
        }
    }

    public function comments()
    {
        return $this->comments;
    }
}
