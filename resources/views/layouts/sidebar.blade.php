<nav id="sidebar">
    <!-- Sidebar Content -->
    <div class="sidebar-content">
        <!-- Side Header -->
        <div class="d-flex justify-content-lg-center pt-4">
            <!-- Logo -->
            <div>
                <img src="/images/logo.jpg" height="50px">
            </div>
            <!-- END Logo -->
        </div>
        <!-- END Side Header -->

        <!-- Sidebar Scrolling -->
        <div class="js-sidebar-scroll">
            <!-- Side Navigation -->
            <div class="content-side content-side-full">
                <ul class="nav-main">
                    <li class="nav-main-item">
                        <a class="nav-main-link {{ request()->is('admin/beranda') ? ' active' : '' }}" href="{{ route('admin.beranda') }}">
                            <i class="nav-main-link-icon si si-grid"></i>
                            <span class="nav-main-link-name">Dashboard</span>
                        </a>
                    </li>
                    @if(in_array(auth()->guard('admin')->user()->level, ['Marketing', 'Manager']))
                    <li class="nav-main-item">
                        <a class="nav-main-link {{ request()->is('admin/peserta') ? ' active' : '' }}" href="{{ route('admin.user.index') }}">
                            <i class="nav-main-link-icon si si-user"></i>
                            <span class="nav-main-link-name">Konsumen</span>
                        </a>
                    </li>
                    <li class="nav-main-item">
                        <a class="nav-main-link {{ request()->is('admin/order') ? ' active' : '' }}" href="{{ route('admin.order.index') }}">
                            <i class="nav-main-link-icon si si-energy"></i>
                            <span class="nav-main-link-name">Transaksi</span>
                        </a>
                    </li>
                    @endif
                    @if(in_array(auth()->guard('admin')->user()->level, ['Content Planner', 'Manager', 'Marketing']))
                    <li class="nav-main-item">
                        <a class="nav-main-link {{ request()->is('admin/journey') ? ' active' : '' }}" href="{{ route('admin.journey.index') }}">
                            <i class="nav-main-link-icon far fa-map"></i>
                            <span class="nav-main-link-name">Customer Journey</span>
                        </a>
                    </li>
                    @endif
                    @if(in_array(auth()->guard('admin')->user()->level, ['Manager']))
                    <li class="nav-main-item">
                        <a class="nav-main-link {{ request()->is('admin/paket', 'admin/paket/*') ? ' active' : '' }}" href="{{ route('admin.paket.index') }}">
                            <i class="nav-main-link-icon fa fa-boxes-stacked"></i>
                            <span class="nav-main-link-name">Paket</span>
                        </a>
                    </li>
                    @endif
                    @if(in_array(auth()->guard('admin')->user()->level, ['Marketing', 'Manager']))
                    <li class="nav-main-item">
                        <a class="nav-main-link {{ request()->is('admin/pembayaran', 'admin/pembayaran/*') ? ' active' : '' }}" href="{{ route('admin.payment.index') }}">
                            <i class="nav-main-link-icon fa fa-wallet"></i>
                            <span class="nav-main-link-name">Pembayaran</span>
                        </a>
                    </li>
                    @endif
                    @if(in_array(auth()->guard('admin')->user()->level, ['Content Planner', 'Manager']))
                    <li class="nav-main-item">
                        <a class="nav-main-link {{ request()->is('admin/project') ? ' active' : '' }}" href="{{ route('admin.project.index') }}">
                            <i class="nav-main-link-icon si si-briefcase"></i>
                            <span class="nav-main-link-name">Manajemen Konten</span>
                        </a>
                    </li>
                    @endif
                    @if(in_array(auth()->guard('admin')->user()->level, ['Manager']))
                    <li class="nav-main-item">
                        <a class="nav-main-link {{ request()->is('admin/staff') ? ' active' : '' }}" href="{{ route('admin.staff.index') }}">
                            <i class="nav-main-link-icon si si-user"></i>
                            <span class="nav-main-link-name">Staff</span>
                        </a>
                    </li>
                    @endif
                </ul>
            </div>
            <!-- END Side Navigation -->
        </div>
        <!-- END Sidebar Scrolling -->
    </div>
    <!-- Sidebar Content -->
</nav>