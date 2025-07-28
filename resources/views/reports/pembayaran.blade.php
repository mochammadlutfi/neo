<html>

<head>
    <title> Laporan Pembayaran</title>
    <link rel="stylesheet" href="/css/bootstrap.css">
</head>

<body>
    <div class="container">
        <table width="100%">
            <tr>
                <td>
                    <img src="/images/logo.png" width="80pt"/>
                </td>
                <td class="text-center">
                    <h1 class="text-center" style="font-size:30pt; font-weight: bold;">NEO Agency Advertising</h1>
                    <h2 class="h3 text-center" style="font-weight: bold; margin-top:0px">LAPORAN PEMBAYARAN</h2>
                    <h2 class="h4 text-center" style="font-weight: bold; margin-top:0px">
                        Periode : {{ \Carbon\Carbon::parse($tgl[0])->translatedFormat('d F Y') }} - {{ \Carbon\Carbon::parse($tgl[1])->translatedFormat('d F Y') }}
                    </h2>
            </tr>
        </table>
        <hr/>
        <table class="table table-bordered datatable w-100">
            <thead>
                <tr>
                    <th width="60px">No</th>
                    <th>No Pesanan</th>
                    <th>Konsumen</th>
                    <th>Tanggal</th>
                    <th>Jumlah</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $a)
                    <tr>
                        <td>{{ $loop->index+1 }}</td>
                        <td>{{ $a->order->nomor }}</td>
                        <td>{{ $a->order->user->nama }}</td>
                        <td>{{ \Carbon\Carbon::parse($a->tgl)->translatedFormat('d F Y') }}</td>
                        <td>Rp {{ number_format($a->jumlah,0,',','.') }}</td>
                        <td>{{ $a->status }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</body>

</html>