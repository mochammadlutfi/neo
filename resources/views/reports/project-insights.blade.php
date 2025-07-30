<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Insights - {{ $project->nama }}</title>
    <style>
        * {
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #2c3e50;
            margin: 0;
            padding: 15px;
            background: #ffffff;
        }
        
        /* Header Section */
        .header {
            background: #4a69bd;
            color: white;
            padding: 20px;
            margin-bottom: 25px;
            border-radius: 8px;
            overflow: hidden;
            position: relative;
        }
        
        .header-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .header-table td {
            vertical-align: middle;
            padding: 5px;
        }
        
        .company-section {
            width: 60%;
        }
        
        .logo-placeholder {
            width: 50px;
            height: 50px;
            background: #ffffff;
            border-radius: 6px;
            display: inline-block;
            text-align: center;
            line-height: 50px;
            font-weight: bold;
            color: #4a69bd;
            font-size: 18px;
            margin-right: 15px;
            vertical-align: middle;
        }
        
        .company-details {
            display: inline-block;
            vertical-align: middle;
        }
        
        .company-name {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 3px;
        }
        
        .company-tagline {
            font-size: 13px;
            opacity: 0.9;
        }
        
        .report-section {
            width: 40%;
            text-align: right;
        }
        
        .report-title {
            color:white;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 6px;
        }
        
        .report-subtitle {
            font-size: 14px;
            color:white;
            margin-bottom: 8px;
            opacity: 0.9;
        }
        
        .report-date {
            color:white;
            font-size: 12px;
            padding: 6px 12px;
            border-radius: 15px;
            display: inline-block;
        }
        
        /* Project Info Card */
        .project-card {
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-left: 6px solid #4a69bd;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
        }
        
        .project-title {
            font-size: 18px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #dee2e6;
        }
        
        .info-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .info-table td {
            width: 25%;
            padding: 10px;
            vertical-align: top;
        }
        
        .info-item {
            background: white;
            /* padding: 12px; */
            border-radius: 6px;
            /* border-left: 3px solid #4a69bd; */
            margin: 5px 0;
        }
        
        .info-label {
            font-size: 10px;
            text-transform: uppercase;
            color: #6c757d;
            font-weight: bold;
            margin-bottom: 4px;
            letter-spacing: 0.5px;
        }
        
        .info-value {
            font-size: 13px;
            font-weight: bold;
            color: #2c3e50;
        }
        
        /* Statistics Section */
        .statistics-section {
            margin-bottom: 25px;
        }
        
        .section-title {
            font-size: 18px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 15px;
            padding-left: 12px;
            border-left: 4px solid #4a69bd;
        }
        
        .stats-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        .stats-table td {
            width: 20%;
            padding: 8px;
        }
        
        .stat-card {
            background: white;
            padding: 18px;
            border-radius: 8px;
            text-align: center;
            border: 2px solid #f1f3f4;
            margin: 3px;
            position: relative;
        }
        
        .stat-card-primary { border-top: 4px solid #4a69bd; }
        .stat-card-success { border-top: 4px solid #28a745; }
        .stat-card-warning { border-top: 4px solid #ffc107; }
        .stat-card-danger { border-top: 4px solid #dc3545; }
        .stat-card-info { border-top: 4px solid #17a2b8; }
        
        .stat-number {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 6px;
        }
        
        .stat-number-primary { color: #4a69bd; }
        .stat-number-success { color: #28a745; }
        .stat-number-warning { color: #ffc107; }
        .stat-number-danger { color: #dc3545; }
        .stat-number-info { color: #17a2b8; }
        
        .stat-label {
            font-size: 10px;
            text-transform: uppercase;
            color: #6c757d;
            font-weight: bold;
            letter-spacing: 0.5px;
        }
        
        /* Engagement Summary */
        .engagement-summary {
            background: #4a69bd;
            color: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 25px;
        }
        
        .engagement-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 15px;
            text-align: center;
        }
        
        .engagement-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .engagement-table td {
            width: 33.33%;
            text-align: center;
            padding: 10px;
        }
        
        .engagement-item {
            background: rgba(255,255,255,0.15);
            padding: 15px;
            border-radius: 6px;
            margin: 0 5px;
        }
        
        .engagement-number {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 6px;
        }
        
        .engagement-label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            opacity: 0.9;
        }
        
        /* Analysis Box */
        .analysis-box {
            background: #fff3cd;
            border: 2px solid #ffd32a;
            border-radius: 8px;
            padding: 18px;
            margin-bottom: 20px;
        }
        
        .analysis-title {
            font-weight: bold;
            color: #856404;
            margin-bottom: 12px;
            font-size: 15px;
        }
        
        .analysis-item {
            margin-bottom: 8px;
            color: #856404;
        }
        
        .analysis-strong {
            font-weight: bold;
        }
        
        .status-positive { color: #28a745; font-weight: bold; }
        .status-warning { color: #ffc107; font-weight: bold; }
        .status-negative { color: #dc3545; font-weight: bold; }
        
        /* Table Styles */
        .table-container {
            background: white;
            border-radius: 8px;
            border: 1px solid #dee2e6;
            margin-bottom: 25px;
        }
        
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }
        
        .data-table th {
            background: #2c3e50;
            color: white;
            padding: 12px 6px;
            text-align: left;
            font-weight: bold;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        
        .data-table td {
            padding: 10px 6px;
            border-bottom: 1px solid #f1f3f4;
            vertical-align: middle;
        }
        
        .data-table tr:nth-child(even) {
            background: #f8f9fa;
        }
        
        /* Status Badges */
        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-weight: bold;
            font-size: 8px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        
        .status-draft { 
            background: #fff3cd; 
            color: #856404; 
            border: 1px solid #ffeaa7;
        }
        .status-selesai { 
            background: #cce5ff; 
            color: #004085; 
            border: 1px solid #99d3ff;
        }
        .status-disetujui { 
            background: #d4edda; 
            color: #155724; 
            border: 1px solid #a3d9a5;
        }
        .status-ditolak { 
            background: #f8d7da; 
            color: #721c24; 
            border: 1px solid #f1aeb5;
        }
        .upload-yes { 
            background: #d4edda; 
            color: #155724; 
            border: 1px solid #a3d9a5;
        }
        .upload-no { 
            background: #f8d7da; 
            color: #721c24; 
            border: 1px solid #f1aeb5;
        }
        
        /* Utility Classes */
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .text-left { text-align: left; }
        
        /* Footer */
        .footer {
            margin-top: 30px;
            text-align: center;
            color: #6c757d;
            border-top: 2px solid #e9ecef;
            padding-top: 15px;
            font-size: 10px;
        }
        
        .footer-title {
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 6px;
            font-size: 12px;
        }
        
        .footer-info {
            margin-bottom: 4px;
        }
        
        .footer-meta {
            font-size: 9px;
            color: #adb5bd;
            margin-top: 8px;
        }
        
        /* Page Break */
        .page-break {
            page-break-before: always;
        }
        
        /* mPDF specific fixes */
        table {
            border-spacing: 0;
        }
        
        .no-break {
            page-break-inside: avoid;
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <div class="header">
        <table class="header-table">
            <tr>
                <td class="company-section">
                    <div class="logo-placeholder">
                        <img src="/images/logo.jpg" width="80pt"/>
                    </div>
                </td>
                <td class="report-section">
                    <div class="report-title">LAPORAN INSIGHTS</div>
                    <div class="report-subtitle">Project Content Performance</div>
                    <div class="report-date">{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Project Information Card -->
    <div class="project-card no-break">
        <div class="project-title">Informasi Project: {{ $project->nama }}</div>
        <table class="info-table">
            <tr>
                <td>
                    <div class="info-item">
                        <div class="info-label">No. Pesanan</div>
                        <div class="info-value">{{ $project->order->nomor }}</div>
                    </div>
                </td>
                <td>
                    <div class="info-item">
                        <div class="info-label">Paket Layanan</div>
                        <div class="info-value">{{ $project->order->paket->nama }}</div>
                    </div>
                </td>
                <td>
                    <div class="info-item">
                        <div class="info-label">Durasi</div>
                        <div class="info-value">{{ $project->order->durasi }} Bulan</div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Statistics Section -->
    <div class="statistics-section no-break">
        <div class="section-title">Statistik Keseluruhan</div>
        <table class="stats-table">
            <tr>
                <td>
                    <div class="stat-card stat-card-primary">
                        <div class="stat-number stat-number-primary">{{ $statistics['total_tasks'] }}</div>
                        <div class="stat-label">Total Konten</div>
                    </div>
                </td>
                <td>
                    <div class="stat-card stat-card-success">
                        <div class="stat-number stat-number-success">{{ $statistics['completed_tasks'] }}</div>
                        <div class="stat-label">Disetujui</div>
                    </div>
                </td>
                <td>
                    <div class="stat-card stat-card-warning">
                        <div class="stat-number stat-number-warning">{{ $statistics['pending_tasks'] }}</div>
                        <div class="stat-label">Draft</div>
                    </div>
                </td>
                <td>
                    <div class="stat-card stat-card-danger">
                        <div class="stat-number stat-number-danger">{{ $statistics['rejected_tasks'] }}</div>
                        <div class="stat-label">Ditolak</div>
                    </div>
                </td>
                <td>
                    <div class="stat-card stat-card-info">
                        <div class="stat-number stat-number-info">{{ $statistics['uploaded_tasks'] }}</div>
                        <div class="stat-label">Terupload</div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Engagement Summary -->
    <div class="engagement-summary no-break">
        <div class="engagement-title">Ringkasan Engagement</div>
        <table class="engagement-table">
            <tr>
                <td>
                    <div class="engagement-item">
                        <div class="engagement-number">{{ number_format($statistics['total_views'], 0, ',', '.') }}</div>
                        <div class="engagement-label">Total Views</div>
                    </div>
                </td>
                <td>
                    <div class="engagement-item">
                        <div class="engagement-number">{{ number_format($statistics['total_engagement'], 0, ',', '.') }}</div>
                        <div class="engagement-label">Total Engagement</div>
                    </div>
                </td>
                <td>
                    <div class="engagement-item">
                        <div class="engagement-number">{{ number_format($statistics['engagement_rate'], 2) }}%</div>
                        <div class="engagement-label">Engagement Rate</div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Analysis Box -->
    <div class="analysis-box no-break">
        <div class="analysis-title">Analisis Performa:</div>
        <div class="analysis-item">
            • <span class="analysis-strong">Progress Keseluruhan:</span> {{ $statistics['total_tasks'] > 0 ? number_format(($statistics['completed_tasks'] / $statistics['total_tasks']) * 100, 1) : 0 }}% konten telah disetujui
        </div>
        <div class="analysis-item">
            • <span class="analysis-strong">Tingkat Upload:</span> {{ $statistics['total_tasks'] > 0 ? number_format(($statistics['uploaded_tasks'] / $statistics['total_tasks']) * 100, 1) : 0 }}% konten telah diupload ke media sosial
        </div>
        <div class="analysis-item">
            • <span class="analysis-strong">Engagement Rate:</span> {{ number_format($statistics['engagement_rate'], 2) }}% (Industry average: 1-3%)
        </div>
        <div class="analysis-item">
            @if($statistics['engagement_rate'] > 3)
            • <span class="status-positive">✓ Performa engagement sangat baik!</span>
            @elseif($statistics['engagement_rate'] > 1)
            • <span class="status-warning">⚠ Performa engagement cukup baik</span>
            @else
            • <span class="status-negative">⚠ Performa engagement perlu ditingkatkan</span>
            @endif
        </div>
    </div>

    @if($tasks->count() > 0)
    <!-- Content Performance Table -->
    <div class="page-break"></div>
    <div class="section-title">Detail Konten & Performance</div>
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th width="4%">#</th>
                    <th width="26%">Nama Konten</th>
                    <th width="10%">Status</th>
                    <th width="8%">Upload</th>
                    <th width="12%">Tgl Tempo</th>
                    <th width="10%">Views</th>
                    <th width="8%">Likes</th>
                    <th width="8%">Comments</th>
                    <th width="8%">Shares</th>
                    <th width="6%">Rate</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tasks as $task)
                @php
                    $taskEngagement = ($task->total_likes ?? 0) + ($task->total_comments ?? 0) + ($task->total_share ?? 0);
                    $taskEngagementRate = ($task->total_view ?? 0) > 0 ? ($taskEngagement / $task->total_view) * 100 : 0;
                @endphp
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $task->nama }}</td>
                    <td>
                        <span class="status-badge status-{{ strtolower($task->status) }}">
                            {{ $task->status }}
                        </span>
                    </td>
                    <td>
                        <span class="status-badge {{ $task->status_upload ? 'upload-yes' : 'upload-no' }}">
                            {{ $task->status_upload ? 'Ya' : 'Tidak' }}
                        </span>
                    </td>
                    <td>
                        @if($task->tgl_tempo)
                            {{ \Carbon\Carbon::parse($task->tgl_tempo)->translatedFormat('d M Y') }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="text-right">{{ number_format($task->total_view ?? 0, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($task->total_likes ?? 0, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($task->total_comments ?? 0, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($task->total_share ?? 0, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($taskEngagementRate, 1) }}%</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <div class="footer-title">NEO Agency Advertising - Social Media Management Services</div>
        <div class="footer-info">Laporan ini dibuat secara otomatis pada {{ \Carbon\Carbon::now()->translatedFormat('d F Y H:i') }}</div>
        <div class="footer-meta">
            Insights Report - {{ $project->nama }} | {{ $project->order->nomor }}
        </div>
    </div>
</body>
</html>