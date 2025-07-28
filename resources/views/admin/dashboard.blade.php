<x-app-layout>
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    
    <div class="content">
        <!-- Page Header -->
        <div class="block block-rounded">
            <div class="block-header">
                <h3 class="block-title fs-base fw-bold">
                    <i class="fa fa-tachometer-alt me-2 text-primary"></i>
                    Dashboard - Welcome {{ auth()->user()->name }}!
                </h3>
                <div class="block-options">
                    <span class="text-muted fs-sm">{{ Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</span>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-6 col-xl-3">
                <a class="block block-rounded block-link-rotate text-end" href="{{ route('admin.user.index') }}">
                    <div class="block-content block-content-full d-sm-flex justify-content-between align-items-center">
                        <div class="d-none d-sm-block">
                            <i class="fa fa-users fa-2x text-primary opacity-25"></i>
                        </div>
                        <div class="text-end">
                            <div class="fs-3 fw-semibold text-primary">{{ $ovr['user'] }}</div>
                            <div class="fs-sm fw-semibold text-uppercase text-muted">Total Konsumen</div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-6 col-xl-3">
                <a class="block block-rounded block-link-rotate text-end" href="{{ route('admin.order.index') }}">
                    <div class="block-content block-content-full d-sm-flex justify-content-between align-items-center">
                        <div class="d-none d-sm-block">
                            <i class="fa fa-shopping-cart fa-2x text-info opacity-25"></i>
                        </div>
                        <div class="text-end">
                            <div class="fs-3 fw-semibold text-info">{{ $ovr['order'] }}</div>
                            <div class="fs-sm fw-semibold text-uppercase text-muted">Total Pesanan</div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-6 col-xl-3">
                <a class="block block-rounded block-link-rotate text-end" href="{{ route('admin.payment.index') }}">
                    <div class="block-content block-content-full d-sm-flex justify-content-between align-items-center">
                        <div class="d-none d-sm-block">
                            <i class="fa fa-credit-card fa-2x text-success opacity-25"></i>
                        </div>
                        <div class="text-end">
                            <div class="fs-3 fw-semibold text-success">{{ $ovr['pembayaran'] }}</div>
                            <div class="fs-sm fw-semibold text-uppercase text-muted">Total Pembayaran</div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-6 col-xl-3">
                <a class="block block-rounded block-link-rotate text-end" href="{{ route('admin.project.index') }}">
                    <div class="block-content block-content-full d-sm-flex justify-content-between align-items-center">
                        <div class="d-none d-sm-block">
                            <i class="fa fa-project-diagram fa-2x text-warning opacity-25"></i>
                        </div>
                        <div class="text-end">
                            <div class="fs-3 fw-semibold text-warning">{{ $ovr['project'] }}</div>
                            <div class="fs-sm fw-semibold text-uppercase text-muted">Total Project</div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Revenue Cards -->
        <div class="row">
            <div class="col-md-4">
                <div class="block block-rounded">
                    <div class="block-content block-content-full">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <i class="fa fa-money-bill-wave fa-2x text-success"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fs-3 fw-semibold text-success">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
                                <div class="fs-sm text-muted">Total Pendapatan</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="block block-rounded">
                    <div class="block-content block-content-full">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <i class="fa fa-clock fa-2x text-warning"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fs-3 fw-semibold text-warning">Rp {{ number_format($pendingRevenue, 0, ',', '.') }}</div>
                                <div class="fs-sm text-muted">Pendapatan Pending</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="block block-rounded">
                    <div class="block-content block-content-full">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <i class="fa fa-calendar-month fa-2x text-info"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fs-3 fw-semibold text-info">Rp {{ number_format($thisMonthRevenue, 0, ',', '.') }}</div>
                                <div class="fs-sm text-muted">Pendapatan Bulan Ini</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="row">
            <!-- Monthly Orders Chart -->
            <div class="col-xl-8">
                <div class="block block-rounded">
                    <div class="block-header">
                        <h3 class="block-title">Statistik Pesanan & Pendapatan (6 Bulan Terakhir)</h3>
                    </div>
                    <div class="block-content">
                        <canvas id="monthlyChart" height="100"></canvas>
                    </div>
                </div>
            </div>
            
            <!-- Order Status Pie Chart -->
            <div class="col-xl-4">
                <div class="block block-rounded">
                    <div class="block-header">
                        <h3 class="block-title">Status Pembayaran Pesanan</h3>
                    </div>
                    <div class="block-content">
                        <canvas id="orderStatusChart" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="row">
            <!-- Recent Orders -->
            <div class="col-xl-6">
                <div class="block block-rounded">
                    <div class="block-header">
                        <h3 class="block-title">Pesanan Terbaru</h3>
                        <div class="block-options">
                            <a href="{{ route('admin.order.index') }}" class="btn btn-sm btn-primary">
                                <i class="fa fa-eye me-1"></i>Lihat Semua
                            </a>
                        </div>
                    </div>
                    <div class="block-content p-0">
                        <div class="table-responsive">
                            <table class="table table-vcenter table-striped table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Nomor</th>
                                        <th>Konsumen</th>
                                        <th>Paket</th>
                                        <th>Status</th>
                                        <th>Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentOrders as $order)
                                    <tr>
                                        <td class="fw-semibold">{{ $order->nomor }}</td>
                                        <td>{{ $order->user->nama ?? 'N/A' }}</td>
                                        <td>{{ $order->paket->nama ?? 'N/A' }}</td>
                                        <td>
                                            @if($order->status_pembayaran == 'Lunas')
                                                <span class="badge bg-success">Lunas</span>
                                            @elseif($order->status_pembayaran == 'Sebagian')
                                                <span class="badge bg-warning">Sebagian</span>
                                            @else
                                                <span class="badge bg-danger">Belum Bayar</span>
                                            @endif
                                        </td>
                                        <td class="fs-sm text-muted">{{ $order->created_at->diffForHumans() }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-3">Belum ada pesanan</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Payments -->
            <div class="col-xl-6">
                <div class="block block-rounded">
                    <div class="block-header">
                        <h3 class="block-title">Pembayaran Terbaru</h3>
                        <div class="block-options">
                            <a href="{{ route('admin.payment.index') }}" class="btn btn-sm btn-primary">
                                <i class="fa fa-eye me-1"></i>Lihat Semua
                            </a>
                        </div>
                    </div>
                    <div class="block-content p-0">
                        <div class="table-responsive">
                            <table class="table table-vcenter table-striped table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Konsumen</th>
                                        <th>Jumlah</th>
                                        <th>Status</th>
                                        <th>Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentPayments as $payment)
                                    <tr>
                                        <td>{{ $payment->order->user->nama ?? 'N/A' }}</td>
                                        <td class="fw-semibold">Rp {{ number_format($payment->jumlah, 0, ',', '.') }}</td>
                                        <td>
                                            @if($payment->status == 'terima')
                                                <span class="badge bg-success">Diterima</span>
                                            @elseif($payment->status == 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @else
                                                <span class="badge bg-danger">Ditolak</span>
                                            @endif
                                        </td>
                                        <td class="fs-sm text-muted">{{ $payment->created_at->diffForHumans() }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-3">Belum ada pembayaran</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    @push('scripts')
        <script>
            // Monthly Orders and Revenue Chart
            const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
            const monthlyChart = new Chart(monthlyCtx, {
                type: 'line',
                data: {
                    labels: [
                        @foreach($monthlyOrders as $data)
                            '{{ Carbon\Carbon::createFromDate($data->year, $data->month, 1)->translatedFormat('M Y') }}',
                        @endforeach
                    ],
                    datasets: [{
                        label: 'Pesanan',
                        data: [
                            @foreach($monthlyOrders as $data)
                                {{ $data->total }},
                            @endforeach
                        ],
                        borderColor: 'rgb(54, 162, 235)',
                        backgroundColor: 'rgba(54, 162, 235, 0.1)',
                        tension: 0.4,
                        yAxisID: 'y'
                    }, {
                        label: 'Pendapatan (Juta)',
                        data: [
                            @foreach($monthlyRevenue as $data)
                                {{ round($data->total / 1000000, 1) }},
                            @endforeach
                        ],
                        borderColor: 'rgb(75, 192, 192)',
                        backgroundColor: 'rgba(75, 192, 192, 0.1)',
                        tension: 0.4,
                        yAxisID: 'y1'
                    }]
                },
                options: {
                    responsive: true,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    scales: {
                        x: {
                            display: true,
                            title: {
                                display: true,
                                text: 'Bulan'
                            }
                        },
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            title: {
                                display: true,
                                text: 'Jumlah Pesanan'
                            },
                            beginAtZero: true
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            title: {
                                display: true,
                                text: 'Pendapatan (Juta Rupiah)'
                            },
                            beginAtZero: true,
                            grid: {
                                drawOnChartArea: false,
                            },
                        }
                    },
                    plugins: {
                        legend: {
                            display: true
                        },
                        title: {
                            display: false
                        }
                    }
                }
            });

            // Order Status Pie Chart
            const orderStatusCtx = document.getElementById('orderStatusChart').getContext('2d');
            const orderStatusChart = new Chart(orderStatusCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Lunas', 'Sebagian', 'Belum Bayar'],
                    datasets: [{
                        data: [
                            {{ $orderStats->lunas ?? 0 }},
                            {{ $orderStats->sebagian ?? 0 }},
                            {{ $orderStats->belum_bayar ?? 0 }}
                        ],
                        backgroundColor: [
                            'rgb(34, 197, 94)',
                            'rgb(251, 191, 36)',
                            'rgb(239, 68, 68)'
                        ],
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true
                            }
                        }
                    }
                }
            });
        </script>
    @endpush
</x-app-layout>