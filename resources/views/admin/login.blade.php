<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1.0">

        <title>Login Admin - Infotech Global Indonesia</title>

        <meta name="description" content="Infotech Global Indonesia">
        <meta name="robots" content="noindex, nofollow">
        <!-- Stylesheets -->
        <!-- Codebase framework -->
        <link rel="stylesheet" id="css-main" href="/css/codebase.min.css">
        @vite(['resources/sass/main.scss', 'resources/js/codebase/app.js',
        'resources/js/app.js'])
    </head>

    <body>
        <div id="page-container" class="main-content-boxed">

            <!-- Main Container -->
            <main id="main-container">
                <!-- Page Content -->
                    <div class="row mx-0 justify-content-center">
                        <div class="hero-static col-lg-6 col-xl-5">
                            <div class="content content-full overflow-hidden">
                                <!-- Header -->
                                <!-- END Header -->

                                <div class="block block-rounded mt-6">
                                    <div class="block-header bg-gd-main">
                                        <h3 class="block-title text-white">
                                            Login Sekarang
                                        </h3>
                                    </div>
                                    <div class="block-content">
                                        <h1 class="h4 fw-bold mt-0 mb-2"></h1>
                                        <form method="POST" action="{{ route('admin.login') }}">
                                            @csrf
                                            <x-input-field type="text" name="username" id="username" label="Username"/>                                            
                                            <x-input-field type="password" name="password" id="password" label="Password"/>
                                            <div class="mb-4">
                                            <button type="submit" class="btn btn-lg btn-gd-main fw-medium w-100">
                                                Login Sekarang
                                            </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <!-- END Sign Up Form -->
                            </div>
                        </div>
                    </div>
                <!-- END Page Content -->
            </main>
            <!-- END Main Container -->
        </div>
        <!-- END Page Container -->
    </body>
</html>