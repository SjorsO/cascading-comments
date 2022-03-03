<?php /** @var \App\Models\Comment $comment */ ?>

<div>

    <div class="flex items-center space-x-4">
        <div class="px-2 border rounded cursor-pointer select-none" wire:click="previous" wire:loading.attr="disabled">&laquo;</div>

        <div>{{ $index }} / {{ $this->commentsCount }}</div>

        <div class="px-2 border rounded cursor-pointer select-none" wire:click="next" wire:loading.attr="disabled">&raquo;</div>

        <div class="px-2 border rounded cursor-pointer select-none" wire:click="random" wire:loading.attr="disabled">Random</div>

        @if($hasImperfectComments)
            <div class="px-2 border rounded cursor-pointer select-none" wire:click="nextImperfect" wire:loading.attr="disabled">Next imperfect</div>
        @endif
    </div>

    <div class="flex justify-between mt-4">
        <a href="{{ $comment->github_permalink }}" class="text-blue-500" target="_blank" rel="nofollow">
            {{ $comment->file_path }} at line {{ $comment->starts_at_line_number + 1 }}
        </a>

        @if($comment->is_perfect)
            <div class="px-2 py-1 bg-green-500 text-white rounded text-sm">Perfect</div>
        @endif
    </div>

    <pre class="mt-4">{{ $comment->text }}</pre>

</div>
