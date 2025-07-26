<x-app-layout>
    <div class="content">
        <div class="content-heading d-flex justify-content-between align-items-center">
            <span>Ubah Profil</span>
        </div>
        <div class="block block-rounded">
            <div class="block-content">
                <form method="POST" action="{{ route('profile.update')}}">
                    @csrf
                    @if(auth()->user()->level == 'ketua')
                    <div class="row mb-4">
                        <label class="col-sm-3 col-form-label" for="field-nis">NIS</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control {{ $errors->has('nis') ? 'is-invalid' : '' }}"
                                id="field-nis" name="nis" placeholder="Masukan NIS" value="{{ old('nis', $data->anggota->nis) }}">
                            <x-input-error :messages="$errors->get('nis')" class="mt-2" />
                        </div>
                    </div>
                    @endif
                    <div class="row mb-4">
                        <label class="col-sm-3 col-form-label" for="field-nama">Nama Lengkap</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control {{ $errors->has('nama') ? 'is-invalid' : '' }}"
                                id="field-nama" name="nama" placeholder="Masukan Nama Lengkap"
                                value="{{ old('nama', $data->nama) }}">
                            <x-input-error :messages="$errors->get('nama')" class="mt-2" />
                        </div>
                    </div>
                    
                    @if(auth()->user()->level == 'ketua')
                    <div class="row mb-4">
                        <label class="col-sm-3 col-form-label" for="field-jk">Jenis Kelamin</label>
                        <div class="col-sm-6">
                            <select class="form-select {{ $errors->has('jk') ? 'is-invalid' : '' }}"
                                id="field-jk" style="width: 100%;" name="jk">
                                <option value="">Pilih</option>
                                <option value="L" {{ (old('jk', $data->anggota->jk) == 'L') ? 'selected' : '' }}>Laki-Laki</option>
                                <option value="P" {{ (old('jk', $data->anggota->jk) == 'P') ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            <x-input-error :messages="$errors->get('jk')" class="mt-2" />
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-sm-3 col-form-label" for="field-kelas">Kelas</label>
                        <div class="col-sm-6">
                            <select class="form-select {{ $errors->has('kelas') ? 'is-invalid' : '' }}"
                                id="field-kelas" style="width: 100%;" name="kelas">
                                <option value="">Pilih</option>
                                <option value="VII" {{ (old('kelas', $data->anggota->kelas) == 'VII') ? 'selected' : '' }}>VII</option>
                                <option value="VIII" {{ (old('kelas', $data->anggota->kelas) == 'VIII') ? 'selected' : '' }}>VIII</option>
                                <option value="IX" {{ (old('kelas', $data->anggota->kelas) == 'IX') ? 'selected' : '' }}>IX</option>
                            </select>
                            <x-input-error :messages="$errors->get('kelas')" class="mt-2" />
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-sm-3 col-form-label" for="field-hp">No HP / WA</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control {{ $errors->has('hp') ? 'is-invalid' : '' }}"
                                id="field-hp" name="hp" placeholder="Masukan No Handphone"
                                value="{{ old('hp', $data->anggota->hp) }}">
                            <x-input-error :messages="$errors->get('hp')" class="mt-2" />
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-sm-3 col-form-label" for="field-email">Alamat Email</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                id="field-email" name="email" placeholder="Masukan Alamat Email"
                                value="{{ old('email', $data->anggota->email) }}">
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-sm-3 col-form-label" for="field-alamat">Alamat Lengkap</label>
                        <div class="col-sm-6">
                            <textarea name="alamat" id="field-alamat" rows="4" class="form-control  {{ $errors->has('alamat') ? 'is-invalid' : '' }}">{{ old('alamat', $data->anggota->alamat) }}</textarea>
                            <x-input-error :messages="$errors->get('alamat')" class="mt-2" />
                        </div>
                    </div>
                    
                    @endif
                    <div class="row mb-4">
                        <label class="col-sm-3 col-form-label" for="field-username">Username</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control {{ $errors->has('username') ? 'is-invalid' : '' }}"
                                id="field-username" name="username" placeholder="Masukan Username" value="{{ old('email', $data->username) }}">
                            <x-input-error :messages="$errors->get('username')" class="mt-2" />
                        </div>
                    </div>
                    <div class="mb-4">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

