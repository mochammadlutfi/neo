<x-app-layout>
    <div class="content">
        <div class="content-heading d-flex justify-content-between align-items-center">
            <div class="content-title">
                Dashboard
            </div>
        </div>
        <div class="row">
            <!-- Row #1 -->
            <div class="col-6 col-xl-4">
                <a
                    class="block block-rounded block-link-rotate text-end"
                    href="{{ route('admin.user.index') }}">
                    <div
                        class="block-content block-content-full d-sm-flex justify-content-between align-items-center">
                        <div class="d-none d-sm-block">
                            <i class="fa fa-users fa-2x opacity-25"></i>
                        </div>
                        <div class="text-end">
                            <div class="fs-3 fw-semibold">{{ $ovr['user']}}</div>
                            <div class="fs-sm fw-semibold text-uppercase text-muted">Konsumen</div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-6 col-xl-4">
                <a
                    class="block block-rounded block-link-rotate text-end"
                    href="{{ route('admin.order.index') }}">
                    <div
                        class="block-content block-content-full d-sm-flex justify-content-between align-items-center">
                        <div class="d-none d-sm-block">
                            <i class="fa fa-book fa-2x opacity-25"></i>
                        </div>
                        <div class="text-end">
                            <div class="fs-3 fw-semibold">{{ $ovr['order'] }}</div>
                            <div class="fs-sm fw-semibold text-uppercase text-muted">Pesanan</div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-6 col-xl-4">
                <a
                    class="block block-rounded block-link-rotate text-end"
                    href="{{ route('admin.payment.index') }}">
                    <div
                        class="block-content block-content-full d-sm-flex justify-content-between align-items-center">
                        <div class="d-none d-sm-block">
                            <i class="fa fa-wallet fa-2x opacity-25"></i>
                        </div>
                        <div class="text-end">
                            <div class="fs-3 fw-semibold">{{ $ovr['pembayaran'] }}</div>
                            <div class="fs-sm fw-semibold text-uppercase text-muted">Pembayaran</div>
                        </div>
                    </div>
                </a>
            </div>
            <!-- END Row #1 -->
        </div>

    </div>
    @push('scripts')
        <script>

        </script>
    @endpush
</x-app-layout>