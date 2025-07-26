@props([
    'id' => '',
    'data' => null
])

<div class="modal" id="{{ $id }}" aria-labelledby="{{ $id }}" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="block rounded shadow-none mb-0">
                <div class="block-header border-3 border-bottom">
                    <h3 class="block-title">Detail Tugas</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">
                            <i class="fa fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="row mb-3 fs-sm">
                                <label class="col-sm-5 fw-medium">Nama Tugas</label>
                                <div class="col-sm-7">: {{ $data->nama}}</div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-5 fw-medium">Link Brief</label>
                                <div class="col-sm-7">: 
                                    <a href="{{ $data->link_brief }}" target="_blank" class="badge bg-primary px-3 text-white">Lihat Brief</a>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-5 fw-medium">Status</label>
                                <div class="col-sm-7">: 
                                    @if($data->status == 'Draft')
                                        <span class="badge bg-danger">Draft</span>
                                    @elseif($data->status == 'Pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @elseif($data->status == 'Selesai')
                                        <span class="badge bg-primary">Selesai</span>
                                    @elseif($data->status == 'Disetujui')
                                        <span class="badge bg-success">Disetujui</span>
                                    @elseif($data->status == 'Ditolak')
                                        <span class="badge bg-secondary">Ditolak</span>
                                    @endif
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-5 fw-medium">Tanggal Tempo</label>
                                <div class="col-sm-7">
                                    : {{ \Carbon\Carbon::parse($data->tgl)->translatedFormat('d F Y') }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-5 fw-medium">Tanggal Upload</label>
                                <div class="col-sm-7">
                                    : {{ \Carbon\Carbon::parse($data->tgl)->translatedFormat('d F Y H:i') }} WIB
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-5 fw-medium">Status Upload</label>
                                <div class="col-sm-7">: 
                                    
                                    @if($data->status_upload == 0)
                                        <span class="badge bg-danger">Belum Upload</span>
                                    @else
                                        <span class="badge bg-success">Sudah Upload</span>
                                    @endif
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-5 fw-medium">File Task</label>
                                <div class="col-sm-7">: 
                                    <a href="{{ $data->file }}" target="_blank" class="badge bg-primary px-3 text-white">
                                        Lihat File
                                    </a>
                                </div>
                            </div>
                            @if ($data->catatan)
                                <div class="row mb-3">
                                    <label class="col-sm-5 fw-medium">Catatan</label>
                                    <div class="col-sm-7">: {{ $data->catatan}}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                @if ($data->status != 'Disetujui')
                <form method="POST" action="{{ route('user.project.status', $data->id)}}">
                    @csrf
                    <div class="block-content">
                       <div class="mb-4">
                        <label class="form-label" for="field-catatan">Catatan</label>
                        <textarea class="form-control" name="catatan" id="field-catatan"></textarea>
                        </div>     
                    </div>
                    <div class="block-content block-content-full block-content-sm text-end border-top">
                        <button type="submit" name="status" value="Ditolak" class="btn btn-alt-danger px-4 rounded-pill" data-bs-dismiss="modal">
                            <i class="fa fa-times me-1"></i>
                            Tolak
                        </button>
                        <button type="submit" name="status" value="Disetujui" class="btn btn-alt-primary px-4 rounded-pill" id="btn-simpan">
                            <i class="fa fa-check me-1"></i>
                            Setuju
                        </button>
                    </div>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>