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
                            <x-input-field type="password" id="old_password" name="old_password" label="Password Lama" :required="true"/>
                            <x-input-field type="password" id="password" name="password" label="Password Baru" :required="true"/>
                            <x-input-field type="password" id="password_confirmation" name="password_confirmation" label="Konfirmasi Password Baru" :required="true"/>
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
    @endpush
</x-app-layout>

