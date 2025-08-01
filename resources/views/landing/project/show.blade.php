<x-user-layout>
    @if($data->order->status_pembayaran != 'Lunas')
        <!-- Payment Required Notice -->
        <div class="block block-rounded">
            <div class="block-content text-center py-5">
                <i class="fa fa-lock fa-4x text-warning mb-4"></i>
                <h4 class="fs-base fw-bold text-warning mb-2">Akses Project Terbatas</h4>
                <p class="fs-base text-muted mb-4">
                    Project ini belum dapat diakses karena pembayaran pesanan {{ $data->order->nomor }} belum lunas.
                    <br>Silakan selesaikan pembayaran terlebih dahulu untuk mengakses detail project.
                </p>
                <div class="d-flex gap-2 justify-content-center">
                    <a href="{{ route('user.project.index') }}" class="btn btn-secondary fs-base">
                        <i class="fa fa-arrow-left me-1"></i> Kembali ke Manajemen Konten
                    </a>
                    <a href="{{ route('user.order.show', $data->order->id) }}" class="btn btn-success fs-base">
                        <i class="fa fa-credit-card me-1"></i> Bayar Sekarang
                    </a>
                </div>
            </div>
        </div>
    @else
        <!-- Project Header -->
        <div class="block block-rounded">
            <div class="block-header">
                <h3 class="block-title fs-base fw-bold">
                    <i class="fa fa-briefcase me-2 text-primary"></i>
                    Detail Project: {{ $data->nama }}
                </h3>
                <div class="block-options">
                    <a href="{{ route('user.project.pdf-report', $data->id) }}" class="btn btn-sm btn-success me-2 fs-base" target="_blank">
                        <i class="fa fa-file-pdf me-1"></i>
                        Download Laporan PDF
                    </a>
                    <a href="{{ route('user.project.calendar', $data->id) }}" class="btn btn-sm btn-warning me-2 fs-base">
                        <i class="fa fa-calendar me-1"></i>
                        Kalender
                    </a>
                    <a href="{{ route('user.project.index') }}" class="btn btn-sm btn-secondary fs-base">
                        <i class="fa fa-arrow-left me-1"></i>
                        Kembali
                    </a>
                </div>
            </div>
        </div>

        <!-- Project Information -->
        <div class="row mb-4">
            <div class="col-lg-8">
                <div class="block block-rounded">
                    <div class="block-header">
                        <h3 class="block-title fs-base fw-bold">Informasi Project</h3>
                    </div>
                    <div class="block-content p-4">
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <div class="p-3 bg-body-light rounded">
                                    <div class="fs-xs text-muted mb-1">Nama Project</div>
                                    <div class="fs-base fw-bold">{{ $data->nama }}</div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="p-3 bg-body-light rounded">
                                    <div class="fs-xs text-muted mb-1">No Pesanan</div>
                                    <div class="fs-base fw-bold text-primary">{{ $data->order->nomor }}</div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="p-3 bg-body-light rounded">
                                    <div class="fs-xs text-muted mb-1">Paket Layanan</div>
                                    <div class="fs-base fw-semibold">{{ $data->order->paket->nama }}</div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="p-3 bg-body-light rounded">
                                    <div class="fs-xs text-muted mb-1">Durasi</div>
                                    <div class="fs-base fw-semibold">{{ $data->order->durasi }} Bulan</div>
                                </div>
                            </div>
                        </div>
                        
                        @if($data->deskripsi)
                        <div class="border-top pt-3 mt-3">
                            <div class="fs-base fw-bold mb-2">Deskripsi Project</div>
                            <div class="fs-base text-muted">{{ $data->deskripsi }}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Project Statistics -->
            <div class="col-lg-4">
                <div class="block block-rounded">
                    <div class="block-header">
                        <h3 class="block-title fs-base fw-bold">Statistik Konten</h3>
                    </div>
                    <div class="block-content">
                        @php
                            $totalTasks = $task->count();
                            $completedTasks = $task->where('status', 'Disetujui')->count();
                            $pendingTasks = $task->where('status', 'Draft')->count();
                            $rejectedTasks = $task->where('status', 'Ditolak')->count();
                            $progress = $totalTasks > 0 ? ($completedTasks / $totalTasks) * 100 : 0;
                        @endphp
                        
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <div class="p-2 bg-primary-light rounded text-center">
                                    <div class="fs-xs text-muted">Total Tugas</div>
                                    <div class="fs-base fw-bold text-primary">{{ $totalTasks }}</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-2 bg-success-light rounded text-center">
                                    <div class="fs-xs text-muted">Selesai</div>
                                    <div class="fs-base fw-bold text-success">{{ $completedTasks }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between fs-xs text-muted mb-1">
                                <span>Progress Keseluruhan</span>
                                <span>{{ number_format($progress, 0) }}%</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-success" style="width: {{ $progress }}%"></div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between fs-xs">
                            <span class="text-warning">Pending: {{ $pendingTasks }}</span>
                            <span class="text-danger">Ditolak: {{ $rejectedTasks }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tasks List -->
        <div class="block block-rounded">
            <div class="block-header">
                <h3 class="block-title fs-base fw-bold">
                    <i class="fa fa-tasks me-2 text-primary"></i>
                    Daftar Konten
                </h3>
                <div class="block-options d-flex align-items-center gap-3">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-primary fs-xs">{{ $task->count() }} Total</span>
                        <span class="badge bg-success fs-xs">{{ $task->where('status', 'Disetujui')->count() }} Selesai</span>
                        <span class="badge bg-warning fs-xs">{{ $task->where('status', 'Draft')->count() }} Draft</span>
                    </div>
                </div>
            </div>
            <div class="block-content">
                @if($task->count() > 0)
                    <!-- Filter and Search -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fa fa-search"></i>
                                </span>
                                <input type="text" class="form-control fs-base" id="task-search" placeholder="Cari tugas...">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select fs-base" id="status-filter">
                                <option value="">Semua Status</option>
                                <option value="Draft">Draft</option>
                                <option value="Selesai">Selesai</option>
                                <option value="Disetujui">Disetujui</option>
                                <option value="Ditolak">Ditolak</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select fs-base" id="upload-filter">
                                <option value="">Semua Upload</option>
                                <option value="1">Sudah Upload</option>
                                <option value="0">Belum Upload</option>
                            </select>
                        </div>
                    </div>

                    <!-- Tasks List -->
                    <div class="d-flex flex-column gap-3" id="tasks-container">
                        @foreach ($task as $d)
                            <div class="task-card" 
                                 data-status="{{ $d->status }}" 
                                 data-upload="{{ $d->status_upload }}"
                                 data-name="{{ strtolower($d->nama) }}">
                                <div class="block block-rounded task-item">
                                    <div class="block-content">
                                        <div class="row align-items-center">
                                            <!-- Task Info -->
                                            <div class="col-lg-6">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0 me-3">
                                                        <div class="bg-primary-light rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                            <i class="fa fa-tasks fa-lg text-primary"></i>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h5 class="fs-base fw-bold mb-1">{{ $d->nama }}</h5>
                                                        <div class="d-flex align-items-center gap-2 mb-1">
                                                            @if($d->status == 'Draft')
                                                                <span class="badge bg-warning fs-xs">Draft</span>
                                                            @elseif($d->status == 'Selesai')
                                                                <span class="badge bg-primary fs-xs">Selesai</span>
                                                            @elseif($d->status == 'Disetujui')
                                                                <span class="badge bg-success fs-xs">Disetujui</span>
                                                            @elseif($d->status == 'Ditolak')
                                                                <span class="badge bg-danger fs-xs">Ditolak</span>
                                                            @elseif($d->status == 'Direvisi')
                                                                <span class="badge bg-warning fs-xs">Direvisi</span>
                                                            @endif
                                                            
                                                            @if($d->status_upload == 0)
                                                                <span class="badge bg-danger fs-xs">Belum Upload</span>
                                                            @else
                                                                <span class="badge bg-success fs-xs">Sudah Upload</span>
                                                            @endif
                                                        </div>
                                                        <div class="fs-sm text-muted">
                                                            <i class="fa fa-calendar me-1"></i>
                                                            @if($d->tgl_tempo)
                                                                Tempo: {{ \Carbon\Carbon::parse($d->tgl_tempo)->translatedFormat('d M Y') }}
                                                            @else
                                                                Belum ada tanggal tempo
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Task Details -->
                                            <div class="col-lg-4">
                                                <div class="row g-2">
                                                    @if($d->tgl_upload)
                                                        <div class="col-6">
                                                            <div class="p-2 bg-success-light rounded text-center">
                                                                <div class="fs-xs text-muted">Upload</div>
                                                                <div class="fs-sm fw-semibold">{{ \Carbon\Carbon::parse($d->tgl_upload)->translatedFormat('d M') }}</div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    
                                                    @if($d->status_upload == 1 && ($d->total_view || $d->total_share || $d->total_likes || $d->total_comments))
                                                        <div class="col-6">
                                                            <div class="p-2 bg-info-light rounded text-center">
                                                                <div class="fs-xs text-muted">Views</div>
                                                                <div class="fs-sm fw-bold text-primary">{{ number_format($d->total_view ?? 0, 0, ',', '.') }}</div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Action Buttons -->
                                            <div class="col-lg-2">
                                                <div class="d-flex gap-1 justify-content-end">
                                                    <button type="button" class="btn btn-sm btn-primary fs-xs" data-bs-toggle="modal" data-bs-target="#modalShow-{{$d->id}}" title="Lihat Detail">
                                                        <i class="fa fa-eye"></i>
                                                    </button>
                                                    @if($d->status_upload == 1 && ($d->total_view || $d->total_share || $d->total_likes || $d->total_comments))
                                                        <button type="button" class="btn btn-sm btn-outline-info fs-xs" data-bs-toggle="modal" data-bs-target="#engagementModal-{{$d->id}}" title="Engagement Report">
                                                            <i class="fa fa-chart-line"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Task Detail Modal -->
                                <x-task-detail id="modalShow-{{$d->id}}" :data="$d"/>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-4" id="pagination-container">
                        <div class="fs-base text-muted">
                            Menampilkan <span id="showing-count">{{ $task->count() }}</span> dari <span id="total-count">{{ $task->count() }}</span> tugas
                        </div>
                        <nav aria-label="Task pagination">
                            <ul class="pagination pagination-sm mb-0" id="task-pagination">
                                <!-- Pagination will be generated by JavaScript -->
                            </ul>
                        </nav>
                    </div>

                    <!-- Engagement Report Modals -->
                    @foreach ($task as $d)
                        @if($d->status_upload == 1 && ($d->total_view || $d->total_share || $d->total_likes || $d->total_comments))
                            <div class="modal fade" id="engagementModal-{{$d->id}}" tabindex="-1" aria-labelledby="engagementModalLabel-{{$d->id}}" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="block block-rounded shadow-none mb-0">
                                            <div class="block-header block-header-default">
                                                <h3 class="block-title">
                                                    <i class="fa fa-chart-line me-2"></i>
                                                    Report Engagement - {{ $d->nama }}
                                                </h3>
                                                <div class="block-options">
                                                    <button type="button" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">
                                                        <i class="fa fa-times"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="block-content p-4">
                                                <!-- Task Info -->
                                                <div class="row mb-4">
                                                    <div class="col-md-6">
                                                        <div class="p-3 bg-body-light rounded">
                                                            <div class="fs-xs text-muted mb-1">Nama Tugas</div>
                                                            <div class="fs-base fw-bold">{{ $d->nama }}</div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="p-3 bg-body-light rounded">
                                                            <div class="fs-xs text-muted mb-1">Tanggal Upload</div>
                                                            <div class="fs-base fw-semibold">
                                                                @if($d->tgl_upload)
                                                                    {{ \Carbon\Carbon::parse($d->tgl_upload)->translatedFormat('d F Y') }}
                                                                @else
                                                                    <span class="text-muted">-</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Engagement Metrics -->
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <div class="p-3 bg-primary-light rounded text-center">
                                                            <div class="fs-xs text-muted mb-1">Total Views</div>
                                                            <div class="fs-2 fw-bold text-primary">
                                                                {{ number_format($d->total_view ?? 0, 0, ',', '.') }}
                                                            </div>
                                                            <div class="fs-xs text-muted">
                                                                <i class="fa fa-eye me-1"></i>
                                                                Penayangan
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="p-3 bg-success-light rounded text-center">
                                                            <div class="fs-xs text-muted mb-1">Total Likes</div>
                                                            <div class="fs-2 fw-bold text-success">
                                                                {{ number_format($d->total_likes ?? 0, 0, ',', '.') }}
                                                            </div>
                                                            <div class="fs-xs text-muted">
                                                                <i class="fa fa-heart me-1"></i>
                                                                Suka
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="p-3 bg-info-light rounded text-center">
                                                            <div class="fs-xs text-muted mb-1">Total Comments</div>
                                                            <div class="fs-2 fw-bold text-info">
                                                                {{ number_format($d->total_comments ?? 0, 0, ',', '.') }}
                                                            </div>
                                                            <div class="fs-xs text-muted">
                                                                <i class="fa fa-comment me-1"></i>
                                                                Komentar
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="p-3 bg-warning-light rounded text-center">
                                                            <div class="fs-xs text-muted mb-1">Total Shares</div>
                                                            <div class="fs-2 fw-bold text-warning">
                                                                {{ number_format($d->total_share ?? 0, 0, ',', '.') }}
                                                            </div>
                                                            <div class="fs-xs text-muted">
                                                                <i class="fa fa-share me-1"></i>
                                                                Bagikan
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Bukti File -->
                                                @if(!empty($d->bukti))
                                                <div class="border-top pt-4 mt-4">
                                                    <div class="alert alert-info d-flex align-items-center" role="alert">
                                                        <i class="fa fa-file-alt fa-2x me-3"></i>
                                                        <div class="flex-grow-1">
                                                            <strong>File Bukti Tersedia</strong>
                                                            <p class="mb-2">File bukti screenshot engagement telah diupload untuk tugas ini.</p>
                                                            <div class="d-flex gap-2">
                                                                <button type="button" class="btn btn-sm btn-primary" onclick="viewBukti('{{ asset($d->bukti) }}')" title="Lihat File">
                                                                    <i class="fa fa-eye me-1"></i>
                                                                    Lihat File
                                                                </button>
                                                                <a href="{{ asset($d->bukti) }}" target="_blank" class="btn btn-sm btn-success" title="Download File">
                                                                    <i class="fa fa-download me-1"></i>
                                                                    Download
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif

                                                <!-- Engagement Summary -->
                                                @php
                                                    $totalEngagement = ($d->total_likes ?? 0) + ($d->total_comments ?? 0) + ($d->total_share ?? 0);
                                                    $engagementRate = ($d->total_view ?? 0) > 0 ? ($totalEngagement / $d->total_view) * 100 : 0;
                                                @endphp
                                                <div class="border-top pt-4 mt-4">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="p-3 bg-body rounded">
                                                                <div class="fs-base fw-bold mb-2">Total Engagement</div>
                                                                <div class="fs-1 fw-bold text-dark">{{ number_format($totalEngagement, 0, ',', '.') }}</div>
                                                                <div class="fs-xs text-muted">Likes + Comments + Shares</div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="p-3 bg-body rounded">
                                                                <div class="fs-base fw-bold mb-2">Engagement Rate</div>
                                                                <div class="fs-1 fw-bold text-dark">{{ number_format($engagementRate, 2) }}%</div>
                                                                <div class="fs-xs text-muted">Engagement / Views × 100</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                @else
                    <div class="text-center py-5">
                        <i class="fa fa-tasks fa-4x text-muted mb-4"></i>
                        <h4 class="fs-base fw-bold text-muted mb-2">Belum Ada Tugas</h4>
                        <p class="fs-base text-muted">Tugas akan ditambahkan oleh admin untuk project ini</p>
                    </div>
                @endif
            </div>
        </div>
    @endif

    @push('scripts')
    <script>
        $(document).ready(function() {
            const itemsPerPage = 8; // Show 8 cards per page in single column
            let currentPage = 1;
            let filteredTasks = [];
            let allTasks = [];

            // Initialize tasks array
            function initializeTasks() {
                allTasks = [];
                $('.task-card').each(function() {
                    allTasks.push({
                        element: $(this),
                        status: $(this).data('status'),
                        upload: $(this).data('upload'),
                        name: $(this).data('name')
                    });
                });
                filteredTasks = [...allTasks];
            }

            // Filter tasks based on search and filters
            function filterTasks() {
                const searchTerm = $('#task-search').val().toLowerCase();
                const statusFilter = $('#status-filter').val();
                const uploadFilter = $('#upload-filter').val();

                filteredTasks = allTasks.filter(task => {
                    let matchesSearch = searchTerm === '' || task.name.includes(searchTerm);
                    let matchesStatus = statusFilter === '' || task.status === statusFilter;
                    let matchesUpload = uploadFilter === '' || task.upload.toString() === uploadFilter;
                    
                    return matchesSearch && matchesStatus && matchesUpload;
                });

                currentPage = 1;
                updateDisplay();
            }

            // Update the display with current page and filters
            function updateDisplay() {
                // Hide all tasks first
                $('.task-card').hide();

                // Calculate pagination
                const totalItems = filteredTasks.length;
                const totalPages = Math.ceil(totalItems / itemsPerPage);
                const startIndex = (currentPage - 1) * itemsPerPage;
                const endIndex = Math.min(startIndex + itemsPerPage, totalItems);

                // Show tasks for current page
                for (let i = startIndex; i < endIndex; i++) {
                    if (filteredTasks[i]) {
                        filteredTasks[i].element.show();
                    }
                }

                // Update pagination info
                $('#showing-count').text(endIndex - startIndex);
                $('#total-count').text(totalItems);

                // Update pagination controls
                updatePagination(totalPages);

                // Show no results message if needed
                if (totalItems === 0) {
                    if ($('#no-results').length === 0) {
                        $('#tasks-container').after(`
                            <div id="no-results" class="text-center py-5">
                                <i class="fa fa-search fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Tidak ada tugas yang ditemukan</h5>
                                <p class="text-muted">Coba ubah kata kunci pencarian atau filter</p>
                            </div>
                        `);
                    }
                    $('#no-results').show();
                    $('#pagination-container').hide();
                } else {
                    $('#no-results').hide();
                    $('#pagination-container').show();
                }
            }

            // Update pagination controls
            function updatePagination(totalPages) {
                const pagination = $('#task-pagination');
                pagination.empty();

                if (totalPages <= 1) {
                    return;
                }

                // Previous button
                const prevDisabled = currentPage === 1 ? 'disabled' : '';
                pagination.append(`
                    <li class="page-item ${prevDisabled}">
                        <a class="page-link" href="#" data-page="${currentPage - 1}">
                            <i class="fa fa-chevron-left"></i>
                        </a>
                    </li>
                `);

                // Page numbers
                const startPage = Math.max(1, currentPage - 2);
                const endPage = Math.min(totalPages, currentPage + 2);

                if (startPage > 1) {
                    pagination.append(`
                        <li class="page-item">
                            <a class="page-link" href="#" data-page="1">1</a>
                        </li>
                    `);
                    if (startPage > 2) {
                        pagination.append(`<li class="page-item disabled"><span class="page-link">...</span></li>`);
                    }
                }

                for (let i = startPage; i <= endPage; i++) {
                    const active = i === currentPage ? 'active' : '';
                    pagination.append(`
                        <li class="page-item ${active}">
                            <a class="page-link" href="#" data-page="${i}">${i}</a>
                        </li>
                    `);
                }

                if (endPage < totalPages) {
                    if (endPage < totalPages - 1) {
                        pagination.append(`<li class="page-item disabled"><span class="page-link">...</span></li>`);
                    }
                    pagination.append(`
                        <li class="page-item">
                            <a class="page-link" href="#" data-page="${totalPages}">${totalPages}</a>
                        </li>
                    `);
                }

                // Next button
                const nextDisabled = currentPage === totalPages ? 'disabled' : '';
                pagination.append(`
                    <li class="page-item ${nextDisabled}">
                        <a class="page-link" href="#" data-page="${currentPage + 1}">
                            <i class="fa fa-chevron-right"></i>
                        </a>
                    </li>
                `);
            }

            // Event handlers
            $('#task-search').on('input', debounce(filterTasks, 300));
            $('#status-filter, #upload-filter').on('change', filterTasks);

            // Pagination click handler
            $(document).on('click', '#task-pagination .page-link', function(e) {
                e.preventDefault();
                const page = parseInt($(this).data('page'));
                if (page && page !== currentPage) {
                    currentPage = page;
                    updateDisplay();
                    
                    // Scroll to top of tasks section
                    $('html, body').animate({
                        scrollTop: $('#tasks-container').offset().top - 100
                    }, 500);
                }
            });

            // Debounce function for search
            function debounce(func, wait) {
                let timeout;
                return function executedFunction(...args) {
                    const later = () => {
                        clearTimeout(timeout);
                        func(...args);
                    };
                    clearTimeout(timeout);
                    timeout = setTimeout(later, wait);
                };
            }

            // Initialize on page load
            initializeTasks();
            updateDisplay();
        });

        // Function to view bukti file
        function viewBukti(fileUrl) {
            // Check if file is image or PDF
            const extension = fileUrl.split('.').pop().toLowerCase();
            
            if (['jpg', 'jpeg', 'png', 'gif'].includes(extension)) {
                // Show image in modal
                const modal = $(`
                    <div class="modal fade" id="buktiModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">File Bukti Screenshot</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body text-center">
                                    <img src="${fileUrl}" class="img-fluid" alt="Bukti Screenshot" style="max-height: 70vh;">
                                </div>
                                <div class="modal-footer">
                                    <a href="${fileUrl}" target="_blank" class="btn btn-primary">
                                        <i class="fa fa-external-link-alt me-1"></i>
                                        Buka di Tab Baru
                                    </a>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>
                `);
                
                // Remove existing modal if any
                $('#buktiModal').remove();
                
                // Add modal to body and show
                $('body').append(modal);
                $('#buktiModal').modal('show');
                
                // Clean up when modal is hidden
                $('#buktiModal').on('hidden.bs.modal', function () {
                    $(this).remove();
                });
            } else {
                // For PDF or other files, open in new tab
                window.open(fileUrl, '_blank');
            }
        }
    </script>
    @endpush
</x-user-layout>