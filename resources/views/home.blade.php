<?php /** @var \App\Models\Repository $repository */ ?>

<x-layout.template.base title="Cascading Comments">

    <x-h1 class="mb-4">An index of Laravel's cascading comments</x-h1>

    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">

        @foreach($repositories as $repository)
            <a class="border rounded px-2 py-3 text-center hover:ring" href="{{ route('repositories.show', [$repository->owner, $repository->name]) }}">

                <img src="{{ $repository->logo_url }}" alt="Logo for the {{ $repository->display_name }} repository" class="mx-auto h-12 mb-2" height="48">

                <h2 class="text-lg font-medium">{{ $repository->display_name }}</h2>

                <p>
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

</x-layout.template.base>
