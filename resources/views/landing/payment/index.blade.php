<x-user-layout>
    <div class="block rounded">
        <div class="block-header">
            <h3 class="block-title fw-semibold">Pembayaran</h3>
            <div class="block-options">

            </div>
        </div>
        <div class="block-content">
            <table class="table table-bordered w-100 datatable">
                <thead>
                    <tr>
                        <th width="60px">No</th>
                        <th width="200px">No Pesanan</th>
                        <th>Tanggal</th>
                        <th>Jumlah</th>
                        <th>Status</th>
                        <th width="120px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $d)
                        <tr>
                            <td>{{ $loop->index+1 }}</td>
                            <td>{{ $d->order->nomor }}</td>
                            <td>{{ \Carbon\Carbon::parse($d->created_at)->translatedFormat('d F Y') }}</td>
                            <td>Rp {{ number_format($d->jumlah,0,',','.') }}</td>
                            <td>
                                @if($d->status == 'pending')
                                <span class="badge bg-warning">Pending</span>
                                @elseif ($d->status == 'terima')
                                <span class="badge bg-success">Diterima</span>
                                @else
                                <span class="badge bg-danger">Ditolak</span>
                                @endif
                            </td>
                            <td>
                                <button class="btn btn-primary btn-sm">
                                    <i class="si si-eye me-1"></i>
                                    Detail
                                </button>
                            </td>
                        </tr>                            
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-user-layout>