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
                                            <div class="mb-4">
                                                <label class="form-label" for="password">Password</label>
                                                <div class="input-group">
                                                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Masukkan Password">
                                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                                        <i class="fa fa-eye" id="eyeIcon"></i>
                                                    </button>
                                                </div>
                                                @error('password')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
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
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const togglePassword = document.getElementById('togglePassword');
                const passwordField = document.getElementById('password');
                const eyeIcon = document.getElementById('eyeIcon');

                togglePassword.addEventListener('click', function() {
                    const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordField.setAttribute('type', type);
                    
                    // Toggle eye icon
                    if (type === 'password') {
                        eyeIcon.classList.remove('fa-eye-slash');
                        eyeIcon.classList.add('fa-eye');
                    } else {
                        eyeIcon.classList.remove('fa-eye');
                        eyeIcon.classList.add('fa-eye-slash');
                    }
                });
            });
        </script>
    </body>
</html>