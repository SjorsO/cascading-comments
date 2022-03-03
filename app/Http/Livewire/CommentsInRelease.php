<?php

namespace App\Http\Livewire;

use App\Models\Release;
use Livewire\Component;

class CommentsInRelease extends Component
{
    public Release $release;

    public $index = 1;

    public $commentsCount;

    public $hasImperfectComments;

    protected $listeners = ['setRelease'];

    public function mount(Release $release)
    {
        $this->setRelease($release);
    }

    public function setRelease(Release $release)
    {
        $this->release = $release;

        $this->commentsCount = $release->comments()->count();

        $this->hasImperfectComments = $release->comments()->where('is_perfect', false)->exists();

        $this->index = 1;
    }

    public function previous()
    {
        $this->index = $this->index === 1
            ? $this->commentsCount
            : $this->index - 1;
    }

    public function next()
    {
        $this->index = $this->index > ($this->commentsCount - 1)
            ? 1
            : $this->index + 1;
    }

    public function random()
    {
        do {
            $newIndex = mt_rand(1, $this->commentsCount);
        } while ($newIndex === $this->index || $this->commentsCount === 1);

        $this->index = $newIndex;
    }

    public function nextImperfect()
    {
        if (! $this->hasImperfectComments) {
            return;
        }

        $nextImperfectIndex = $this->release->comments()
            ->where('index', '>', $this->index)
            ->where('is_perfect', false)
            ->first()?->index;

        if (! $nextImperfectIndex) {
            $nextImperfectIndex = $this->release->comments()
                ->where('is_perfect', false)
                ->first()?->index;
        }

        $this->index = $nextImperfectIndex;
    }

    public function render()
    {
        return view('livewire.comments-in-release', [
            'comment' => $this->release->comments()->firstWhere('index', $this->index),
        ]);
    }
}
