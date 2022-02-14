<x-layout.template.base title="Cascading Comments">

    <div class="flex items-center mb-4">
        <img src="{{ $repository->logo_url }}" alt="Logo for the {{ $repository->display_name }} repository" class="h-12" height="48">

        <x-h1 class="ml-4">Cascading comments of {{ $repository->display_name }}</x-h1>
    </div>

</x-layout.template.base>
