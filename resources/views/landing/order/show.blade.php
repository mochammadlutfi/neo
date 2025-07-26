<x-user-layout>
    <!-- Order Header -->
    <div class="block block-rounded">
        <div class="block-header">
            <h3 class="block-title fs-base fw-bold">
                <i class="fa fa-shopping-cart me-2 text-primary"></i>
                Detail Pesanan {{ $data->nomor }}
            </h3>
            <div class="block-options">
                @if($data->status_pembayaran != 'Lunas')
                <button type="button" class="btn btn-sm btn-success me-2 fs-base" 
                    onclick="openPaymentModal('{{ $data->id }}', '{{ $data->nomor }}', '{{ $data->total }}', '{{ $data->payment->where('status', 'terima')->sum('jumlah') }}')">
                    <i class="fa fa-credit-card me-1"></i> Bayar Sekarang
                </button>
                @endif
                <a href="{{ route('user.order.invoice', $data->id) }}" class="btn btn-sm btn-primary fs-base" target="_blank">
                    <i class="fa fa-print me-1"></i> Cetak Invoice
                </a>
            </div>
        </div>
    </div>

    <!-- Order Summary Cards -->
    <div class="row mb-4">
        <!-- Order Info Card -->
        <div class="col-lg-8">
            <div class="block block-rounded">
                <div class="block-header">
                    <h3 class="block-title fs-base fw-bold">Informasi Pesanan</h3>
                </div>
                <div class="block-content p-4 pt-0">
                    <div class="row g-3 mb-4">
                        <div class="col-sm-6">
                            <div class="p-3 bg-body-light rounded">
                                <div class="fs-xs text-muted mb-1">Nomor Pesanan</div>
                                <div class="fs-base fw-bold text-primary">{{ $data->nomor }}</div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-3 bg-body-light rounded">
                                <div class="fs-xs text-muted mb-1">Status Pembayaran</div>
                                <div>
                                    @if($data->status_pembayaran == 'Belum Bayar')
                                        <span class="badge bg-danger fs-base">Belum Bayar</span>
                                    @elseif ($data->status_pembayaran == 'Sebagian')
                                        <span class="badge bg-warning fs-base">Sebagian</span>
                                    @else
                                        <span class="badge bg-success fs-base">Lunas</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-3 bg-body-light rounded">
                                <div class="fs-xs text-muted mb-1">Tanggal Pemesanan</div>
                                <div class="fs-base fw-semibold">{{ \Carbon\Carbon::parse($data->tgl)->translatedFormat('d F Y') }}</div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-3 bg-body-light rounded">
                                <div class="fs-xs text-muted mb-1">Batas Pembayaran</div>
                                <div class="fs-base fw-semibold text-warning">{{ \Carbon\Carbon::parse($data->tgl_tempo)->translatedFormat('d F Y') }}</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="border-top pt-3">
                        <div class="fs-base fw-bold mb-3">Detail Paket</div>
                        <div class="d-flex align-items-center p-3 bg-body rounded">
                            <div class="flex-shrink-0">
                                <i class="fa fa-box fa-2x text-primary"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <div class="fs-base fw-bold mb-1">{{ $data->paket->nama }}</div>
                                <div class="fs-base text-muted mb-1">{{ $data->paket->deskripsi ?? 'Social Media Management Package' }}</div>
                                <div class="fs-base text-muted">Durasi: {{ $data->durasi }} Bulan | Periode: {{ \Carbon\Carbon::parse($data->tgl)->translatedFormat('d M Y') }} - {{ \Carbon\Carbon::parse($data->tgl_selesai)->translatedFormat('d M Y') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Summary Card -->
        <div class="col-lg-4">
            <div class="block block-rounded">
                <div class="block-header">
                    <h3 class="block-title fs-base fw-bold">Ringkasan Pembayaran</h3>
                </div>
                <div class="block-content p-0">
                    <div class="d-flex justify-content-between p-2 border-bottom">
                        <span class="fs-xs text-muted">Harga per Bulan</span>
                        <span class="fs-base fw-semibold">Rp {{ number_format($data->harga,0,',','.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between p-2 border-bottom">
                        <span class="fs-xs text-muted">Durasi</span>
                        <span class="fs-base fw-semibold">{{ $data->durasi }} Bulan</span>
                    </div>
                    <div class="d-flex justify-content-between p-2 border-bottom">
                        <span class="fs-xs fw-semibold">Total Tagihan</span>
                        <span class="fs-base fw-bold text-primary">Rp {{ number_format($data->total,0,',','.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between p-2 border-bottom">
                        <span class="fs-xs text-muted">Minimal DP (50%)</span>
                        <span class="fs-base fw-semibold text-warning">Rp {{ number_format($data->total * 0.5,0,',','.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between p-2 border-bottom">
                        <span class="fs-xs text-success">Total Dibayar</span>
                        <span class="fs-base fw-semibold text-success">Rp {{ number_format($data->payment->where('status', 'terima')->sum('jumlah'),0,',','.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-3 bg-body-light rounded px-3 mt-2">
                        <span class="fs-xs fw-bold">Sisa Pembayaran</span>
                        <span class="fs-base fw-bold text-danger">Rp {{ number_format($data->total - $data->payment->where('status', 'terima')->sum('jumlah'),0,',','.') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs Section -->
    <div class="block block-rounded">
        <div class="block-header">
            <ul class="nav nav-tabs nav-tabs-block" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active fs-base" id="btabs-project-tab" data-bs-toggle="tab"
                        data-bs-target="#btabs-project" role="tab" aria-controls="btabs-project"
                        aria-selected="true">
                        <i class="fa fa-briefcase me-1"></i> Project
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fs-base" id="btabs-payment-tab" data-bs-toggle="tab"
                        data-bs-target="#btabs-payment" role="tab"
                        aria-controls="btabs-payment" aria-selected="false" tabindex="-1">
                        <i class="fa fa-credit-card me-1"></i> Riwayat Pembayaran
                    </button>
                </li>
            </ul>
        </div>
        <div class="block-content tab-content">
            <!-- Project Tab -->
            <div class="tab-pane fade show active" id="btabs-project" role="tabpanel"
                aria-labelledby="btabs-project-tab" tabindex="0">
                @if($data->project->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover table-vcenter fs-base">
                        <thead>
                            <tr>
                                <th width="60px" class="fs-base">No</th>
                                <th class="fs-base">Nama Project</th>
                                <th width="120px" class="fs-base">Total Tugas</th>
                                <th width="100px" class="fs-base">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data->project as $project)
                                <tr>
                                    <td class="text-center fs-base">{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="fs-base fw-semibold">{{ $project->nama }}</div>
                                        <div class="fs-base text-muted">{{ $project->deskripsi ?? 'Project untuk paket ' . $data->paket->nama }}</div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-primary fs-base">{{ $project->task_count }} Tugas</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('user.project.show', $project->id) }}" class="btn btn-sm btn-primary fs-base">
                                            <i class="fa fa-eye me-1"></i>
                                            Detail
                                        </a>
                                    </td>
                                </tr>                            
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-5">
                    <i class="fa fa-briefcase fa-3x text-muted mb-3"></i>
                    <div class="fs-base fw-bold text-muted mb-2">Belum Ada Project</div>
                    <div class="fs-base text-muted">Project akan dibuat setelah pembayaran diterima</div>
                </div>
                @endif
            </div>
            
            <!-- Payment History Tab -->
            <div class="tab-pane fade" id="btabs-payment" role="tabpanel"
                aria-labelledby="btabs-payment-tab" tabindex="0">
                @if($data->payment->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover table-vcenter fs-base">
                        <thead>
                            <tr>
                                <th width="60px" class="fs-base">No</th>
                                <th class="fs-base">Tanggal Bayar</th>
                                <th class="fs-base">Jumlah</th>
                                <th class="fs-base">Status</th>
                                <th width="100px" class="fs-base">Bukti</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data->payment as $payment)
                                <tr>
                                    <td class="text-center fs-base">{{ $loop->iteration }}</td>
                                    <td class="fs-base">{{ \Carbon\Carbon::parse($payment->tgl)->translatedFormat('d F Y') }}</td>
                                    <td class="fs-base fw-semibold">Rp {{ number_format($payment->jumlah,0,',','.') }}</td>
                                    <td>
                                        @if($payment->status == 'pending')
                                        <span class="badge bg-warning fs-base">Pending</span>
                                        @elseif ($payment->status == 'terima')
                                        <span class="badge bg-success fs-base">Diterima</span>
                                        @else
                                        <span class="badge bg-danger fs-base">Ditolak</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($payment->bukti)
                                        <button type="button" class="btn btn-sm btn-outline-primary fs-base" onclick="showPaymentProof('{{ asset($payment->bukti) }}')">
                                            <i class="fa fa-image me-1"></i> Lihat
                                        </button>
                                        @else
                                        <span class="fs-base text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>                            
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-5">
                    <i class="fa fa-credit-card fa-3x text-muted mb-3"></i>
                    <div class="fs-base fw-bold text-muted mb-2">Belum Ada Pembayaran</div>
                    <div class="fs-base text-muted mb-3">Lakukan pembayaran untuk memulai layanan</div>
                    @if($data->status_pembayaran != 'Lunas')
                    <button type="button" class="btn btn-success fs-base" 
                        onclick="openPaymentModal('{{ $data->id }}', '{{ $data->nomor }}', '{{ $data->total }}', '{{ $data->payment->where('status', 'terima')->sum('jumlah') }}')">
                        <i class="fa fa-credit-card me-1"></i> Bayar Sekarang
                    </button>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Payment Modal Component -->
    <x-payment-modal modal-id="paymentModal" order-id="{{ $data->id }}" order-number="{{ $data->nomor }}" />

    <!-- Payment Proof Modal -->
    <div class="modal fade" id="proofModal" tabindex="-1" aria-labelledby="proofModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                
                <div class="block block-rounded shadow-none mb-0">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">Bukti Pembayaran</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-bs-dismiss="modal"
                                aria-label="Close">
                                <i class="fa fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content p-3 text-center">
                        <img id="proof-image" src="" alt="Bukti Pembayaran" class="img-fluid rounded">
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Function to show payment proof - using native Bootstrap modal
            window.showPaymentProof = function(imageUrl) {
                document.getElementById('proof-image').src = imageUrl;
                const proofModal = new bootstrap.Modal(document.getElementById('proofModal'));
                proofModal.show();
            };
        </script>
    @endpush
</x-user-layout>