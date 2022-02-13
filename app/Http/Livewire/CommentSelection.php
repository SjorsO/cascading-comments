<?php

namespace App\Http\Livewire;

use App\Models\Comment;
use App\Models\Release;
use Livewire\Component;

class CommentSelection extends Component
{
    public Release $release;

    public $comment_id;

    public $listeners = ['selectedReleaseChanged'];

    public function mount(Release $release)
    {
        $this->selectedReleaseChanged($release);
    }

    public function getCommentProperty(): Comment
    {
        return Comment::find($this->comment_id);
    }

    public function updatedCommentId()
    {
        $this->emit('selectedCommentChanged', $this->comment);
    }

    public function selectedReleaseChanged(Release $release)
    {
        $this->release = $release;

        $this->comment_id = $release->comments()->first()->id;

        $this->updatedCommentId();
    }

    public function render()
    {
        return view('livewire.comment-selection', [
            'commentOptions' => $this->release->comments()->pluck('id', 'id'),
        ]);
    }
}
