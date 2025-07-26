<x-app-layout>
    <div class="content">
        <div class="content-heading d-flex justify-content-between align-items-center">
            <div class="content-title">
                Detail Project {{ $data->nama }}
            </div>
            <div class="space-x">
                <a href="{{ route('admin.project.edit', $data->id) }}" class="btn btn-sm btn-alt-primary me-2">
                    <i class="fa fa-edit me-1"></i>
                    Ubah
                </a>
                <a href="{{ route('admin.project.calendar', $data->id) }}" class="btn btn-sm btn-alt-warning me-2">
                    <i class="si si-calendar me-1"></i>
                    Kalender
                </a>
                <button type="button" class="btn btn-sm btn-alt-danger" onclick="hapus()">
                    <i class="si si-trash me-1"></i>
                    Hapus
                </button>
            </div>
        </div>
        <div class="block block-rounded mb-2">
            <div class="block-content p-4">
                <div class="row">
                    <div class="col-md-6">
                        <x-show-field label="Konsumen" value="{{ $data->user->nama }}"/>
                    </div>
                    <div class="col-md-6">
                        <x-show-field label="No Pemesanan" value="{{ $data->order->nomor }}"/>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-heading d-flex justify-content-between align-items-center">
            <div class="content-title">
                Tugas
            </div>
            <div class="space-x">
                <button class="btn btn-alt-primary btn-sm" onclick="openModal()">
                    <i class="fa fa-plus me-1"></i>
                    Tambah Tugas
                </button>
            </div>
        </div>
        <div class="block block-rounded">
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
                        <tr></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="modal" id="modal-form" aria-labelledby="modal-form" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form id="formData" onsubmit="return false;" enctype="multipart/form-data">
                    <div class="block block-rounded shadow-none mb-0">
                        <input type="hidden" name="project_id" value="{{  $data->id }}"/>
                        <input type="hidden" id="field-id" name="id" value=""/>
                        <div class="block-header bg-gd-dusk">
                            <h3 class="block-title text-white" id="modalFormTitle">Tugas</h3>
                            <div class="block-options">
                                <button type="button" class="text-white btn-block-option" data-bs-dismiss="modal" aria-label="Close">
                                    <i class="fa fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="block-content p-4">
                            <div id="note-wrap" style="display: none;">
                                <label class="form-label" for="field-status">Catatan</label>
                                <p id="note-val"></p>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <x-input-field type="text" id="nama" name="nama" label="Nama Tugas" isAjax/>
                                    <x-input-field type="text" id="tgl_tempo" name="tgl_tempo" label="Tanggal Tempo" isAjax/>
                                    <x-input-field type="text" id="link_brief" name="link_brief" label="Link Brief" isAjax/>

                                </div>
                                <div class="col-md-6">
                                    <x-input-field type="text" id="tgl_upload" name="tgl_upload" label="Tanggal Upload" isAjax/>
                                    <x-select-field id="status_upload" name="status_upload" label="Status Upload" isAjax :options="[
                                        ['value' => 0, 'label' => 'Belum Upload'],
                                        ['value' => 1, 'label' => 'Sudah Upload'],
                                    ]"/>
                                    <div class="mb-4">
                                        <label class="form-label" for="field-file">Upload File</label>
                                        <input class="form-control" type="file" name="file" id="field-file">
                                        <div class="invalid-feedback" id="error-file">Invalid feedback</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="block-content block-content-full block-content-sm text-end border-top">
                            <button type="button" class="btn btn-alt-danger" data-bs-dismiss="modal">
                                <i class="fa fa-times me-1"></i>
                                Batal
                            </button>
                            <button type="submit" class="btn btn-alt-primary" id="btn-simpan">
                                <i class="fa fa-check me-1"></i>
                                Simpan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="modal" id="modal-show" aria-labelledby="modal-show" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="block block-rounded shadow-none mb-0">
                    <div class="block-header bg-gd-dusk">
                        <h3 class="block-title text-white">Detail Tugas</h3>
                        <div class="block-options">
                            <button type="button" class="text-white btn-block-option" data-bs-dismiss="modal" aria-label="Close">
                                <i class="fa fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div id="detail">
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
    <script>
        $(function () {
            var table = $('#tableTask').DataTable({
                processing: true,
                serverSide: true,
                dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>><'row'<'col-sm-12'tr>><'r" +
                        "ow'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                ajax: {
                    url : "{{ route('admin.task.index') }}",
                    data : function(data){
                        data.project_id = "{{ $data->id }}";
                    },
                },
                columns: [
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    }, {
                        data: 'nama',
                        name: 'nama'
                    }, {
                        data: 'tgl_tempo',
                        name: 'tgl_tempo'
                    }, {
                        data: 'tgl_upload',
                        name: 'tgl_upload'
                    }, {
                        data: 'status',
                        name: 'status'
                    }, {
                        data: 'status_upload',
                        name: 'status_upload'
                    }, {
                        data: 'action',
                        name: 'action',
                        orderable: true,
                        searchable: true
                    }
                ]
            });
        });

        function openModal(){
            var modalForm = bootstrap.Modal.getOrCreateInstance(document.getElementById('modal-form'));
            $("#modalFormTitle").html('Tambah Tugas');
            modalForm.show();
        }

        function detail(id){
            $.ajax({
                url: "/admin/task/"+id,
                type: "GET",
                dataType: "html",
                success: function (response) {
                    var el = document.getElementById('modal-show');
                    $("#detail").html(response);
                    var myModal = bootstrap.Modal.getOrCreateInstance(el);
                    myModal.show();
                },
                error: function (error) {
                }

            });
        }

        function edit(data){
            var modalForm = bootstrap.Modal.getOrCreateInstance(document.getElementById('modal-form'));
            modalForm.show();
        }


        function edit(id){
            $.ajax({
                url: `/admin/task/${id}/edit`,
                type: "GET",
                dataType: "json",
                success: function (response) {
                    $('#field-id').val(response.id);
                    $('#field-nama').val(response.nama);
                    $('#field-tgl_tempo').val(response.tgl_tempo);
                    $('#field-status').val(response.status);
                    $('#field-link_brief').val(response.link_brief);
                    $('#field-tgl_upload').val(response.tgl_upload);
                    $('#field-status').val(response.status);
                    $('#field-status_upload').val(response.status_upload);
                    $("#modalFormTitle").html('Ubah Tugas');

                    $("#note-wrap").show();
                    $("#note-val").html(response.catatan);

                    var el = document.getElementById('modal-form');
                    var myModal = bootstrap.Modal.getOrCreateInstance(el);
                    myModal.show();
                },
                error: function (error) {
                }

            });
            }

        $("#field-tgl_tempo").flatpickr({
            altInput: true,
            altFormat: "j F Y",
            dateFormat: "Y-m-d",
            locale : "id",
            defaultDate : new Date(),
            minDate: "today"
        });

        

        $("#field-tgl_upload").flatpickr({
            altInput: true,
            altFormat: "j F Y H:i",
            dateFormat: "Y-m-d H:i",
            locale : "id",
            defaultDate : new Date(),
            minDate: "today",
            enableTime : true
        });

        function modalShow(id){
            $.ajax({
                url: "/admin/pembayaran/"+id,
                type: "GET",
                dataType: "html",
                success: function (response) {
                    var el = document.getElementById('modal-show');
                    $("#detailPembayaran").html(response);
                    var myModal = bootstrap.Modal.getOrCreateInstance(el);
                    myModal.show();
                },
                error: function (error) {
                }

            });
        }

        function updateStatus(id, status, booking_id){
            // console.log(status);
            $.ajax({
                url: "/admin/pembayaran/"+id +"/status",
                type: "POST",
                data : {
                    booking_id : booking_id,
                    status : status,
                    _token : $("meta[name='csrf-token']").attr("content"),
                },
                success: function (response) {
                    // console.log(response);
                    location.reload();
                    var el = document.getElementById('modal-show');
                    $('.datatable').DataTable().ajax.reload();
                    // $("#detailPembayaran").html(response);
                    var myModal = bootstrap.Modal.getOrCreateInstance(el);
                    myModal.hide();
                },
                error: function (error) {
                }
            });
        }

        $("#formData").on("submit",function (e) {
            e.preventDefault();
            var fomr = $('form#formData')[0];
            var formData = new FormData(fomr);
            let token   = $("meta[name='csrf-token']").attr("content");
            formData.append('_token', token);

            let id = $('#field-id').val();

            let url = (id) ? `/admin/task/${id}/update` : `/admin/task/simpan`;

            $.ajax({
                url: url,
                type: "POST",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function (response) {
                    if (response.fail == false) {
                        $('#tableTask').DataTable().ajax.reload();
                        var myModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('modal-form'));
                        myModal.hide();
                    } else {
                        for (control in response.errors) {
                            $('#field-' + control).addClass('is-invalid');
                            $('#error-' + control).html(response.errors[control]);
                        }
                    }
                },
                error: function (error) {
                }

            });

        });

        
        function hapus(id){
                Swal.fire({
                    icon : 'warning',
                    text: 'Hapus Data?',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: `Tidak, Jangan!`,
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "/admin/task/"+ id +"/delete",
                            type: "DELETE",
                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            success: function(data) {
                                if(data.fail == false){
                                    Swal.fire({
                                        toast : true,
                                        title: "Berhasil",
                                        text: "Data Berhasil Dihapus!",
                                        timer: 1500,
                                        showConfirmButton: false,
                                        icon: 'success',
                                        position : 'top-end'
                                    }).then((result) => {
                                        $('#tableTask').DataTable().ajax.reload();
                                    });
                                }else{
                                    Swal.fire({
                                        toast : true,
                                        title: "Gagal",
                                        text: "Data Gagal Dihapus!",
                                        timer: 1500,
                                        showConfirmButton: false,
                                        icon: 'error',
                                        position : 'top-end'
                                    });
                                }
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                    Swal.fire({
                                        toast : true,
                                        title: "Gagal",
                                        text: "Terjadi Kesalahan Di Server!",
                                        timer: 1500,
                                        showConfirmButton: false,
                                        icon: 'error',
                                        position : 'top-end'
                                    });
                            }
                        });
                    }
                })
            }
    </script>
    @endpush

</x-app-layout>

