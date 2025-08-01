@props(['data'])

<!-- Main Task Info -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-start mb-3">
            <div>
                <h5 class="mb-1">{{ $data->nama }}</h5>
                <div class="d-flex gap-2 mb-2">
                    @if($data->status == 'Draft')
                        <span class="badge bg-warning">Draft</span>
                    @elseif($data->status == 'Pending')
                        <span class="badge bg-info">Pending</span>
                    @elseif($data->status == 'Selesai')
                        <span class="badge bg-primary">Selesai</span>
                    @elseif($data->status == 'Disetujui')
                        <span class="badge bg-success">Disetujui</span>
                    @elseif($data->status == 'Ditolak')
                        <span class="badge bg-danger">Ditolak</span>
                    @elseif($d->status == 'Direvisi')
                        <span class="badge bg-warning fs-xs">Direvisi</span>
                    @endif
                    
                    @if($data->status_upload == 0)
                        <span class="badge bg-secondary">Belum Upload</span>
                    @else
                        <span class="badge bg-success">File Uploaded</span>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="row g-3">
            <div class="col-md-6">
                <small class="text-muted">Deadline</small>
                <div class="fw-medium">
                    @if($data->tgl_tempo)
                        {{ \Carbon\Carbon::parse($data->tgl_tempo)->translatedFormat('d M Y') }}
                    @else
                        <span class="text-muted">Belum ditentukan</span>
                    @endif
                </div>
            </div>
            @if($data->tgl_upload)
            <div class="col-md-6">
                <small class="text-muted">Upload Date</small>
                <div class="fw-medium">{{ \Carbon\Carbon::parse($data->tgl_upload)->translatedFormat('d M Y') }}</div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Files and Links -->
@if($data->link_brief || $data->file)
<div class="border-top pt-3 mb-4">
    <h6 class="mb-3">File & Links</h6>
    <div class="d-flex gap-2 flex-wrap">
        @if($data->link_brief)
            <a href="{{ $data->link_brief }}" target="_blank" class="btn btn-outline-primary btn-sm">
                <i class="fa fa-link me-1"></i> Brief
            </a>
        @endif
        @if($data->file)
            <a href="{{ $data->file }}" target="_blank" class="btn btn-outline-success btn-sm">
                <i class="fa fa-download me-1"></i> Download File
            </a>
        @endif
    </div>
</div>
@endif

<!-- Engagement Data -->
@if($data->status_upload == 1 && ($data->total_view || $data->total_share || $data->total_likes || $data->total_comments))
<div class="border-top pt-3 mb-4">
    <h6 class="mb-3">Engagement</h6>
    <div class="row g-2">
        @if($data->total_view)
        <div class="col-6 col-md-3">
            <div class="text-center p-2 bg-light rounded">
                <div class="fw-bold">{{ number_format($data->total_view, 0, ',', '.') }}</div>
                <small class="text-muted">Views</small>
            </div>
        </div>
        @endif
        @if($data->total_likes)
        <div class="col-6 col-md-3">
            <div class="text-center p-2 bg-light rounded">
                <div class="fw-bold">{{ number_format($data->total_likes, 0, ',', '.') }}</div>
                <small class="text-muted">Likes</small>
            </div>
        </div>
        @endif
        @if($data->total_comments)
        <div class="col-6 col-md-3">
            <div class="text-center p-2 bg-light rounded">
                <div class="fw-bold">{{ number_format($data->total_comments, 0, ',', '.') }}</div>
                <small class="text-muted">Comments</small>
            </div>
        </div>
        @endif
        @if($data->total_share)
        <div class="col-6 col-md-3">
            <div class="text-center p-2 bg-light rounded">
                <div class="fw-bold">{{ number_format($data->total_share, 0, ',', '.') }}</div>
                <small class="text-muted">Shares</small>
            </div>
        </div>
        @endif
    </div>
    
    @php
        $totalEngagement = ($data->total_likes ?? 0) + ($data->total_comments ?? 0) + ($data->total_share ?? 0);
        $engagementRate = ($data->total_view ?? 0) > 0 ? ($totalEngagement / $data->total_view) * 100 : 0;
    @endphp
    @if($totalEngagement > 0)
    <div class="row mt-3">
        <div class="col-md-6">
            <div class="text-center p-2 border rounded">
                <div class="fw-bold">{{ number_format($totalEngagement, 0, ',', '.') }}</div>
                <small class="text-muted">Total Engagement</small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="text-center p-2 border rounded">
                <div class="fw-bold">{{ number_format($engagementRate, 1) }}%</div>
                <small class="text-muted">Engagement Rate</small>
            </div>
        </div>
    </div>
    @endif
</div>
@endif

<!-- Notes -->
@if($data->catatan)
<div class="border-top pt-3 mb-4">
    <h6 class="mb-3">Catatan</h6>
    <div class="p-3 bg-light rounded">
        {{ $data->catatan }}
    </div>
</div>
@endif