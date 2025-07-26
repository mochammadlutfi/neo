<x-app-layout>
    @push('styles')
    @endpush

    <div class="content">
        <!-- Page Header -->
        <div class="block block-rounded">
            <div class="block-header">
                <h3 class="block-title fs-base fw-bold">
                    <i class="fa fa-user-edit me-2 text-primary"></i>
                    Ubah Profil
                </h3>
            </div>
        </div>
        <div class="block block-rounded">
            <div class="block-content p-4">
                <form method="POST" action="{{ route('admin.profile.update') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <x-input-field type="text" id="nama" name="nama" value="{{ auth()->guard('admin')->user()->nama }}" label="Nama Lengkap" :required="true"/>
                            </div>
                        <div class="col-md-6">
                            <x-input-field type="text" id="username" name="username" label="Username" value="{{ auth()->guard('admin')->user()->username }}" :required="true"/>
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

