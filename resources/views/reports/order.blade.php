<html>

<head>
    <title> Laporan Pesanan</title>
    <link rel="stylesheet" href="/css/bootstrap.css">
</head>

<body>
    <div class="container">
        <table width="100%">
            <tr>
                <td>
                    <img src="/images/logo.jpg" width="80pt"/>
                </td>
                <td class="text-center">
                    <h1 class="text-center" style="font-size:30pt; font-weight: bold;">NEO Agency & Advertising</h1>
                    <h2 class="h3 text-center" style="font-weight: bold; margin-top:0px">LAPORAN PESANAN</h2>
                    <h2 class="h4 text-center" style="font-weight: bold; margin-top:0px">
                        Periode : {{ \Carbon\Carbon::parse($tgl[0])->translatedFormat('d F Y') }} - {{ \Carbon\Carbon::parse($tgl[1])->translatedFormat('d F Y') }}
                    </h2>
            </tr>
        </table>
        <hr/>
        <table class="table table-bordered datatable w-100">
            <thead>
                <tr>
                    <th width="50px">#</th>
                    <th width="200px">Nomor</th>
                    <th width="150px">Konsumen</th>
                    <th width="100px">Paket</th>
                    <th width="150px">Tgl Pemesanan</th>
                    <th width="120px">Durasi</th>
                    <th width="150px">Tgl Selesai</th>
                    <th width="150px">Jumlah Project</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $a)
                    <tr>
                        <td>{{ $loop->index+1 }}</td>
                        <td>{{ $a->nomor }}</td>
                        <td>{{ $a->user->nama }}</td>
                        <td>{{ $a->paket->nama }}</td>
                        <td>{{ \Carbon\Carbon::parse($a->tgl)->translatedFormat('d F Y') }}</td>
                        <td>{{ $a->durasi }} Bulan</td>
                        <td>{{ \Carbon\Carbon::parse($a->tgl)->addMonth($a->durasi)->translatedFormat('d F Y') }}</td>
                        <td>{{ $a->project_count }} Project</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</body>

</html>