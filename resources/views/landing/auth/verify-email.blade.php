<x-landing-layout>

    <div class="content content-full">
        <div class="row justify-content-center">
            <div class="col-md-6">
                
        <!-- Header -->
        <div class="py-4 text-center">
            <h1 class="h3 fw-bold mt-4 mb-2">Selamat Datang, {{ auth()->user()->nama }}!</h1>
            <h4 class="h5 fw-medium mt-4 mb-2">Silakan verifikasi email Anda untuk melanjutkan</h4>
        </div>
        <!-- END Header -->

        <div class="block block-rounded">
            <div class="block-content p-4">
                
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <div class="text-center mb-4">
                    <div class="alert alert-info">
                        <i class="fa fa-envelope fa-2x mb-3"></i>
                        <p class="mb-2">Email verifikasi telah dikirim ke:</p>
                        <p class="fw-bold mb-2">{{ auth()->user()->email }}</p>
                        <p class="mb-0">Silakan cek inbox atau folder spam Anda dan klik link verifikasi.</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <div class="row justify-content-center">
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-lg btn-alt-primary w-100">
                                <i class="fa fa-fw fa-envelope opacity-50 me-1"></i> Kirim Ulang Email Verifikasi
                            </button>
                        </div>
                    </div>
                </form>

                <div class="text-center mt-4">
                    <form method="POST" action="{{ route('user.logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-link text-muted">
                            <i class="fa fa-fw fa-sign-out-alt opacity-50 me-1"></i> Logout dan Login dengan Akun Lain
                        </button>
                    </form>
                </div>

            </div>
        </div>

            </div>
        </div>
    </div>

</x-landing-layout>