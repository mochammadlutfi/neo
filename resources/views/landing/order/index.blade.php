<x-user-layout>
    <!-- Page Header -->
    <div class="block block-rounded">
        <div class="block-header">
            <h3 class="block-title fs-base fw-bold">
                <i class="fa fa-shopping-cart me-2 text-primary"></i>
                Pesanan Saya
            </h3>
            <div class="block-options">
                <span class="fs-base text-muted">Total: {{ $data->count() }} pesanan</span>
            </div>
        </div>
    </div>

    @if($data->count() > 0)
        <!-- Orders Grid -->
        <div class="row">
            @foreach ($data as $d)
                <div class="col-lg-6 col-xl- col-12 mb-4">
                    <div class="block block-rounded h-100">
                        <!-- Order Header -->
                        <div class="block-header bg-body-light">
                            <div class="w-100 d-flex justify-content-between align-items-center">
                                <div>
                                    <h4 class="block-title fs-base fw-bold mb-1">{{ $d->nomor }}</h4>
                                    <div class="fs-xs text-muted">{{ \Carbon\Carbon::parse($d->created_at)->translatedFormat('d F Y') }}</div>
                                </div>
                                <div>
                                    @if($d->status_pembayaran == 'Belum Bayar')
                                        <span class="badge bg-danger fs-xs">Belum Bayar</span>
                                    @elseif ($d->status_pembayaran == 'Sebagian')
                                        <span class="badge bg-warning fs-xs">Sebagian</span>
                                    @else
                                        <span class="badge bg-success fs-xs">Lunas</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Order Content -->
                        <div class="block-content flex-grow-1 d-flex flex-column">
                            <!-- Package Info -->
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-shrink-0">
                                    <i class="fa fa-box fa-2x text-primary"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <div class="fs-base fw-bold mb-1">{{ $d->paket->nama }}</div>
                                    <div class="fs-xs text-muted">{{ $d->paket->deskripsi ?? 'Social Media Management Package' }}</div>
                                </div>
                            </div>

                            <!-- Order Details -->
                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <div class="p-2 bg-body-light rounded text-center">
                                        <div class="fs-xs text-muted">Durasi</div>
                                        <div class="fs-base fw-semibold">{{ $d->durasi }} Bulan</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-2 bg-body-light rounded text-center">
                                        <div class="fs-xs text-muted">Total</div>
                                        <div class="fs-base fw-bold text-primary">Rp {{ number_format($d->total,0,',','.') }}</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Payment Progress -->
                            <div class="mb-3">
                                <div class="d-flex justify-content-between fs-xs text-muted mb-1">
                                    <span>Progress Pembayaran</span>
                                    <span>{{ number_format(($d->payment->where('status', 'terima')->sum('jumlah') / $d->total) * 100, 0) }}%</span>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar bg-success" style="width: {{ ($d->payment->where('status', 'terima')->sum('jumlah') / $d->total) * 100 }}%"></div>
                                </div>
                                <div class="d-flex justify-content-between fs-xs mt-1">
                                    <span class="text-success">Rp {{ number_format($d->payment->where('status', 'terima')->sum('jumlah'),0,',','.') }}</span>
                                    <span class="text-muted">dari Rp {{ number_format($d->total,0,',','.') }}</span>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="mt-auto">
                                <div class="d-flex gap-2">
                                    <a href="{{ route('user.order.show', $d->id)}}" class="btn btn-primary btn-sm flex-grow-1 fs-xs">
                                        <i class="fa fa-eye me-1"></i>
                                        Detail
                                    </a>
                                    @if($d->status_pembayaran != 'Lunas')
                                        <button type="button" class="btn btn-success btn-sm fs-xs" 
                                            onclick="openPaymentModal('{{ $d->id }}', '{{ $d->nomor }}', '{{ $d->total }}', '{{ $d->payment->where('status', 'terima')->sum('jumlah') }}')">
                                            <i class="fa fa-credit-card me-1"></i>
                                            Bayar
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <!-- Empty State -->
        <div class="block block-rounded">
            <div class="block-content text-center py-5">
                <i class="fa fa-shopping-cart fa-4x text-muted mb-4"></i>
                <h4 class="fs-base fw-bold text-muted mb-2">Belum Ada Pesanan</h4>
                <p class="fs-base text-muted mb-4">Anda belum memiliki pesanan apapun. Mulai berbelanja sekarang!</p>
                <a href="{{ route('home') }}" class="btn btn-primary fs-base">
                    <i class="fa fa-plus me-1"></i>
                    Buat Pesanan Baru
                </a>
            </div>
        </div>
    @endif

    <!-- Payment Modal Component -->
    <x-payment-modal modal-id="paymentModal" />
    
    @push('scripts')
        <!-- Payment modal is now handled by the component -->
    @endpush
</x-user-layout>