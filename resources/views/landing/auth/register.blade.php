<x-landing-layout>

    <div class="content content-full overflow-hidden">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- Header -->
                <div class="py-4 text-center">
                    <h1 class="h3 fw-bold mt-2 mb-2">Buat Akun</h1>
                    <h4 class="h5 fw-medium mt-2 mb-2">Sudah Punya Akun ? <a
                            href="{{ route('login') }}">Masuk</a></h4>
                </div>
                <!-- END Header -->
        
                <div class="block block-rounded">
                    <div class="block-content">
                        <form method="POST" action="{{ route('register') }}">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <x-input-field type="text" id="nama" name="nama" label="Nama Lengkap"/>
                                </div>
                                <div class="col-md-6">
                                    <x-input-field type="text" id="perusahaan" name="perusahaan" label="Nama Perusahaan"/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <x-input-field type="email" id="email" name="email" label="Alamat Email"/>
                                </div>
                                <div class="col-md-6">
                                    <x-input-field type="text" id="hp" name="hp" label="No Handphone"/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <x-input-field type="password" id="password" name="password" label="Password"/>
                                </div>
                                <div class="col-md-6">
                                    <x-input-field type="password" id="password_conf" name="password_conf" label="Konfirmasi Password"/>
                                </div>
                            </div>
                            <div class="mb-4">
                                <button type="submit" class="btn btn-lg btn-gd-main rounded-pill fw-medium w-100">
                                    Daftar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- END Sign Up Form -->
            </div>
        </div>
    </div>
</x-landing-layout>