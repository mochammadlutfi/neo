<x-app-layout>
    @push('styles')
    @endpush

    <div class="content">
        <!-- Page Header -->
        <div class="block block-rounded">
            <div class="block-header">
                <h3 class="block-title fs-base fw-bold">
                    <i class="fa fa-key me-2 text-primary"></i>
                    Ubah Password
                </h3>
            </div>
        </div>
        <div class="block block-rounded">
            <div class="block-content p-4">
                <form method="POST" action="{{ route('admin.password.update') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="form-label" for="field-old_password">Password Lama <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" class="form-control @error('old_password') is-invalid @enderror" id="field-old_password" name="old_password" placeholder="Masukkan Password Lama">
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('field-old_password', this)">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                </div>
                                @error('old_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label" for="field-password">Password Baru <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="field-password" name="password" placeholder="Masukkan Password Baru">
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('field-password', this)">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label" for="field-password_confirmation">Konfirmasi Password Baru <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" id="field-password_confirmation" name="password_confirmation" placeholder="Konfirmasi Password Baru">
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('field-password_confirmation', this)">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                </div>
                                @error('password_confirmation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-check me-1"></i>
                        Simpan
                    </button>
                </form>
            </div>
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
</x-app-layout>

