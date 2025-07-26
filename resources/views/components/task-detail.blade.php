@props([
    'id' => '',
    'data' => null
])

<div class="modal fade" id="{{ $id }}" tabindex="-1" aria-labelledby="{{ $id }}Label" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="block block-rounded shadow-none mb-0">
                <!-- Modal Header -->
                <div class="block-header block-header-default">
                    <h3 class="block-title">
                        <i class="fa fa-tasks me-2 text-primary"></i>
                        Detail Tugas: {{ $data->nama }}
                    </h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">
                            <i class="fa fa-times"></i>
                        </button>
                    </div>
                </div>

                <!-- Modal Content -->
                <div class="block-content p-4">
                    <x-task-detail-content :data="$data"/>
                </div>

                <!-- Action Form (if not approved) -->
                @if ($data->status != 'Disetujui')
                    <form method="POST" action="{{ route('user.project.status', $data->id) }}">
                        @csrf
                        <div class="block-content border-top">
                            <div class="mb-3">
                                <label class="form-label fs-base fw-bold" for="field-catatan-{{ $id }}">
                                    <i class="fa fa-comment me-1"></i>
                                    Catatan Tambahan
                                </label>
                                <textarea class="form-control fs-base" name="catatan" id="field-catatan-{{ $id }}" rows="3" placeholder="Berikan catatan atau feedback untuk tugas ini..."></textarea>
                            </div>
                        </div>
                        <div class="modal-footer bg-body-light">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="fa fa-times me-1"></i>
                                Tutup
                            </button>
                            <button type="submit" name="status" value="Ditolak" class="btn btn-danger">
                                <i class="fa fa-times me-1"></i>
                                Tolak Tugas
                            </button>
                            <button type="submit" name="status" value="Disetujui" class="btn btn-success">
                                <i class="fa fa-check me-1"></i>
                                Setujui Tugas
                            </button>
                        </div>
                    </form>
                @else
                    <div class="modal-footer bg-body-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fa fa-times me-1"></i>
                            Tutup
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>