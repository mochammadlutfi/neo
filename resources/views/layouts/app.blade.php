<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1"> 
        <meta name="csrf-token" content="{{ csrf_token() }}">


        <title>Admin | NEO Agency & Adevertising</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="/js/plugins/sweetalert2/sweetalert2.min.css">
        <link rel="stylesheet" href="/js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css">
        <link rel="stylesheet" href="/js/plugins/datatables-buttons-bs5/css/buttons.bootstrap5.min.css">
        <link rel="stylesheet" href="/js/plugins/datatables-responsive-bs5/css/responsive.bootstrap5.min.css">
        <link rel="stylesheet" href="/js/plugins/select2/css/select2.min.css">
        <link rel="stylesheet" href="/js/plugins/flatpickr/flatpickr.min.css">

        @stack('styles')
        <!-- Scripts -->
        @vite(['resources/sass/main.scss', 'resources/js/codebase/app.js', 'resources/js/app.js'])
    </head>
    <body>
        <div id="page-container" class="sidebar-o enable-page-overlay side-scroll main-content-boxed side-trans-enabled">
            @include('layouts.sidebar')

            @include('layouts.header')
            <!-- Page Content -->
            <main id="main-container">
                {{ $slot }}
            </main>
        </div>
        <script src="/js/jquery.min.js"></script>
        <script src="/js/plugins/sweetalert2/sweetalert2.min.js"></script>
        <script src="/js/plugins/select2/js/select2.full.min.js"></script>
        <script src="/js/plugins/flatpickr/flatpickr.min.js"></script>
        <script src="/js/plugins/flatpickr/l10n/id.js"></script>
        <script src="/js/plugins/datatables/jquery.dataTables.min.js"></script>
        <script src="/js/plugins/datatables-bs5/js/dataTables.bootstrap5.min.js"></script>
        <script src="/js/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
        <script src="/js/plugins/datatables-responsive-bs5/js/responsive.bootstrap5.min.js"></script>
        <script src="/js/plugins/datatables-buttons/dataTables.buttons.min.js"></script>
        <script src="/js/plugins/datatables-buttons-bs5/js/buttons.bootstrap5.min.js"></script>
        <script src="/js/plugins/datatables-buttons-jszip/jszip.min.js"></script>
        <script src="/js/plugins/datatables-buttons-pdfmake/pdfmake.min.js"></script>
        <script src="/js/plugins/datatables-buttons-pdfmake/vfs_fonts.js"></script>
        <script src="/js/plugins/datatables-buttons/buttons.print.min.js"></script>
        <script src="/js/plugins/datatables-buttons/buttons.html5.min.js"></script>
        <script type="text/javascript">
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        </script>
        @stack('scripts')
    </body>
</html>
