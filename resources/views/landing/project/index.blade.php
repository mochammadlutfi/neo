<x-user-layout>
    <div class="block rounded">
        <div class="block-header">
            <h3 class="block-title fw-semibold">Project</h3>
            <div class="block-options">

            </div>
        </div>
        <div class="block-content">
            <table class="table table-bordered w-100 datatable">
                <thead>
                    <tr>
                        <th width="60px">No</th>
                        <th width="200px">No Pesanan</th>
                        <th width="200px">Nama</th>
                        <th width="200px">Total Tugas</th>
                        <th width="60px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $d)
                        <tr>
                            <td>{{ $loop->index+1 }}</td>
                            <td>{{ $d->order->nomor }}</td>
                            <td>{{ $d->nama }}</td>
                            <td>{{ $d->task_count }}</td>
                            <td>
                                <a href="{{ route('user.project.show', $d->id) }}" class="btn btn-primary btn-sm">
                                    <i class="si si-eye me-1"></i>
                                    Detail
                                </a>
                            </td>
                        </tr>                            
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-user-layout>