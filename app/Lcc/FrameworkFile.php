<?php

namespace App\Lcc;

use Illuminate\Support\Str;

class FrameworkFile
{
    /** @var CascadingComment[] */
    private $comments = [];

    public function __construct($contents)
    {
        $lines = array_map(function ($line) {
            $line = trim($line);

            return Str::startsWith($line, CascadingCommentCandidate::COMMENT_PREFIXES) ? $line : null;
        }, explode("\n", $contents));

        $chunk = [];

        foreach ($lines as $line) {
            if (! $chunk && ! $line) {
                continue;
            }

            if ($chunk && ! $line) {
                $candidate = new CascadingCommentCandidate($chunk);

                if ($candidate->isActuallyACascadingComment) {
                    $this->comments[] = new CascadingComment(
                        $candidate->toString(),
                    );
                }

                $chunk = [];

                continue;
            }

            if ($line) {
                $chunk[] = $line;
            }
        }
    }

    public function comments()
    {
        return $this->comments;
    }
}
