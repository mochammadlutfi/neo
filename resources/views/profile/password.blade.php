<x-app-layout>
    <div class="content">
        <div class="content-heading d-flex justify-content-between align-items-center">
            <span>Ubah Password</span>
        </div>
        <div class="block block-rounded">
            <div class="block-content">
                <form method="POST" action="{{ route('password.update')}}">
                    @csrf
                    <div class="row mb-4">
                        <label class="col-sm-3 col-form-label" for="field-password">Password</label>
                        <div class="col-sm-6">
                            <input type="password"
                                class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                                id="field-password" name="password" placeholder="Masukan Password"
                                value="{{ ($data->password) ?? '' }}">
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-sm-3 col-form-label" for="field-password_confirmation ">Konfirmasi
                            Password</label>
                        <div class="col-sm-6">
                            <input type="password"
                                class="form-control {{ $errors->has('password_confirmation ') ? 'is-invalid' : '' }}"
                                id="field-password_confirmation " name="password_confirmation"
                                placeholder="Masukan Konfirmasi Password"
                                value="{{ ($data->password_confirmation ) ?? '' }}">
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
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

