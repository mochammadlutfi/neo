<x-app-layout>
    @push('styles')
    @endpush

    <div class="content">
        <!-- Page Header -->
        <div class="block block-rounded">
            <div class="block-header">
                <h3 class="block-title fs-base fw-bold">
                    <i class="fa fa-user-edit me-2 text-primary"></i>
                    Ubah Staff: {{ $data->nama }}
                </h3>
                <div class="block-options">
                    <a href="{{ route('admin.staff.index') }}" class="btn btn-sm btn-secondary fs-base me-2">
                        <i class="fa fa-arrow-left me-1"></i>
                        Kembali
                    </a>
                    <button type="submit" form="staffEditForm" class="btn btn-sm btn-primary fs-base">
                        <i class="fa fa-save me-1"></i>
                        Simpan
                    </button>
                </div>
            </div>
        </div>
        
        <form method="POST" action="{{ route('admin.staff.update', $data->id) }}" id="staffEditForm">
            @csrf
            <div class="block block-rounded">
                <div class="block-content">
                    <div class="row mb-4">
                        <label class="col-sm-3 col-form-label" for="field-nama">Nama Lengkap</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control {{ $errors->has('nama') ? 'is-invalid' : '' }}"
                                id="field-nama" name="nama" placeholder="Masukan Nama Lengkap"
                                value="{{ old('nama', $data->nama) }}">
                            <x-input-error :messages="$errors->get('nama')" class="mt-2" />
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-sm-3 col-form-label" for="field-username">Username</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control {{ $errors->has('username') ? 'is-invalid' : '' }}"
                                id="field-username" name="username" placeholder="Masukan Username"
                                value="{{ old('username', $data->username) }}">
                            <x-input-error :messages="$errors->get('username')" class="mt-2" />
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-sm-3 col-form-label" for="field-level">Jabatan</label>
                        <div class="col-sm-6">
                            <select class="form-select {{ $errors->has('level') ? 'is-invalid' : '' }}"
                                id="field-level" style="width: 100%;" name="level">
                                <option value="">Pilih</option>
                                <option value="Marketing" {{ old('level', $data->level)  == 'Marketing' ? 'selected' : '' }}>Marketing</option>
                                <option value="Content Planner" {{ old('level', $data->level)  == 'Content Planner' ? 'selected' : '' }}>Content Planner</option>
                                <option value="Manger" {{ old('level', $data->level)  == 'Manger' ? 'selected' : '' }}>Manager</option>
                            </select>
                            <x-input-error :messages="$errors->get('level')" class="mt-2" />
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

