<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>SIES SMP MUHAMMADIYAH 3</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/sass/main.scss', 'resources/js/codebase/app.js'])
    </head>
    <body>
        <div id="page-container" class="main-content-boxed">

            
            <!-- Main Container -->
            <main id="main-container">
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
