<?php /** @var \App\Models\Repository $repository */ ?>

<x-layout.template.base title="Cascading Comments">

    <h1 class="text-xl mb-4">An index of Laravel's cascading comments</h1>

    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">

        @foreach($repositories as $repository)
            <div class="border rounded px-2 py-3 text-center hover:ring">

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
            </div>
        @endforeach

    </div>

</x-layout.template.base>
