<x-layout.template.base title="Cascading Comments">

    <h1 class="text-xl mb-4">An index of Laravel's cascading comments</h1>

    <div class="grid grid-cols-3 gap-4">

        @foreach($repositories as $repository)
            <div class="border rounded px-2 py-1">
                <h2 class="text-lg text-center">{{ $repository->display_name }}</h2>
            </div>
        @endforeach

    </div>

</x-layout.template.base>
