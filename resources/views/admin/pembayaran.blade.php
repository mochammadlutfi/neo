<x-app-layout>
    <div class="content">
        <!-- Page Header -->
        <div class="block block-rounded">
            <div class="block-header">
                <h3 class="block-title fs-base fw-bold">
                    <i class="fa fa-credit-card me-2 text-primary"></i>
                    Kelola Pembayaran
                </h3>
                <div class="block-options">
                    <button type="button" class="btn btn-sm btn-primary fs-base me-2" onclick="addPayment()">
                        <i class="fa fa-plus me-1"></i>
                        Tambah Pembayaran
                    </button>
                    <button type="button" class="btn btn-sm btn-info fs-base" data-bs-toggle="modal" data-bs-target="#reportModal">
                        <i class="fa fa-print me-1"></i>
                        Download Report
                    </button>
                </div>
            </div>
        </div>
        <div class="block block-rounded">
            <div class="block-content p-3">
                <table class="table table-bordered w-100 table-vcenter" id="dataTable">
                    <thead>
                        <tr>
                            <th width="60px">No</th>
                            <th>Konsumen</th>
                            <th>No Pesanan</th>
                            <th>Tanggal</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                            <th width="100px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
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
        <div class="modal" id="modal-show" tabindex="-1" aria-labelledby="modal-show" style="display: none;"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="block block-rounded shadow-none mb-0">
                        <div class="block-header bg-gd-dusk">
                            <h3 class="block-title text-white" id="modalFormTitle">Detail Pembayaran</h3>
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
    </div>
    <div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.payment.report') }}" method="GET">
                    <div class="block block-rounded mb-0">
                        <div class="block-header bg-body">
                            <h3 class="block-title " id="modalFormTitle">Download Report</h3>
                            <div class="block-options">
                                <button type="button" class=" btn-block-option" data-bs-dismiss="modal" aria-label="Close">
                                    <i class="fa fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="block-content">
                            <x-input-field type="text" name="tgl" id="report_tgl" label="Periode Tanggal"/> 
                        
                            <x-select-field id="report_status" name="status" label="Status" :options="[
                                ['label' => 'Pending', 'value' => 'pending'],
                                ['label' => 'Diterima', 'value' => 'diterima'],
                                ['label' => 'Ditolak', 'value' => 'ditolak'],
                            ]"/>
                        </div>
                        <div class="block-content block-content-full block-content-sm text-end border-top">
                            <button type="button" class="btn btn-alt-secondary" data-bs-dismiss="modal">
                              Batal
                            </button>
                            <button type="submit" class="btn btn-primary">
                                Download
                            </button>
                          </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    @push('scripts')
        <script>
            $(function () {
                $('#dataTable').DataTable({
                    processing: true,
                    serverSide: true,
                    dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>><'row'<'col-sm-12'tr>><'r" +
                            "ow'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                    ajax: "{{ route('admin.payment.index') }}",
                    columns: [
                        {
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex'
                        }, {
                            data: 'order.user.nama',
                            name: 'order.user.nama'
                        }, {
                            data: 'order.nomor',
                            name: 'order.nomor'
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
                            name: 'action'
                        }
                    ]
                });
            });

            
            $('#field-order_id').select2({
                dropdownParent: $("#modal-form"),
                ajax: {
                    url: "{{ route('admin.order.select') }}",
                    type: 'POST',
                    dataType: 'JSON',
                    delay: 250,
                    data: function (params) {
                        return {
                            searchTerm: params.term,
                            user_id : $('#field-user_id').val()
                        };
                    },
                    processResults: function (response) {
                        return {
                            results: response
                        };
                    },
                    cache: true
                }
            });

            $("#field-report_tgl").flatpickr({
                altInput: true,
                altFormat: "j F Y",
                dateFormat: "Y-m-d",
                locale : "id",
                defaultDate: [new Date(Date.now() - 7 * 24 * 60 * 60 * 1000), new Date()],
                mode: "range"
            });

            $("#field-tgl").flatpickr({
                altInput: true,
                altFormat: "j F Y",
                dateFormat: "Y-m-d",
                locale : "id",
                enableTime: false,
                time_24hr: true
            });

            $("#formData").on("submit",function (e) {
                e.preventDefault();
                var fomr = $('form#formData')[0];
                var formData = new FormData(fomr);
                let token   = $("meta[name='csrf-token']").attr("content");
                formData.append('_token', token);

                var id = $("#field-id").val();
                var url = (id) ? "/admin/pembayaran/"+id+"/update" : "/admin/pembayaran/store";

                $.ajax({
                    url: url,
                    type: "POST",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        if (response.fail == false) {
                            $('#dataTable').DataTable().ajax.reload();
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

            function resetForm() {
                $('#formData')[0].reset();
                $('#field-id').val('');
                $('#field-order_id').val(null).trigger('change');
                $('#current-bukti').hide();
                $('.form-control').removeClass('is-invalid');
                $('.invalid-feedback').html('');
                $('#modalFormTitle').text('Tambah Pembayaran');
            }

            function addPayment(){
                resetForm();
                var modalForm = bootstrap.Modal.getOrCreateInstance(document.getElementById('modal-form'));
                modalForm.show();
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

            function modalShow(id){
                $.ajax({
                    url: "/admin/pembayaran/"+id,
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
                        if (response.fail == false) {
                            $('#dataTable').DataTable().ajax.reload();
                            var el = document.getElementById('modal-show');
                            var myModal = bootstrap.Modal.getOrCreateInstance(el);
                            myModal.hide();
                            
                            Swal.fire({
                                toast: true,
                                title: "Berhasil",
                                text: "Status berhasil diupdate!",
                                timer: 1500,
                                showConfirmButton: false,
                                icon: 'success',
                                position: 'top-end'
                            });
                        }
                    },
                    error: function (error) {
                    }
                });
            }
            
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
                            url: "/admin/pembayaran/"+ id +"/delete",
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
                                        $('#dataTable').DataTable().ajax.reload();
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

