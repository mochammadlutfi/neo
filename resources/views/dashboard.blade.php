<x-app-layout>

    <div class="content">
        @if (Auth()->user()->level == 'admin')
        <div class="row">
            <!-- Row #1 -->
            <div class="col-6 col-xl-4">
                <a class="block block-rounded block-link-shadow" href="javascript:void(0)">
                    <div class="block-content block-content-full">
                        <div class="py-3 text-center">
                            <div class="mb-3">
                                <i class="fa fa-4x fa-soccer-ball text-body-bg-dark"></i>
                            </div>
                            <div class="fs-3 fw-semibold">{{ $ovr['ekskul'] }}</div>
                            <div class="fs-sm fw-semibold text-uppercase text-muted">Ekskul Aktif</div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-6 col-xl-4">
                <a class="block block-rounded block-link-shadow" href="javascript:void(0)">
                    <div class="block-content block-content-full">
                        <div class="py-3 text-center">
                            <div class="mb-3">
                                <i class="fa fa-4x fa-users text-body-bg-dark"></i>
                            </div>
                            <div class="fs-3 fw-semibold">{{ $ovr['ekskul'] }}</div>
                            <div class="fs-sm fw-semibold text-uppercase text-muted">Pembina Ekskul</div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-6 col-xl-4">
                <a class="block block-rounded block-link-shadow" href="javascript:void(0)">
                    <div class="block-content block-content-full">
                        <div class="py-3 text-center">
                            <div class="mb-3">
                                <i class="fa fa-4x fa-users-rectangle text-body-bg-dark"></i>
                            </div>
                            <div class="fs-3 fw-semibold">{{ $ovr['ketua'] }}</div>
                            <div class="fs-sm fw-semibold text-uppercase text-muted">Ketua Ekskul</div>
                        </div>
                    </div>
                </a>
            </div>
            <!-- END Row #1 -->
        </div>
        @elseif (Auth()->user()->level == 'pembina')
        <div class="row">
            <!-- Row #1 -->
            <div class="col-6 col-xl-4">
                <a class="block block-rounded block-link-shadow" href="javascript:void(0)">
                    <div class="block-content block-content-full">
                        <div class="py-3 text-center">
                            <div class="mb-3">
                                <i class="fa fa-4x fa-soccer-ball text-body-bg-dark"></i>
                            </div>
                            <div class="fs-3 fw-semibold">{{ $ovr['ekskul'] }}</div>
                            <div class="fs-sm fw-semibold text-uppercase text-muted">Ekskul Aktif</div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-6 col-xl-4">
                <a class="block block-rounded block-link-shadow" href="javascript:void(0)">
                    <div class="block-content block-content-full">
                        <div class="py-3 text-center">
                            <div class="mb-3">
                                <i class="fa fa-4x fa-users text-body-bg-dark"></i>
                            </div>
                            <div class="fs-3 fw-semibold">{{ $ovr['ekskul'] }}</div>
                            <div class="fs-sm fw-semibold text-uppercase text-muted">Pembina Ekskul</div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-6 col-xl-4">
                <a class="block block-rounded block-link-shadow" href="javascript:void(0)">
                    <div class="block-content block-content-full">
                        <div class="py-3 text-center">
                            <div class="mb-3">
                                <i class="fa fa-4x fa-users-rectangle text-body-bg-dark"></i>
                            </div>
                            <div class="fs-3 fw-semibold">{{ $ovr['ketua'] }}</div>
                            <div class="fs-sm fw-semibold text-uppercase text-muted">Ketua Ekskul</div>
                        </div>
                    </div>
                </a>
            </div>
            <!-- END Row #1 -->
        </div>
        @else
        <div class="row">
            <!-- Row #1 -->
            <div class="col-6 col-xl-4">
                <a class="block block-rounded block-link-shadow" href="javascript:void(0)">
                    <div class="block-content block-content-full">
                        <div class="py-3 text-center">
                            <div class="mb-3">
                                <i class="fa fa-4x fa-user-check text-primary-light"></i>
                            </div>
                            <div class="fs-3 fw-semibold">{{ $ovr['aktif'] }}</div>
                            <div class="fs-sm fw-semibold text-uppercase text-primary">Anggota Aktif</div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-6 col-xl-4">
                <a class="block block-rounded block-link-shadow" href="javascript:void(0)">
                    <div class="block-content block-content-full">
                        <div class="py-3 text-center">
                            <div class="mb-3">
                                <i class="fa fa-4x fa-user-plus text-warning-light"></i>
                            </div>
                            <div class="fs-3 fw-semibold">{{ $ovr['baru'] }}</div>
                            <div class="fs-sm fw-semibold text-uppercase text-warning">Anggota Baru</div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-6 col-xl-4">
                <a class="block block-rounded block-link-shadow" href="javascript:void(0)">
                    <div class="block-content block-content-full">
                        <div class="py-3 text-center">
                            <div class="mb-3">
                                <i class="fa fa-4x fa-user-xmark text-danger-light"></i>
                            </div>
                            <div class="fs-3 fw-semibold">{{ $ovr['ditolak'] }}</div>
                            <div class="fs-sm fw-semibold text-uppercase text-danger">Anggota Keluar / Ditolak</div>
                        </div>
                    </div>
                </a>
            </div>
            <!-- END Row #1 -->
        </div>
        @endif
    </div>

</x-app-layout>
