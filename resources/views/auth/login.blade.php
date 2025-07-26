<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />


    <div class="bg-image" style="background-image: url('/images/depan.jpeg');">
        <div class="row mx-0 bg-black-50">
            <div class="hero-static col-md-6 col-xl-8 d-none d-md-flex align-items-md-end">
            </div>
            <div class="hero-static col-md-6 col-xl-4 d-flex align-items-center bg-body-extra-light">
                <div class="content content-full">
                    <!-- Header -->
                    <div class="px-4 py-2 mb-4">
                        <a href="{{ route('login') }}">
                            <img src="/images/logo.jpg" class="w-50" />
                        </a>
                        <h1 class="h3 fw-bold mt-4 mb-2">Selamat Datang!</h1>
                        <h2 class="h5 fw-medium text-muted mb-0">Silahakan Login Untuk Melanjutkan</h2>
                    </div>
                    <!-- END Header -->

                    <!-- Sign In Form -->
                    <div class="px-4">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="mb-4">
                                <label class="form-label" for="val-email">Username
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" id="val-email" name="email" 
                                placeholder="Masukan Username" value="{{ old('email') }}">
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>
                            <div class="mb-4">
                                <label class="form-label" for="val-password">Password
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="password" class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" id="val-password" name="password" placeholder="Masukan password">
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>
                            <div class="row">
                                <div class="col-sm-6 d-sm-flex align-items-center push">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" id="login-remember-me"
                                            name="login-remember-me">
                                        <label class="form-check-label" for="login-remember-me">Remember Me</label>
                                    </div>
                                </div>
                                <div class="col-sm-6 text-sm-end push">
                                    <button type="submit" class="btn btn-lg btn-alt-primary fw-medium">
                                        Login Sekarang
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- END Sign In Form -->
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
