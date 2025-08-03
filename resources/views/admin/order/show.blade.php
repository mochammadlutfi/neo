<x-app-layout>
    <div class="content">
        <!-- Page Header -->
        <div class="block block-rounded">
            <div class="block-header">
                <h3 class="block-title fs-base fw-bold">
                    <i class="fa fa-file-alt me-2 text-primary"></i>
                    Detail Pesanan: {{ $data->nomor }}
                </h3>
                <div class="block-options">
                    @if($data->status_pembayaran != 'Lunas')
                    <button type="button" class="btn btn-sm btn-info fs-base me-2" onclick="kirimTagihan()">
                        <i class="fa fa-paper-plane me-1"></i>
                        Kirim Tagihan
                    </button>
                    @endif
                    <a href="{{ route('admin.order.invoice', $data->id) }}" class="btn btn-sm btn-primary fs-base me-2" target="_blank">
                        <i class="fa fa-print me-1"></i>
                        Download PDF
                    </a>
                    @if(in_array(auth()->guard('admin')->user()->level, ['Marketing', 'Manager']))
                    <a href="{{ route('admin.order.edit', $data->id) }}" class="btn btn-sm btn-warning fs-base me-2">
                        <i class="fa fa-edit me-1"></i>
                        Ubah
                    </a>
                    <button type="button" class="btn btn-sm btn-danger fs-base" onclick="hapus()">
                        <i class="fa fa-trash me-1"></i>
                        Hapus
                    </button>
                    @endif
                </div>
            </div>
        </div>
        <div class="block block-rounded">
            <div class="block-content p-4">
                <div class="row">
                    <div class="col-md-6">
                        <x-show-field label="Konsumen" value="{{ $data->user->nama }}"/>
                        <x-show-field label="Tanggal pesan" value="{{ \Carbon\Carbon::parse($data->tgl)->translatedFormat('d F Y') }}"/>
                        <x-show-field label="Harga per bulan" value="Rp {{ number_format($data->paket->harga,0,',','.') }}"/>
                    </div>
                    <div class="col-md-6">
                        <x-show-field label="Paket" value="{{ $data->paket->nama }}"/>
                        <x-show-field label="Durasi" value="{{ $data->durasi }} Bulan"/>
                        <x-show-field label="Total" value="Rp {{ number_format($data->total,0,',','.') }}"/>

                    </div>
                </div>
            </div>
        </div>
        <div class="block block-rounded overflow-hidden">
            <div class="p-4 border-bottom border-2">
                <ul class="nav nav-tabs nav-fill nav-pills" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="btabs-project-tab" data-bs-toggle="tab"
                            data-bs-target="#btabs-project" role="tab" aria-controls="btabs-project"
                            aria-selected="true">
                            <i class="si fa-fw si-briefcase opacity-50 me-1"></i> Project
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="btabs-payment-tab" data-bs-toggle="tab"
                            data-bs-target="#btabs-payment" role="tab"
                            aria-controls="btabs-payment" aria-selected="false" tabindex="-1">
                            <i class="si fa-fw si-wallet opacity-50 me-1"></i> Pembayaran
                        </button>
                    </li>
                </ul>
            </div>
            <div class="block-content tab-content">
                <div class="tab-pane fade show active" id="btabs-project" role="tabpanel"
                    aria-labelledby="btabs-project-tab" tabindex="0">
                    <table class="table table-bordered w-100" id="tableProject">
                        <thead>
                            <tr>
                                <th width="60px">No</th>
                                <th width="200px">Nama</th>
                                <th width="300px">Konsumen</th>
                                <th width="200px">No Pesanan</th>
                                <th width="200px">Total Tugas</th>
                                <th width="60px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane fade" id="btabs-payment" role="tabpanel"
                    aria-labelledby="btabs-payment-tab" tabindex="0">
                    <table class="table table-bordered w-100 table-vcenter" id="tablePayment">
                        <thead>
                            <tr>
                                <th width="60px">No</th>
                                <th>Tanggal</th>
                                <th>Jumlah</th>
                                <th>Status</th>
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
        <div
            class="modal"
            id="modal-normal"
            tabindex="-1"
            aria-labelledby="modal-normal"
            style="display: none;"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form id="form-payment"  onsubmit="return false;" enctype="multipart/form-data">
                        <div class="block block-rounded shadow-none mb-0">
                            <div class="block-header block-header-default">
                                <h3 class="block-title">Pembayaran</h3>
                                <div class="block-options">
                                    <button
                                        type="button"
                                        class="btn-block-option"
                                        data-bs-dismiss="modal"
                                        aria-label="Close">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="block-content fs-sm">
                                <input type="hidden" name="booking_id" value="{{ $data->id }}"/>
                                <div class="mb-4">
                                    <label for="field-tgl1">Tanggal</label>
                                    <input type="text" class="form-control" id="field-tgl1" name="tgl" placeholder="Masukan Tanggal">
                                    <div class="invalid-feedback" id="error-tgl">Invalid feedback</div>
                                </div>
                                <div class="mb-4">
                                    <label for="field-jumlah2">Jumlah</label>
                                    <input type="number" value="{{ $data->total_bayar - $data->bayar_sum_jumlah }}" class="form-control" id="field-jumlah2" name="jumlah" placeholder="Masukan Jumlah">
                                    <div class="invalid-feedback" id="error-jumlah">Invalid feedback</div>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label" for="field-bukti">Bukti Bayar</label>
                                    <input class="form-control" type="file" name="bukti" id="field-bukti">
                                    <div class="invalid-feedback" id="error-bukti">Invalid feedback</div>
                                </div>
                                <div
                                    class="block-content block-content-full block-content-sm text-end border-top">
                                    <button type="button" class="btn btn-alt-secondary" data-bs-dismiss="modal">
                                        batal
                                    </button>
                                    <button type="submit" class="btn btn-alt-primary" id="btn-simpan">
                                        Simpan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div
            class="modal"
            id="modal-show"
            tabindex="-1"
            aria-labelledby="modal-show"
            style="display: none;"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <form id="form-payment"  onsubmit="return false;" enctype="multipart/form-data">
                        <div class="block block-rounded shadow-none mb-0">
                            <div class="block-header block-header-default">
                                <h3 class="block-title">Detail Pembayaran</h3>
                                <div class="block-options">
                                    <button
                                        type="button"
                                        class="btn-block-option"
                                        data-bs-dismiss="modal"
                                        aria-label="Close">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            <div id="detailPembayaran">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal" id="modal-form" aria-labelledby="modal-form" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <form id="formData" onsubmit="return false;" enctype="multipart/form-data">
                        <div class="block block-rounded shadow-none mb-0">
                            <div class="block-header bg-gd-dusk">
                                <h3 class="block-title text-white" id="modalFormTitle">Pembayaran</h3>
                                <div class="block-options">
                                    <button type="button" class="text-white btn-block-option" data-bs-dismiss="modal" aria-label="Close">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="block-content fs-sm">
                                <input type="hidden" name="id" id="field-id">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-4">
                                            <label for="field-order_id">No Pemesanan</label>
                                            <select class="form-select" id="field-order_id" style="width: 100%;"
                                                name="order_id" data-placeholder="Pilih Pesanan">
                                            </select>
                                            <div class="invalid-feedback" id="error-order_id">Invalid feedback</div>
                                        </div>
                                        <x-input-field type="text" id="tgl" name="tgl" label="Tanggal" :required="true" isAjax/>
                                    </div>
                                    <div class="col-md-6">
                                        <x-input-field type="number" id="jumlah" name="jumlah" label="Jumlah" :required="true" isAjax/>
                                        <div class="mb-4">
                                            <label class="form-label" for="field-bukti">Bukti Bayar</label>
                                            <input class="form-control" type="file" name="bukti" id="field-bukti">
                                            <div class="invalid-feedback" id="error-bukti">Invalid feedback</div>
                                            <div id="current-bukti" class="mt-2" style="display: none;">
                                                <small class="text-muted">File saat ini:</small>
                                                <img id="preview-bukti" src="" alt="Current Bukti" class="img-thumbnail" style="max-width: 200px;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="block-content block-content-full block-content-sm text-end border-top">
                                <button type="button" class="btn btn-alt-danger px-4 rounded-pill" data-bs-dismiss="modal">
                                    <i class="fa fa-times me-1"></i>
                                    Batal
                                </button>
                                <button type="submit" class="btn btn-alt-primary px-4 rounded-pill" id="btn-simpan">
                                    <i class="fa fa-check me-1"></i>
                                    Simpan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    @push('scripts')
    <script>
        $(function () {
            var tablePayment = $('#tablePayment').DataTable({
                processing: true,
                serverSide: true,
                dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>><'row'<'col-sm-12'tr>><'r" +
                        "ow'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                ajax: {
                    url : "{{ route('admin.payment.index') }}",
                    data : function(data){
                        data.order_id = "{{ $data->id }}";
                    },
                },
                columns: [
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    }, {
                        data: 'tgl',
                        name: 'tgl'
                    }, {
                        data: 'jumlah',
                        name: 'jumlah'
                    }, {
                        data: 'status',
                        name: 'status'
                    }, {
                        data: 'action',
                        name: 'action',
                        orderable: true,
                        searchable: true
                    }
                ]
            });

            var tablePayment = $('#tableProject').DataTable({
                    processing: true,
                    serverSide: true,
                    dom : "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>><'row'<'col-sm-12'tr>><'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                    ajax: {
                        url :"{{ route('admin.project.index') }}",
                        data : function(data){
                            data.order_id = "{{ $data->id }}";
                        },
                    },
                    columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                        {data: 'nama', name: 'nama'},
                        {data: 'user.nama', name: 'user.nama'},
                        {data: 'order.nomor', name: 'order.nomor'},
                        {data: 'task_count', name: 'task_count'},
                        {
                            data: 'action', 
                            name: 'action', 
                            orderable: true, 
                            searchable: true
                        },
                    ]
                });
        });


        function resetForm() {
                $('#formData')[0].reset();
                $('#field-id').val('');
                $('#field-order_id').val(null).trigger('change');
                $('#current-bukti').hide();
                $('.form-control').removeClass('is-invalid');
                $('.invalid-feedback').html('');
                $('#modalFormTitle').text('Tambah Pembayaran');
            }
            
            function editPayment(id){
                $.ajax({
                    url: "/admin/pembayaran/" + id + "/edit",
                    type: "GET",
                    success: function (response) {
                        if (response.fail == false) {
                            // Reset form first
                            resetForm();
                            
                            // Fill form with data
                            $('#field-id').val(response.data.id);
                            $('#field-jumlah').val(response.data.jumlah);
                            $('#field-tgl').val(response.data.tgl);
                            
                            // Set order selection
                            var option = new Option(response.data.order_nomor, response.data.order_id, true, true);
                            $('#field-order_id').append(option).trigger('change');
                            
                            // Show current bukti if exists
                            if (response.data.bukti) {
                                $('#preview-bukti').attr('src', response.data.bukti);
                                $('#current-bukti').show();
                            }
                            
                            $('#modalFormTitle').text('Edit Pembayaran');
                            
                            var modalForm = bootstrap.Modal.getOrCreateInstance(document.getElementById('modal-form'));
                            modalForm.show();
                        } else {
                            Swal.fire({
                                toast: true,
                                title: "Gagal",
                                text: response.message || "Gagal mengambil data!",
                                timer: 1500,
                                showConfirmButton: false,
                                icon: 'error',
                                position: 'top-end'
                            });
                        }
                    },
                    error: function (error) {
                        Swal.fire({
                            toast: true,
                            title: "Gagal",
                            text: "Terjadi kesalahan di server!",
                            timer: 1500,
                            showConfirmButton: false,
                            icon: 'error',
                            position: 'top-end'
                        });
                    }
                });
            }

        $("#field-tgl").flatpickr({
            altInput: true,
            altFormat: "j F Y",
            dateFormat: "Y-m-d",
            locale : "id",
            defaultDate : new Date(),
            minDate: "today"
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

        $("#formData").on("submit",function (e) {
                e.preventDefault();
                var fomr = $('form#formData')[0];
                var formData = new FormData(fomr);
                let token   = $("meta[name='csrf-token']").attr("content");
                formData.append('_token', token);

                var id = $("#field-id").val();

                $.ajax({
                    url:  "/admin/pembayaran/"+id+"/update",
                    type: "POST",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        if (response.fail == false) {
                            $('#tablePayment').DataTable().ajax.reload();
                            const el = document.getElementById('modal-form');
                            var myModal = bootstrap.Modal.getOrCreateInstance(el);
                            myModal.hide();
                            resetForm();
                            
                            Swal.fire({
                                toast: true,
                                title: "Berhasil",
                                text: "Data berhasil disimpan!",
                                timer: 1500,
                                showConfirmButton: false,
                                icon: 'success',
                                position: 'top-end'
                            });
                        } else {
                            // Clear previous errors
                            $('.form-control').removeClass('is-invalid');
                            $('.invalid-feedback').html('');
                            
                            // Show new errors
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
        function updateStatus(id, status){
                // console.log(status);
                $.ajax({
                    url: "/admin/pembayaran/"+id +"/status",
                    type: "POST",
                    data : {
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
            

            function kirimTagihan(){
                Swal.fire({
                    icon : 'question',
                    text: 'Kirim tagihan sisa pembayaran ke customer?',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Kirim!',
                    cancelButtonText: `Batal`,
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "/admin/order/{{ $data->id }}/send-invoice",
                            type: "POST",
                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            success: function(data) {
                                if(data.fail == false){
                                    Swal.fire({
                                        toast : true,
                                        title: "Berhasil",
                                        text: "Tagihan berhasil dikirim!",
                                        timer: 1500,
                                        showConfirmButton: false,
                                        icon: 'success',
                                        position : 'top-end'
                                    });
                                }else{
                                    Swal.fire({
                                        toast : true,
                                        title: "Gagal",
                                        text: "Gagal mengirim tagihan!",
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

            function hapus(){
                Swal.fire({
                    icon : 'warning',
                    text: 'Hapus Data?',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: `Tidak, Jangan!`,
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('admin.order.delete', $data->id )}}",
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
                                        // $('#tableTask').DataTable().ajax.reload();
                                        window.location = "{{ route('admin.order.index') }}";
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
        $("#form-payment").on("submit",function (e) {
            e.preventDefault();
            var fomr = $('form#form-payment')[0];
            var formData = new FormData(fomr);
            let token   = $("meta[name='csrf-token']").attr("content");
            formData.append('_token', token);

            $.ajax({
                url: "{{ route('admin.payment.store') }}",
                type: "POST",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function (response) {
                    if (response.fail == false) {
                        $('.datatable').DataTable().ajax.reload();
                        var myModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('modal-normal'));
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
    </script>
    @endpush

</x-app-layout>

