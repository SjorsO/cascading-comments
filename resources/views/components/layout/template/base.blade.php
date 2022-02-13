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

    <div class="container mx-auto px-2">
        {{ $slot }}
    </div>

    @livewireScripts

</body>


</html>
