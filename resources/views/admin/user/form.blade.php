<x-app-layout>
    @push('styles')
    @endpush

    <div class="content">
        <!-- Page Header -->
        <div class="block block-rounded">
            <div class="block-header">
                <h3 class="block-title fs-base fw-bold">
                    <i class="fa fa-user-plus me-2 text-primary"></i>
                    Tambah Konsumen Baru
                </h3>
                <div class="block-options">
                    <a href="{{ route('admin.user.index') }}" class="btn btn-sm btn-secondary fs-base me-2">
                        <i class="fa fa-arrow-left me-1"></i>
                        Kembali
                    </a>
                    <button type="submit" form="userForm" class="btn btn-sm btn-primary fs-base">
                        <i class="fa fa-save me-1"></i>
                        Simpan
                    </button>
                </div>
            </div>
        </div>
        
        <form method="POST" action="{{ route('admin.user.store') }}" id="userForm">
            @csrf
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
                        <label class="col-sm-3 col-form-label" for="field-jk">Jenis Kelamin</label>
                        <div class="col-sm-6">
                            <select class="form-select {{ $errors->has('jk') ? 'is-invalid' : '' }}"
                                id="field-jk" style="width: 100%;" name="jk">
                                <option value="">Pilih</option>
                                <option value="L" {{ old('jk' == 'L') ? 'selected' : '' }}>Laki-Laki</option>
                                <option value="P" {{ old('jk' == 'P') ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            <x-input-error :messages="$errors->get('jk')" class="mt-2" />
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-sm-3 col-form-label" for="field-tmp_lahir">Tempat / Tanggal Lahir</label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control {{ $errors->has('tmp_lahir') ? 'is-invalid' : '' }}"
                                id="field-tmp_lahir" name="tmp_lahir" placeholder="Masukan Tempat Lahir"
                                value="{{ old('tmp_lahir') }}">
                            <x-input-error :messages="$errors->get('tmp_lahir')" class="mt-2" />
                        </div>
                        <div class="col-sm-3">
                            <input type="text" class="form-control {{ $errors->has('tgl_lahir') ? 'is-invalid' : '' }}"
                                id="field-tgl_lahir" name="tgl_lahir" placeholder="Masukan Tanggal Lahir"
                                value="{{ old('tgl_lahir') }}">
                            <x-input-error :messages="$errors->get('tgl_lahir')" class="mt-2" />
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-sm-3 col-form-label" for="field-instansi">Instansi</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control {{ $errors->has('instansi') ? 'is-invalid' : '' }}"
                                id="field-instansi" name="instansi" placeholder="Masukan Instansi"
                                value="{{ old('instansi') }}">
                            <x-input-error :messages="$errors->get('instansi')" class="mt-2" />
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-sm-3 col-form-label" for="field-jabatan">Jabatan</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control {{ $errors->has('jabatan') ? 'is-invalid' : '' }}"
                                id="field-jabatan" name="jabatan" placeholder="Masukan Jabatan"
                                value="{{ old('jabatan') }}">
                            <x-input-error :messages="$errors->get('jabatan')" class="mt-2" />
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-sm-3 col-form-label" for="field-hp">No HP / WA</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control {{ $errors->has('hp') ? 'is-invalid' : '' }}"
                                id="field-hp" name="hp" placeholder="Masukan No Handphone"
                                value="{{ old('hp') }}">
                            <x-input-error :messages="$errors->get('hp')" class="mt-2" />
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-sm-3 col-form-label" for="field-email">Alamat Email</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                id="field-email" name="email" placeholder="Masukan Alamat Email"
                                value="{{ old('email') }}">
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-sm-3 col-form-label" for="field-alamat">Alamat Lengkap</label>
                        <div class="col-sm-6">
                            <textarea name="alamat" id="field-alamat" rows="4" class="form-control  {{ $errors->has('alamat') ? 'is-invalid' : '' }}">{{ old('alamat') }}</textarea>
                            <x-input-error :messages="$errors->get('alamat')" class="mt-2" />
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

