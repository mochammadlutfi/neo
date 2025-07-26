<x-landing-layout>
    
    <div class="content">
        <div class="block block-rounded">
            <div class="block-header bg-gd-dusk">
                <h3 class="block-title text-white fw-semibold">Detail Project</h3>
                <div class="block-options">
                    <a href="{{ route('user.project.calendar', ['id' => $data->order_id, 'project' => $data->id]) }}" class="btn rounded-pill btn-alt-warning me-2">
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
                        <x-show-field label="No Pemesanan" value="{{ $data->order->nomor }}"/>
                    </div>
                </div>
            </div>
        </div>
        <div class="block block-rounded">
            <div class="bg-gd-dusk block-header">
                <h3 class="block-title fs-4 fw-semibold mb-0 text-white">
                    Tugas
                </h3>
            </div>
            <div class="block-content p-4">
                <table class="table table-bordered w-100 table-vcenter" id="tableTask">
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
                                        <span class="badge bg-danger">Draft</span>
                                    @elseif($d->status == 'Pending')
                                        <span class="badge bg-warning">Pending</span>
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
                                    <button type="button" class="btn btn-gd-main" data-bs-toggle="modal" data-bs-target="#modalShow-{{$d->id}}">
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
    </div>
    @push('scripts')
    <script>
        $(function () {
            var table = $('#tableTask').DataTable({
                processing: true,
                serverSide: false,
                dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>><'row'<'col-sm-12'tr>><'r" +
                        "ow'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            });
        });

        function detail(data){
            var el = document.getElementById('modal-show');
            var myModal = bootstrap.Modal.getOrCreateInstance(el);
            myModal.show();
        }
    </script>
@endpush
</x-landing-layout>