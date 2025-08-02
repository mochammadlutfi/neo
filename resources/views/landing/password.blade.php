<x-user-layout>
    <div class="block rounded">
        <div class="block-header border-bottom border-3">
            <h3 class="block-title fw-semibold">Ubah Password</h3>
            <div class="block-options">

            </div>
        </div>
        <div class="block-content p-4">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fa fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('user.profile.password') }}">
                @csrf
                <div class="mb-4">
                    <label class="form-label" for="field-old_password">Password Lama</label>
                    <div class="input-group">
                        <input type="password" class="form-control @error('old_password') is-invalid @enderror" id="field-old_password" name="old_password" placeholder="Masukkan Password Lama">
                        <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('field-old_password', this)">
                            <i class="fa fa-eye"></i>
                        </button>
                    </div>
                    @error('old_password')
                        <div class="text-danger fs-sm">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label class="form-label" for="field-password">Password Baru</label>
                    <div class="input-group">
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="field-password" name="password" placeholder="Masukkan Password Baru">
                        <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('field-password', this)">
                            <i class="fa fa-eye"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="text-danger fs-sm">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label class="form-label" for="field-password_conf">Konfirmasi Password</label>
                    <div class="input-group">
                        <input type="password" class="form-control @error('password_conf') is-invalid @enderror" id="field-password_conf" name="password_conf" placeholder="Konfirmasi Password Baru">
                        <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('field-password_conf', this)">
                            <i class="fa fa-eye"></i>
                        </button>
                    </div>
                    @error('password_conf')
                        <div class="text-danger fs-sm">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <button type="submit" class="btn btn-primary">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function togglePasswordVisibility(fieldId, button) {
            const passwordField = document.getElementById(fieldId);
            const icon = button.querySelector('i');
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
    @endpush
</x-user-layout>