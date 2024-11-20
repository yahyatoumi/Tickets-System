<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        {{-- @vite('resources/css/app.css')
        @vite('resources/js/app.js') --}}
        @vite(['resources/css/app.css', 'resources/js/app.js'])

            {{-- <script src="{{ mix('js/app.js') }}" type="module"></script>
            <script src="{{ mix('css/app.css') }}" type="module"></script> --}}
        @inertiaHead
    </head>
    <body>
        @inertia
        {{-- <div id="app"></div> --}}
    </body>
</html>

{{-- <script>
    window.Ziggy = @json($ziggy);
</script> --}}
