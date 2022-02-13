<?php

namespace App\Http\Livewire;

use App\Models\Comment;
use Livewire\Component;

class CommentView extends Component
{
    public Comment $comment;

    protected $listeners = ['selectedCommentChanged'];

    public function mount(Comment $comment)
    {
        $this->comment = $comment;
    }

    public function selectedCommentChanged(Comment $comment)
    {
        $this->comment = $comment;
    }

    public function render()
    {
        return view('livewire.comment-view');
    }
}
