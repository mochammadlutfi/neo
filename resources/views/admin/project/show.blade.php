<x-app-layout>
    <div class="content">
        <!-- Page Header -->
        <div class="block block-rounded">
            <div class="block-header">
                <h3 class="block-title fs-base fw-bold">
                    <i class="fa fa-briefcase me-2 text-primary"></i>
                    Detail Project: {{ $data->nama }}
                </h3>
                <div class="block-options">
                    <a href="{{ route('admin.project.edit', $data->id) }}" class="btn btn-sm btn-warning fs-base me-2">
                        <i class="fa fa-edit me-1"></i>
                        Edit
                    </a>
                    <a href="{{ route('admin.project.calendar', $data->id) }}" class="btn btn-sm btn-info fs-base me-2">
                        <i class="fa fa-calendar me-1"></i>
                        Kalender
                    </a>
                    <a href="{{ route('admin.project.index') }}" class="btn btn-sm btn-secondary fs-base">
                        <i class="fa fa-arrow-left me-1"></i>
                        Kembali
                    </a>
                </div>
            </div>
        </div>

        <!-- Project Information -->
        <div class="block block-rounded">
            <div class="block-header bg-body-light">
                <h3 class="block-title fs-base fw-bold">
                    <i class="fa fa-info-circle me-2"></i>
                    Informasi Project
                </h3>
            </div>
            <div class="block-content p-4">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="p-3 bg-body-light rounded">
                            <div class="fs-xs text-muted mb-1">Nama Project</div>
                            <div class="fs-base fw-bold">{{ $data->nama }}</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 bg-body-light rounded">
                            <div class="fs-xs text-muted mb-1">Konsumen</div>
                            <div class="fs-base fw-bold text-primary">{{ $data->user->nama }}</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 bg-body-light rounded">
                            <div class="fs-xs text-muted mb-1">No Pesanan</div>
                            <div class="fs-base fw-semibold">{{ $data->order->nomor }}</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 bg-body-light rounded">
                            <div class="fs-xs text-muted mb-1">Status Pembayaran</div>
                            <div>
                                @if($data->order->status_pembayaran == 'Belum Bayar')
                                    <span class="badge bg-danger fs-xs">Belum Bayar</span>
                                @elseif ($data->order->status_pembayaran == 'Sebagian')
                                    <span class="badge bg-warning fs-xs">Sebagian</span>
                                @else
                                    <span class="badge bg-success fs-xs">Lunas</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 bg-body-light rounded">
                            <div class="fs-xs text-muted mb-1">Paket Layanan</div>
                            <div class="fs-base fw-semibold">{{ $data->order->paket->nama }}</div>
                        </div>
                    </div>
                    <div class="col-md-4">
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
        
        <div class="block block-rounded">
            <div class="block-header bg-body-light">
                <h3 class="block-title fs-base fw-bold">
                    <i class="fa fa-chart-line me-2"></i>
                    Statistik Tugas
                </h3>
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
                    <div class="col-3">
                        <div class="p-2 bg-body rounded text-center">
                            <div class="fs-xs text-muted">Total Tugas</div>
                            <div class="fs-2 fw-bold text-primary">{{ $totalTasks }}</div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="p-2 bg-body rounded text-center">
                            <div class="fs-xs text-muted">Selesai</div>
                            <div class="fs-2 fw-bold text-success">{{ $completedTasks }}</div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="p-2 bg-body rounded text-center">
                            <div class="fs-xs text-muted">Pending</div>
                            <div class="fs-2 fw-bold text-warning">{{ $pendingTasks }}</div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="p-2 bg-body rounded text-center">
                            <div class="fs-xs text-muted">Ditolak</div>
                            <div class="fs-2 fw-bold text-danger">{{ $rejectedTasks }}</div>
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
            </div>
        </div>

        <!-- Tasks Management -->
        <div class="block block-rounded">
            <div class="block-header">
                <h3 class="block-title fs-base fw-bold">
                    <i class="fa fa-tasks me-2 text-primary"></i>
                    Manajemen Tugas
                </h3>
                <div class="block-options">
                    <button class="btn btn-sm btn-primary fs-base" onclick="openModal()">
                        <i class="fa fa-plus me-1"></i>
                        Tambah Tugas
                    </button>
                </div>
            </div>
            <div class="block-content p-4">
                <!-- Filter and Search -->
                <div class="row mb-4">
                    <div class="col-md-4">
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
                    <div class="col-md-2">
                        <button type="button" class="btn btn-outline-secondary w-100 fs-base" id="reset-filters">
                            <i class="fa fa-refresh me-1"></i>
                            Reset
                        </button>
                    </div>
                </div>

                <!-- Tasks List -->
                <div class="d-flex flex-column gap-3" id="tasks-container">
                    @foreach ($task as $d)
                        <div class="task-card border rounded" 
                             data-status="{{ $d->status }}" 
                             data-upload="{{ $d->status_upload }}"
                             data-name="{{ strtolower($d->nama) }}">
                            <div class="block block-rounded task-item">
                                <div class="block-content">
                                    <div class="row align-items-center">
                                        <!-- Task Info -->
                                        <div class="col-lg-4">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="bg-body rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
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
                                                        @endif
                                                        
                                                        @if($d->status_upload == 0)
                                                            <span class="badge bg-danger fs-xs">Belum Upload</span>
                                                        @else
                                                            <span class="badge bg-success fs-xs">Sudah Upload</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Task Details -->
                                        <div class="col-lg-4">
                                            <div class="row g-2">
                                                @if($d->tgl_tempo)
                                                    <div class="col-6">
                                                        <div class="p-2 bg-warning-light rounded text-center">
                                                            <div class="fs-xs text-muted">Deadline</div>
                                                            <div class="fs-sm fw-semibold">{{ \Carbon\Carbon::parse($d->tgl_tempo)->translatedFormat('d M') }}</div>
                                                        </div>
                                                    </div>
                                                @endif
                                                
                                                @if($d->tgl_upload)
                                                    <div class="col-6">
                                                        <div class="p-2 bg-success-light rounded text-center">
                                                            <div class="fs-xs text-muted">Upload</div>
                                                            <div class="fs-sm fw-semibold">{{ \Carbon\Carbon::parse($d->tgl_upload)->translatedFormat('d M') }}</div>
                                                        </div>
                                                    </div>
                                                @endif
                                                
                                                @if($d->status_upload == 1 && ($d->total_view || $d->total_share || $d->total_likes || $d->total_comments))
                                                    <div class="col-12">
                                                        <div class="p-2 bg-info-light rounded text-center">
                                                            <div class="fs-xs text-muted">Engagement</div>
                                                            <div class="fs-sm fw-bold text-primary">{{ number_format($d->total_view ?? 0, 0, ',', '.') }} views</div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Action Buttons -->
                                        <div class="col-lg-4">
                                            <div class="d-flex gap-1 justify-content-end">
                                                <button type="button" class="btn btn-sm btn-primary fs-xs" onclick="detail({{ $d->id }})" title="Lihat Detail">
                                                    <i class="fa fa-eye"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-warning fs-xs" onclick="edit({{ $d->id }})" title="Edit Tugas">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                                @if($d->status_upload == 1)
                                                    <button type="button" class="btn btn-sm btn-info fs-xs" onclick="openEngagementModal({{ $d->id }}, '{{ $d->nama }}')" title="Input Engagement">
                                                        <i class="fa fa-chart-line"></i>
                                                    </button>
                                                @endif
                                                <button type="button" class="btn btn-sm btn-danger fs-xs" onclick="hapus({{ $d->id }})" title="Hapus Tugas">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
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

                <!-- No Results -->
                <div id="no-results" class="text-center py-5" style="display: none;">
                    <i class="fa fa-search fa-4x text-muted mb-4"></i>
                    <h4 class="fs-base fw-bold text-muted mb-2">Tidak ada tugas yang ditemukan</h4>
                    <p class="fs-base text-muted">Coba ubah kata kunci pencarian atau filter</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Task Form Modal -->
    <div class="modal fade" id="modal-form" tabindex="-1" aria-labelledby="modalFormTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="formData" onsubmit="return false;" enctype="multipart/form-data">
                    <div class="block block-rounded shadow-none mb-0">
                        <input type="hidden" name="project_id" value="{{ $data->id }}"/>
                        <input type="hidden" id="field-id" name="id" value=""/>
                        
                        <!-- Modal Header -->
                        <div class="block-header block-header-default">
                            <h3 class="block-title">
                                <i class="fa fa-tasks me-2 text-primary"></i>
                                <span id="modalFormTitle">Tambah Tugas</span>
                            </h3>
                            <div class="block-options">
                                <button type="button" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">
                                    <i class="fa fa-times"></i>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Modal Body -->
                        <div class="block-content p-4">
                            <!-- Notes Section (for edit mode) -->
                            <div id="note-wrap" class="mb-4" style="display: none;">
                                <div class="p-3 bg-warning-light rounded">
                                    <h6 class="mb-2">
                                        <i class="fa fa-sticky-note me-1"></i>
                                        Catatan
                                    </h6>
                                    <p id="note-val" class="mb-0"></p>
                                </div>
                            </div>

                            <!-- Form Fields -->
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-medium" for="field-nama">
                                            <i class="fa fa-tag me-1 text-primary"></i>
                                            Nama Tugas <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control" id="field-nama" name="nama" placeholder="Masukkan nama tugas">
                                        <div class="invalid-feedback" id="error-nama"></div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label fw-medium" for="field-tgl_tempo">
                                            <i class="fa fa-calendar me-1 text-warning"></i>
                                            Tanggal Deadline <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control" id="field-tgl_tempo" name="tgl_tempo" placeholder="Pilih tanggal deadline">
                                        <div class="invalid-feedback" id="error-tgl_tempo"></div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label fw-medium" for="field-link_brief">
                                            <i class="fa fa-link me-1 text-info"></i>
                                            Link Brief <span class="text-danger">*</span>
                                        </label>
                                        <input type="url" class="form-control" id="field-link_brief" name="link_brief" placeholder="https://example.com">
                                        <div class="invalid-feedback" id="error-link_brief"></div>
                                        <small class="text-muted">Link brief dokumen atau referensi tugas</small>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-medium" for="field-tgl_upload">
                                            <i class="fa fa-clock me-1 text-success"></i>
                                            Tanggal Upload <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control" id="field-tgl_upload" name="tgl_upload" placeholder="Pilih tanggal dan waktu upload">
                                        <div class="invalid-feedback" id="error-tgl_upload"></div>
                                        <small class="text-muted">Target waktu pengumpulan tugas</small>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label fw-medium" for="field-status_upload">
                                            <i class="fa fa-upload me-1 text-primary"></i>
                                            Status Upload
                                        </label>
                                        <select class="form-select" id="field-status_upload" name="status_upload">
                                            <option value="0">Belum Upload</option>
                                            <option value="1">Sudah Upload</option>
                                        </select>
                                        <div class="invalid-feedback" id="error-status_upload"></div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label fw-medium" for="field-file">
                                            <i class="fa fa-file me-1 text-secondary"></i>
                                            Upload File (Opsional)
                                        </label>
                                        <input class="form-control" type="file" name="file" id="field-file">
                                        <div class="invalid-feedback" id="error-file"></div>
                                        <small class="text-muted">Format: PDF, DOC, DOCX, PNG, JPG (Max: 10MB)</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Modal Footer -->
                        <div class="modal-footer bg-body-light">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="fa fa-times me-1"></i>
                                Batal
                            </button>
                            <button type="submit" class="btn btn-primary" id="btn-simpan">
                                <i class="fa fa-save me-1"></i>
                                Simpan Tugas
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Task Detail Modals -->
    @foreach ($task as $d)
        <div class="modal fade" id="modalShow-{{$d->id}}" tabindex="-1" aria-labelledby="modalShow-{{$d->id}}Label" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="block block-rounded shadow-none mb-0">
                        <!-- Modal Header -->
                        <div class="block-header block-header-default">
                            <h3 class="block-title">
                                <i class="fa fa-tasks me-2 text-primary"></i>
                                Detail Tugas: {{ $d->nama }}
                            </h3>
                            <div class="block-options">
                                <button type="button" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">
                                    <i class="fa fa-times"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Modal Content -->
                        <div class="block-content p-4">
                            <x-admin-task-detail-content :data="$d"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    
    <!-- Engagement Report Modal -->
    <div class="modal fade" id="engagementModal" tabindex="-1" aria-labelledby="engagementModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="engagementForm" onsubmit="return false;">
                    <div class="block block-rounded shadow-none mb-0">
                        <div class="block-header block-header-default">
                            <h3 class="block-title">
                                <i class="fa fa-chart-line me-2"></i>
                                Input Report Engagement - <span id="engagement-task-name"></span>
                            </h3>
                            <div class="block-options">
                                <button type="button" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">
                                    <i class="fa fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="block-content p-4">
                            <input type="hidden" id="engagement-task-id" name="task_id">
                            
                            <!-- Engagement Metrics Input -->
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <div class="p-3 border rounded">
                                        <label class="form-label fs-base fw-bold" for="total_view">
                                            <i class="fa fa-eye me-1 text-primary"></i>
                                            Total Views
                                        </label>
                                        <input type="number" class="form-control fs-base" id="total_view" name="total_view" min="0" placeholder="Masukkan jumlah views">
                                        <div class="invalid-feedback" id="error-total_view"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 border rounded">
                                        <label class="form-label fs-base fw-bold" for="total_likes">
                                            <i class="fa fa-heart me-1 text-success"></i>
                                            Total Likes
                                        </label>
                                        <input type="number" class="form-control fs-base" id="total_likes" name="total_likes" min="0" placeholder="Masukkan jumlah likes">
                                        <div class="invalid-feedback" id="error-total_likes"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 border rounded">
                                        <label class="form-label fs-base fw-bold" for="total_comments">
                                            <i class="fa fa-comment me-1 text-info"></i>
                                            Total Comments
                                        </label>
                                        <input type="number" class="form-control fs-base" id="total_comments" name="total_comments" min="0" placeholder="Masukkan jumlah comments">
                                        <div class="invalid-feedback" id="error-total_comments"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 border rounded">
                                        <label class="form-label fs-base fw-bold" for="total_share">
                                            <i class="fa fa-share me-1 text-warning"></i>
                                            Total Shares
                                        </label>
                                        <input type="number" class="form-control fs-base" id="total_share" name="total_share" min="0" placeholder="Masukkan jumlah shares">
                                        <div class="invalid-feedback" id="error-total_share"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Engagement Summary Preview -->
                            <div class="border-top pt-4 mt-4" id="engagement-preview" style="display: none;">
                                <h5 class="fs-base fw-bold mb-3">
                                    <i class="fa fa-chart-bar me-1"></i>
                                    Preview Engagement
                                </h5>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="p-3 bg-body rounded text-center">
                                            <div class="fs-base fw-bold">Total Engagement</div>
                                            <div class="fs-2 fw-bold text-primary" id="preview-total-engagement">0</div>
                                            <div class="fs-xs text-muted">Likes + Comments + Shares</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-3 bg-body rounded text-center">
                                            <div class="fs-base fw-bold">Engagement Rate</div>
                                            <div class="fs-2 fw-bold text-success" id="preview-engagement-rate">0%</div>
                                            <div class="fs-xs text-muted">Total Engagement / Views × 100</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer bg-body-light">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="fa fa-times me-1"></i>
                                Batal
                            </button>
                            <button type="submit" class="btn btn-primary" id="btn-save-engagement">
                                <i class="fa fa-save me-1"></i>
                                Simpan Report
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    @push('scripts')
    <script>
        $(document).ready(function() {
            const itemsPerPage = 8;
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
            $('#reset-filters').on('click', function() {
                $('#task-search').val('');
                $('#status-filter').val('');
                $('#upload-filter').val('');
                filteredTasks = [...allTasks];
                currentPage = 1;
                updateDisplay();
            });

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

            // Function to reload tasks from server
            window.reloadTasks = function() {
                // Reload the page to get fresh data from server
                location.reload();
            };

            // Engagement Modal functionality
            $('#total_view, #total_likes, #total_comments, #total_share').on('input', function() {
                updateEngagementPreview();
            });

            // Handle engagement form submission
            $('#engagementForm').on('submit', function(e) {
                e.preventDefault();
                
                const submitBtn = $('#btn-save-engagement');
                const originalText = submitBtn.html();
                
                // Show loading state
                submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-1"></i> Menyimpan...');
                
                // Clear previous errors
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').empty();
                
                // Create FormData
                const formData = new FormData(this);
                formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
                
                $.ajax({
                    url: `/admin/project/task/${formData.get('task_id')}/engagement`,
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.fail == false) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: 'Data engagement berhasil disimpan.',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                $('#engagementModal').modal('hide');
                                reloadTasks(); // Reload to show updated engagement data
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: 'Terjadi kesalahan saat menyimpan data.',
                                confirmButtonText: 'OK'
                            });
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422 && xhr.responseJSON.errors) {
                            const errors = xhr.responseJSON.errors;
                            $.each(errors, function(field, messages) {
                                $('#' + field).addClass('is-invalid');
                                $('#error-' + field).text(messages[0]);
                            });
                        } else if (xhr.responseJSON && xhr.responseJSON.fail == true) {
                            // Handle backend validation errors
                            if (xhr.responseJSON.errors) {
                                const errors = xhr.responseJSON.errors;
                                $.each(errors, function(field, messages) {
                                    $('#' + field).addClass('is-invalid');
                                    $('#error-' + field).text(messages[0]);
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: xhr.responseJSON.pesan || 'Terjadi kesalahan saat menyimpan data.',
                                    confirmButtonText: 'OK'
                                });
                            }
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'Terjadi kesalahan server. Silakan coba lagi.',
                                confirmButtonText: 'OK'
                            });
                        }
                    },
                    complete: function() {
                        submitBtn.prop('disabled', false).html(originalText);
                    }
                });
            });

            // Clear form when modal is hidden
            $('#engagementModal').on('hidden.bs.modal', function() {
                $('#engagementForm')[0].reset();
                $('#engagement-preview').hide();
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').empty();
            });
        });

        // Global function for engagement preview
        function updateEngagementPreview() {
            const views = parseInt($('#total_view').val()) || 0;
            const likes = parseInt($('#total_likes').val()) || 0;
            const comments = parseInt($('#total_comments').val()) || 0;
            const shares = parseInt($('#total_share').val()) || 0;

            const totalEngagement = likes + comments + shares;
            const engagementRate = views > 0 ? (totalEngagement / views) * 100 : 0;

            $('#preview-total-engagement').text(totalEngagement.toLocaleString());
            $('#preview-engagement-rate').text(engagementRate.toFixed(2) + '%');

            if (views > 0 || likes > 0 || comments > 0 || shares > 0) {
                $('#engagement-preview').show();
            } else {
                $('#engagement-preview').hide();
            }
        }

        function openModal(){
            // Clear form
            $('#formData')[0].reset();
            $('#field-id').val('');
            $('#note-wrap').hide();
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').empty();
            
            // Set title for add mode
            $("#modalFormTitle").html('Tambah Tugas');
            
            // Show modal
            var modalForm = bootstrap.Modal.getOrCreateInstance(document.getElementById('modal-form'));
            modalForm.show();
        }

        function openEngagementModal(taskId, taskName) {
            // Set task data
            $('#engagement-task-id').val(taskId);
            $('#engagement-task-name').text(taskName);
            
            // Load existing engagement data  
            $.ajax({
                url: `/admin/project/task/${taskId}/engagement`,
                type: 'GET',
                success: function(response) {
                    if (response.fail == false && response.data) {
                        $('#total_view').val(response.data.total_view || '');
                        $('#total_likes').val(response.data.total_likes || '');
                        $('#total_comments').val(response.data.total_comments || '');
                        $('#total_share').val(response.data.total_share || '');
                        updateEngagementPreview();
                    }
                },
                error: function() {
                    // If error, just continue with empty form
                }
            });
            
            // Show modal
            const engagementModal = new bootstrap.Modal(document.getElementById('engagementModal'));
            engagementModal.show();
        }

        function detail(id){
            // Use the component-based modal instead of AJAX loading
            var modalEl = document.getElementById('modalShow-' + id);
            if (modalEl) {
                var myModal = bootstrap.Modal.getOrCreateInstance(modalEl);
                myModal.show();
            }
        }



        function edit(id){
            // Clear form first
            $('#formData')[0].reset();
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').empty();
            
            $.ajax({
                url: `/admin/task/${id}/edit`,
                type: "GET",
                dataType: "json",
                success: function (response) {
                    // Populate form fields
                    $('#field-id').val(response.id);
                    $('#field-nama').val(response.nama);
                    $('#field-tgl_tempo').val(response.tgl_tempo);
                    $('#field-link_brief').val(response.link_brief);
                    $('#field-tgl_upload').val(response.tgl_upload);
                    $('#field-status_upload').val(response.status_upload);
                    
                    // Set modal title
                    $("#modalFormTitle").html('Ubah Tugas');

                    // Show notes if available
                    if(response.catatan) {
                        $("#note-wrap").show();
                        $("#note-val").html(response.catatan);
                    } else {
                        $("#note-wrap").hide();
                    }

                    // Show modal
                    var myModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('modal-form'));
                    myModal.show();
                },
                error: function (error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Gagal memuat data tugas. Silakan coba lagi.',
                        confirmButtonText: 'OK'
                    });
                }
            });
        }

        $("#field-tgl_tempo").flatpickr({
            altInput: true,
            altFormat: "j F Y",
            dateFormat: "Y-m-d",
            locale : "id",
            defaultDate : new Date(),
            minDate: "today"
        });

        

        $("#field-tgl_upload").flatpickr({
            altInput: true,
            altFormat: "j F Y H:i",
            dateFormat: "Y-m-d H:i",
            locale : "id",
            defaultDate : new Date(),
            minDate: "today",
            enableTime : true
        });

        function modalShow(id){
            $.ajax({
                url: "/admin/pembayaran/"+id,
                type: "GET",
                dataType: "html",
                success: function (response) {
                    var el = document.getElementById('modal-show');
                    $("#detailPembayaran").html(response);
                    var myModal = bootstrap.Modal.getOrCreateInstance(el);
                    myModal.show();
                },
                error: function (error) {
                }

            });
        }

        function updateStatus(id, status, booking_id){
            // console.log(status);
            $.ajax({
                url: "/admin/pembayaran/"+id +"/status",
                type: "POST",
                data : {
                    booking_id : booking_id,
                    status : status,
                    _token : $("meta[name='csrf-token']").attr("content"),
                },
                success: function (response) {
                    // console.log(response);
                    location.reload();
                    var el = document.getElementById('modal-show');
                    updateDisplay();
                    // $("#detailPembayaran").html(response);
                    var myModal = bootstrap.Modal.getOrCreateInstance(el);
                    myModal.hide();
                },
                error: function (error) {
                }
            });
        }

        $("#formData").on("submit",function (e) {
            e.preventDefault();
            var fomr = $('form#formData')[0];
            var formData = new FormData(fomr);
            let token   = $("meta[name='csrf-token']").attr("content");
            formData.append('_token', token);

            let id = $('#field-id').val();

            let url = (id) ? `/admin/task/${id}/update` : `/admin/task/simpan`;

            $.ajax({
                url: url,
                type: "POST",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function (response) {
                    if (response.fail == false) {
                        var myModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('modal-form'));
                        myModal.hide();
                        
                        // Show success message and reload tasks
                        Swal.fire({
                            toast: true,
                            title: "Berhasil",
                            text: id ? "Task berhasil diupdate!" : "Task berhasil ditambahkan!",
                            timer: 1500,
                            showConfirmButton: false,
                            icon: 'success',
                            position: 'top-end'
                        }).then(() => {
                            reloadTasks();
                        });
                    } else {
                        for (control in response.errors) {
                            $('#field-' + control).addClass('is-invalid');
                            $('#error-' + control).html(response.errors[control]);
                        }
                    }
                },
                error: function (error) {
                }

            });

        });

        
        function hapus(id){
                Swal.fire({
                    icon : 'warning',
                    text: 'Hapus Data?',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: `Tidak, Jangan!`,
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "/admin/task/"+ id +"/delete",
                            type: "DELETE",
                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            success: function(data) {
                                if(data.fail == false){
                                    Swal.fire({
                                        toast : true,
                                        title: "Berhasil",
                                        text: "Data Berhasil Dihapus!",
                                        timer: 1500,
                                        showConfirmButton: false,
                                        icon: 'success',
                                        position : 'top-end'
                                    }).then((result) => {
                                        reloadTasks();
                                    });
                                }else{
                                    Swal.fire({
                                        toast : true,
                                        title: "Gagal",
                                        text: "Data Gagal Dihapus!",
                                        timer: 1500,
                                        showConfirmButton: false,
                                        icon: 'error',
                                        position : 'top-end'
                                    });
                                }
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                    Swal.fire({
                                        toast : true,
                                        title: "Gagal",
                                        text: "Terjadi Kesalahan Di Server!",
                                        timer: 1500,
                                        showConfirmButton: false,
                                        icon: 'error',
                                        position : 'top-end'
                                    });
                            }
                        });
                    }
                })
            }
    </script>
    @endpush

</x-app-layout>

