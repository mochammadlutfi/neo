<x-user-layout>
    <div class="block rounded">
        <div class="block-header border-3 border-bottom">
            <h3 class="block-title fw-semibold">Detail Project</h3>
            <div class="block-options">
                <a href="{{ route('user.project.calendar',$data->id) }}" class="btn btn-sm btn-alt-warning me-2">
                    <i class="si si-calendar me-1"></i>
                    Kalender
                </a>
            </div>
        </div>
        <div class="block-content p-4">
            <div class="row">
                <div class="col-md-6">
                    <x-show-field label="Nama Project" value="{{ $data->nama }}"/>
                </div>
                <div class="col-md-6">
                    <x-show-field label="No Pesanan" value="{{ $data->order->nomor }}"/>
                </div>
            </div>
        </div>
    </div>

    <div class="block rounded">
        <div class="block-header border-3 border-bottom">
            <h3 class="block-title fw-semibold">List Tugas</h3>
        </div>
        <div class="block-content p-4">
            <table class="table table-bordered w-100 table-vcenter datatable">
                <thead>
                    <tr>
                        <th width="60px">No</th>
                        <th>Nama</th>
                        <th>Tgl Tempo</th>
                        <th>Tgl Upload</th>
                        <th>Status</th>
                        <th>Status Upload</th>
                        <th width="60px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($task as $d)
                        <tr>
                            <td>{{ $loop->index+1 }}</td>
                            <td>{{ $d->nama }}</td>
                            <td>{{ $d->nama }}</td>
                            <td>{{ $d->nama }}</td>
                            <td>
                                @if($d->status == 'Draft')
                                    <span class="badge bg-warning">Draft</span>
                                @elseif($d->status == 'Selesai')
                                    <span class="badge bg-primary">Selesai</span>
                                @elseif($d->status == 'Disetujui')
                                    <span class="badge bg-success">Disetujui</span>
                                @elseif($d->status == 'Ditolak')
                                    <span class="badge bg-secondary">Ditolak</span>
                                @endif
                            </td>
                            <td>
                                @if($d->status_upload == 0)
                                    <span class="badge bg-danger">Belum Upload</span>
                                @else
                                    <span class="badge bg-success">Sudah Upload</span>
                                @endif
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalShow-{{$d->id}}">
                                    Detail
                                </button>
                                <x-task-detail id="modalShow-{{$d->id}}" :data="$d"/>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-user-layout>