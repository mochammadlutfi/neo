<x-user-layout>
    <div class="block rounded">
        <div class="block-header border-bottom border-3">
            <h3 class="block-title fw-semibold">Ubah Password</h3>
            <div class="block-options">

            </div>
        </div>
        <div class="block-content p-4">
            <form method="POST" action="{{ route('user.profile.password') }}">
                @csrf
                <x-input-field type="password" id="old_password" name="old_password" label="Password Lama"/>
                <x-input-field type="password" id="password" name="password" label="Password Baru"/>
                <x-input-field type="password" id="password_conf" name="password_conf" label="Konfirmasi Password"/>
                <div class="mb-4">
                    <button type="submit" class="btn btn-primary">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-user-layout>