<?php /** @var \App\Models\Repository $repository */ ?>

<x-layout.template.base title="Cascading Comments">

    <x-h1 class="mb-6">An index of Laravel's cascading comments</x-h1>

    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">

        @foreach($repositories as $repository)
            <a class="relative border rounded px-2 py-3 text-center hover:ring" href="{{ route('repositories.show', [$repository->owner, $repository->name]) }}">

                <img src="{{ $repository->logo_url }}" alt="Logo for the {{ $repository->display_name }} repository" class="absolute left-2 top-2 h-10" height="40">

                <h2 class="text-lg font-medium">{{ $repository->display_name }}</h2>

                <p class="text-sm">
                    Latest release: {{ $repository->latestRelease->name }}
                </p>
                <p class="mt-2">
                    @if($repository->latestRelease->comments_count > 0)
                        Perfect cascading comments:
                        <br>
                        {{ $repository->latestRelease->perfect_comments_count }} / {{ $repository->latestRelease->comments_count }} ({{ $repository->latestRelease->perfect_comment_percentage }}%)
                    @else
                        This release has no cascading comments.
                    @endif
                </p>
            </a>
        @endforeach
    </div>

    <div class="mt-8">
        <x-h2>What is a cascading comment?</x-h2>
        <p>
            A cascading comment is a 3 or 4 lines comment, where each line is shorter than the last.
            For example:
        </p>

        <div class="border rounded my-4 text-sm">
            <div class="border-b bg-gray-100 px-2 py-1">
                <a class="text-blue-500 hover:underline" href="https://github.com/laravel/laravel/blob/d0603437cbbb478586979a3792d49e0d157ce554/artisan#L26-L30">laravel/laravel - artisan</a>
                <br>
                Lines 27 to 29
            </div>

            <div class="px-2 py-1 font-mono overflow-x-scroll whitespace-nowrap">
                <div class="flex">
                    <div class="flex-shrink-0 w-8">26</div>
                    <div>|</div>
                </div>
                <div class="flex">
                    <div class="flex-shrink-0 w-8">27</div>
                    <div>| When we run the console application, the current CLI command will be </div>
                </div>
                <div class="flex">
                    <div class="flex-shrink-0 w-8">28</div>
                    <div>| executed in this console and the response sent back to a terminal </div>
                </div>
                <div class="flex">
                    <div class="flex-shrink-0 w-8">29</div>
                    <div>| or another output device for the developers. Here goes nothing! </div>
                </div>
                <div class="flex">
                    <div class="flex-shrink-0 w-8">30</div>
                    <div>| </div>
                </div>
            </div>
        </div>

        <p>
            Each line is three characters shorter than the previous line.
            The last line is two characters shorter and ends with punctuation.
            If a comment follows these rules exactly, it is considered a "perfect" cascading comment.
            <br>
            <br>
            These types of comments are everywhere in Laravel's source code.
            The skeleton, framework, and most first-party packages have them.
            This project finds and indexes all of these comments per Laravel release.
        </p>

    </div>

    <div class="mb-24"></div>

</x-layout.template.base>
