<x-landing-layout>

    <div class="content content-full">
        <div class="row justify-content-center">
            <div class="col-md-6">
                
        <!-- Header -->
        <div class="py-4 text-center">
            <h1 class="h3 fw-bold mt-4 mb-2">Buat Password Baru</h1>
            <h4 class="h5 fw-medium mt-4 mb-2">
                Masukkan password baru untuk akun Anda
            </h4>
        </div>
        <!-- END Header -->

        <div class="block block-rounded">
            <div class="block-content p-4">
                
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('password.store') }}">
                    @csrf
                    
                    <!-- Password Reset Token -->
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">
                    
                    <div class="mb-4">
                        <label class="form-label" for="email">Alamat Email
                            <span class="text-danger">*</span>
                        </label>
                        <input
                            type="email"
                            class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                            id="email"
                            name="email"
                            value="{{ old('email', $request->email) }}"
                            placeholder="Masukan alamat email Anda"
                            required>
                        <x-input-error :messages="$errors->get('email')" class="mt-2"/>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label" for="password">Password Baru
                            <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <input
                                type="password"
                                class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                                id="password"
                                name="password"
                                placeholder="Masukan password baru"
                                required>
                            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                <i class="fa fa-eye" id="eyeIcon"></i>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2"/>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label" for="password_confirmation">Konfirmasi Password
                            <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <input
                                type="password"
                                class="form-control {{ $errors->has('password_confirmation') ? 'is-invalid' : '' }}"
                                id="password_confirmation"
                                name="password_confirmation"
                                placeholder="Ulangi password baru"
                                required>
                            <button class="btn btn-outline-secondary" type="button" id="togglePasswordConfirm">
                                <i class="fa fa-eye" id="eyeIconConfirm"></i>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2"/>
                    </div>
                    
                    <button type="submit" class="btn btn-lg btn-gd-main rounded-pill fw-medium w-100">
                        Reset Password
                    </button>
                </form>
                
                <div class="text-center mt-3">
                    <a href="{{ route('login') }}" class="text-muted">
                        <small><i class="fa fa-arrow-left me-1"></i>Kembali ke Login</small>
                    </a>
                </div>
            </div>
        </div>
        <!-- END Reset Password Form -->
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle for password field
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

            // Toggle for password confirmation field
            const togglePasswordConfirm = document.getElementById('togglePasswordConfirm');
            const passwordConfirmField = document.getElementById('password_confirmation');
            const eyeIconConfirm = document.getElementById('eyeIconConfirm');

            togglePasswordConfirm.addEventListener('click', function() {
                const type = passwordConfirmField.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordConfirmField.setAttribute('type', type);
                
                // Toggle eye icon
                if (type === 'password') {
                    eyeIconConfirm.classList.remove('fa-eye-slash');
                    eyeIconConfirm.classList.add('fa-eye');
                } else {
                    eyeIconConfirm.classList.remove('fa-eye');
                    eyeIconConfirm.classList.add('fa-eye-slash');
                }
            });
        });
    </script>
    @endpush

</x-landing-layout>