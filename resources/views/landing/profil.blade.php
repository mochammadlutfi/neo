<x-user-layout>
    <div class="block rounded">
        <div class="block-header border-bottom border-3">
            <h3 class="block-title fw-semibold">Profil Saya</h3>
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

            <form method="POST" action="{{ route('user.profile.edit') }}">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <x-input-field type="text" id="nama" name="nama" label="Nama Lengkap" value="{{ $data->nama }}"/>
                    </div>
                    <div class="col-md-6">
                        <x-input-field type="text" id="perusahaan" name="perusahaan" label="Nama Perusahaan" value="{{ $data->perusahaan }}"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <x-input-field type="email" id="email" name="email" label="Alamat Email" value="{{ $data->email }}"/>
                    </div>
                    <div class="col-md-6">
                        <x-input-field type="text" id="hp" name="hp" label="No Handphone" value="{{ $data->hp }}"/>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">
                    Simpan
                </button>
            </form>
        </div>
    </div>
</x-user-layout>