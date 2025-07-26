<x-app-layout>
    @push('styles')
    @endpush

    <div class="content">
        <form method="POST" action="{{ route('admin.staff.store') }}">
            @csrf
            <div class="content-heading d-flex justify-content-between align-items-center">
                <span>Tambah Staff Baru</span>
                <div class="space-x-1">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-check me-1"></i>
                        Simpan
                    </button>
                </div>
            </div>
            <div class="block block-rounded">
                <div class="block-content">
                    <div class="row mb-4">
                        <label class="col-sm-3 col-form-label" for="field-nama">Nama Lengkap</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control {{ $errors->has('nama') ? 'is-invalid' : '' }}"
                                id="field-nama" name="nama" placeholder="Masukan Nama Lengkap"
                                value="{{ old('nama') }}">
                            <x-input-error :messages="$errors->get('nama')" class="mt-2" />
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-sm-3 col-form-label" for="field-username">Username</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control {{ $errors->has('username') ? 'is-invalid' : '' }}"
                                id="field-username" name="username" placeholder="Masukan Username"
                                value="{{ old('username') }}">
                            <x-input-error :messages="$errors->get('username')" class="mt-2" />
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-sm-3 col-form-label" for="field-level">Jabatan</label>
                        <div class="col-sm-6">
                            <select class="form-select {{ $errors->has('level') ? 'is-invalid' : '' }}"
                                id="field-level" style="width: 100%;" name="level">
                                <option value="">Pilih</option>
                                <option value="Marketing" {{ old('jk' == 'Marketing') ? 'selected' : '' }}>Marketing</option>
                                <option value="Content Planner" {{ old('jk' == 'Content Planner') ? 'selected' : '' }}>Content Planner</option>
                                <option value="Manger" {{ old('jk' == 'Manger') ? 'selected' : '' }}>Manager</option>
                            </select>
                            <x-input-error :messages="$errors->get('level')" class="mt-2" />
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-sm-3 col-form-label" for="field-password">Password</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                                id="field-password" name="password" placeholder="Masukan Password"
                                value="{{ old('password') }}">
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
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

