<x-app-layout>
    @push('styles')
    @endpush

    <div class="content">
        <!-- Page Header -->
        <div class="block block-rounded">
            <div class="block-header">
                <h3 class="block-title fs-base fw-bold">
                    <i class="fa fa-user-edit me-2 text-primary"></i>
                    Ubah Konsumen: {{ $data->nama }}
                </h3>
                <div class="block-options">
                    <a href="{{ route('admin.user.index') }}" class="btn btn-sm btn-secondary fs-base me-2">
                        <i class="fa fa-arrow-left me-1"></i>
                        Kembali
                    </a>
                    <button type="submit" form="userEditForm" class="btn btn-sm btn-primary fs-base">
                        <i class="fa fa-save me-1"></i>
                        Simpan
                    </button>
                </div>
            </div>
        </div>
        
        <form method="POST" action="{{ route('admin.user.update', $data->id) }}" id="userEditForm">
            @csrf
            <div class="block block-rounded">
                <div class="block-content">
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
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="/js/plugins/flatpickr/flatpickr.min.js"></script>
    <script src="/js/plugins/flatpickr/l10n/id.js"></script>
    <script src="/js/plugins/ckeditor5-classic/build/ckeditor.js"></script>
    <script>
        
        $("#field-tgl_lahir").flatpickr({
            altInput: true,
            altFormat: "d M Y",
            dateFormat: "Y-m-d",
            locale : "id",
        });
    </script>
    @endpush
</x-app-layout>

