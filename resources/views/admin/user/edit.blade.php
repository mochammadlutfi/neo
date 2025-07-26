<x-app-layout>
    @push('styles')
    @endpush

    <div class="content">
        <form method="POST" action="{{ route('admin.user.update', $data->id) }}">
            @csrf
            <div class="content-heading d-flex justify-content-between align-items-center">
                <span>Ubah Konsumen</span>
                <div class="space-x-1">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-check me-1"></i>
                        Simpan
                    </button>
                </div>
            </div>
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

