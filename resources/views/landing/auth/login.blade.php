<x-landing-layout>

    <div class="content content-full">
        <div class="row justify-content-center">
            <div class="col-md-6">
                
        <!-- Header -->
        <div class="py-4 text-center">
            <h1 class="h3 fw-bold mt-4 mb-2">Masuk</h1>
            <h4 class="h5 fw-medium mt-4 mb-2">Belum Punya Akun ?
                <a href="{{ route('register') }}">Daftar</a>
            </h4>
        </div>
        <!-- END Header -->

        <div class="block block-rounded">
            <div class="block-content p-4">
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label" for="val-email">Alamat Email
                            <span class="text-danger">*</span>
                        </label>
                        <input
                            type="text"
                            class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                            id="val-email"
                            name="email"
                            placeholder="Masukan Email">
                        <x-input-error :messages="$errors->get('email')" class="mt-2"/>
                    </div>
                    <div class="mb-4">
                        <label class="form-label" for="val-password">Password
                            <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <input
                                type="password"
                                class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                                id="val-password"
                                name="password"
                                placeholder="Masukan password">
                            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                <i class="fa fa-eye" id="eyeIcon"></i>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2"/>
                    </div>
                    <button type="submit" class="btn btn-lg btn-gd-main rounded-pill fw-medium w-100">
                        Login Sekarang
                    </button>
                </form>
            </div>
        </div>
        <!-- END Sign Up Form -->
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.getElementById('togglePassword');
            const passwordField = document.getElementById('val-password');
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
    @endpush
</x-landing-layout>