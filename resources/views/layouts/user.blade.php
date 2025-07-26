<x-landing-layout>
    <div class="content">
        <div class="row">
            <div class="col-md-3">
                <div class="block rounded">
                    <div class="block-content p-2">
                        <ul class="nav nav-pills flex-column">
                            <li class="nav-item mb-2">
                                <a class="nav-link d-flex align-items-center justify-content-between
                                {{ request()->is('user/pesanan', 'user/pesanan/*') ? ' active' : '' }}"
                                    href="{{ route('user.order.index')}}">
                                    <span><i class="fa fa-fw fa-inbox opacity-50 me-1"></i> Pesanan Saya</span>
                                </a>
                            </li>
                            <li class="nav-item mb-2">
                                <a class="nav-link d-flex align-items-center justify-content-between
                                {{ request()->is('user/pembayaran', 'user/pembayaran/*') ? ' active' : '' }}"
                                    href="{{ route('user.payment.index') }}">
                                    <span><i class="fa fa-fw fa-wallet opacity-50 me-1"></i> Pembayaran</span>
                                </a>
                            </li>
                            <li class="nav-item mb-2">
                                <a class="nav-link d-flex align-items-center justify-content-between
                                {{ request()->is('user/project', 'user/project/*') ? ' active' : '' }}"
                                    href="{{ route('user.project.index') }}">
                                    <span><i class="si fa-fw si-briefcase opacity-50 me-2"></i>Project</span>
                                </a>
                            </li>
                            <li class="nav-item mb-2">
                                <a class="nav-link d-flex align-items-center justify-content-between
                                {{ request()->is('user/profile', 'user/profile/*') ? ' active' : '' }}"
                                    href="{{ route('user.profile.edit') }}">
                                    <span><i class="fa fa-fw fa-user opacity-50 me-1"></i>Profil</span>
                                </a>
                            </li>
                            <li class="nav-item mb-2">
                                <a class="nav-link d-flex align-items-center justify-content-between"
                                    href="{{ route('user.profile.password') }}">
                                    <span><i class="fa fa-fw fa-lock opacity-50 me-1"></i>Ubah Password</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                {{ $slot }}
            </div>
        </div>
    </div>

</x-landing-layout>