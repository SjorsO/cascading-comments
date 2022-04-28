@props(['title'])
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="canonical" href="{{ URL::current() }}" />
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}" />

    <title>{{ $title }}</title>

    <style>
        [x-cloak] { display: none !important; }
    </style>

    @stack('head')

    <link rel="stylesheet" type="text/css" href="{{ mix('css/app.css') }}"/>

    <script type="text/javascript" src="{{ mix('js/app.js') }}" defer></script>
    @livewireStyles
</head>

<body>

    <div class="flex items-center justify-between bg-black px-4 py-1 mb-2 text-white">
        <a class="flex items-center items-end text-sm sm:text-lg" href="/">
            <img src="{{ asset('favicon.png') }}" width="36" height="36" alt="Cascading Comments logo" class="mr-2">
            Cascading Comments
        </a>

        <a class="flex items-center text-sm" href="https://github.com/sjorso/cascading-comments" rel="nofollow">
            GitHub
            <x-svg.heroicons.solid.external-link class="w-4 h-4 ml-1"/>
        </a>
    </div>

    <div class="max-w-4xl w-full mx-auto px-4 py-4">
        {{ $slot }}
    </div>

    @livewireScripts

</body>


</html>
