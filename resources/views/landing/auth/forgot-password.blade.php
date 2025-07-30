<x-landing-layout>

    <div class="content content-full">
        <div class="row justify-content-center">
            <div class="col-md-6">
                
        <!-- Header -->
        <div class="py-4 text-center">
            <h1 class="h3 fw-bold mt-4 mb-2">Reset Password</h1>
            <h4 class="h5 fw-medium mt-4 mb-2">
                Masukkan email Anda untuk mendapatkan link reset password
            </h4>
        </div>
        <!-- END Header -->

        <div class="block block-rounded">
            <div class="block-content p-4">
                
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label" for="email">Alamat Email
                            <span class="text-danger">*</span>
                        </label>
                        <input
                            type="email"
                            class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="Masukan alamat email Anda"
                            required>
                        <x-input-error :messages="$errors->get('email')" class="mt-2"/>
                    </div>
                    
                    <button type="submit" class="btn btn-lg btn-gd-main rounded-pill fw-medium w-100">
                        Kirim Link Reset Password
                    </button>
                </form>
                
                <div class="text-center mt-3">
                    <a href="{{ route('login') }}" class="text-muted">
                        <small><i class="fa fa-arrow-left me-1"></i>Kembali ke Login</small>
                    </a>
                </div>
            </div>
        </div>
        <!-- END Reset Password Form -->
            </div>
        </div>
    </div>

</x-landing-layout>