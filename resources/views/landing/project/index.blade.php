<x-user-layout>
    <!-- Page Header -->
    <div class="block block-rounded">
        <div class="block-header">
            <h3 class="block-title fs-base fw-bold">
                <i class="fa fa-briefcase me-2 text-primary"></i>
                Project Saya
            </h3>
            <div class="block-options">
                <span class="fs-base text-muted">Total: {{ $data->count() }} project</span>
            </div>
        </div>
    </div>

    @if($data->count() > 0)
        <!-- Projects Grid -->
        <div class="row">
            @foreach ($data as $d)
                <div class="col-lg-6 col-xl-6 mb-4">
                    <div class="block block-rounded h-100">
                        <!-- Project Header -->
                        <div class="block-header bg-body-light">
                            <div class="w-100 d-flex justify-content-between align-items-center">
                                <div>
                                    <h4 class="block-title fs-base fw-bold mb-1">{{ $d->nama }}</h4>
                                    <div class="fs-xs text-muted">{{ $d->order->nomor }}</div>
                                </div>
                                <div>
                                    @if($d->order->status_pembayaran == 'Belum Bayar')
                                        <span class="badge bg-danger fs-xs">Belum Bayar</span>
                                    @elseif ($d->order->status_pembayaran == 'Sebagian')
                                        <span class="badge bg-warning fs-xs">Sebagian</span>
                                    @else
                                        <span class="badge bg-success fs-xs">Lunas</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Project Content -->
                        <div class="block-content p-4 flex-grow-1 d-flex flex-column">
                            <!-- Project Info -->
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-shrink-0">
                                    <i class="fa fa-tasks fa-2x text-primary"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <div class="fs-base fw-bold mb-1">{{ $d->task_count }} Tugas</div>
                                    <div class="fs-xs text-muted">{{ $d->deskripsi ?? 'Project untuk paket ' . $d->order->paket->nama }}</div>
                                </div>
                            </div>

                            <!-- Project Details -->
                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <div class="p-2 bg-body-light rounded text-center">
                                        <div class="fs-xs text-muted">Paket</div>
                                        <div class="fs-base fw-semibold">{{ $d->order->paket->nama }}</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-2 bg-body-light rounded text-center">
                                        <div class="fs-xs text-muted">Durasi</div>
                                        <div class="fs-base fw-semibold">{{ $d->order->durasi }} Bulan</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Progress Information -->
                            @php
                                $completedTasks = $d->task->where('status', 'Disetujui')->count();
                                $totalTasks = $d->task_count;
                                $progress = $totalTasks > 0 ? ($completedTasks / $totalTasks) * 100 : 0;
                            @endphp
                            <div class="mb-3">
                                <div class="d-flex justify-content-between fs-xs text-muted mb-1">
                                    <span>Progress Tugas</span>
                                    <span>{{ $completedTasks }}/{{ $totalTasks }} ({{ number_format($progress, 0) }}%)</span>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar bg-success" style="width: {{ $progress }}%"></div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="mt-auto">
                                <div class="d-flex gap-2">
                                    @if($d->order->status_pembayaran == 'Lunas')
                                        <a href="{{ route('user.project.show', $d->id) }}" class="btn btn-primary btn-sm flex-grow-1 fs-xs">
                                            <i class="fa fa-eye me-1"></i>
                                            Detail
                                        </a>
                                        <a href="{{ route('user.project.calendar', $d->id) }}" class="btn btn-outline-warning btn-sm fs-xs">
                                            <i class="fa fa-calendar me-1"></i>
                                            Kalender
                                        </a>
                                    @else
                                        <div class="w-100 text-center">
                                            <div class="fs-xs text-muted mb-2">Project akan aktif setelah pembayaran lunas</div>
                                            <a href="{{ route('user.order.show', $d->order->id) }}" class="btn btn-success btn-sm fs-xs">
                                                <i class="fa fa-credit-card me-1"></i>
                                                Bayar Sekarang
                                            </a>
                                        </div>
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
                <i class="fa fa-briefcase fa-4x text-muted mb-4"></i>
                <h4 class="fs-base fw-bold text-muted mb-2">Belum Ada Project</h4>
                <p class="fs-base text-muted mb-4">Project akan dibuat setelah Anda melakukan pemesanan dan pembayaran.</p>
                <a href="{{ route('home') }}" class="btn btn-primary fs-base">
                    <i class="fa fa-plus me-1"></i>
                    Buat Pesanan Baru
                </a>
            </div>
        </div>
    @endif
</x-user-layout>