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

    @if($comment)
        <div class="border rounded my-4 text-sm">
            <div class="border-b bg-gray-100 px-2 py-1">
                <div class="flex justify-between items-center">
                    <a class="text-blue-500 hover:underline" href="{{ $comment->github_permalink }}" target="_blank" rel="nofollow">
                        {{ $comment->file_path }} at line {{ $comment->starts_at_line_number + 1 }}
                    </a>

                    @if($comment->is_perfect)
                        <div class="flex items-center text-sm">
                            Perfect
                            <x-svg.heroicons.solid.badge-check class="ml-1 w-5 h-5 text-green-500"/>
                        </div>
                    @endif
                </div>
            </div>

            <div class="px-2 py-4 font-mono overflow-x-scroll whitespace-nowrap">
                @foreach(explode("\n", $comment->text) as $i => $line)
                    <div>{{ $line }}</div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="mb-32"></div>

</div>
