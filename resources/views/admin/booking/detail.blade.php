<x-app-layout>
    <div class="content">
        <div class="content-heading d-flex justify-content-between align-items-center">
            <span>Detail Pesanan</span>
            <div class="space-x-1">

            </div>
        </div>
        <div class="block block-rounded">
            <div class="block-header">
                <h3 class="block-title">Informasi Pemesanan</h3>

            </div>
            <div class="block-content p-4">
                <input type="hidden" id="field-booking_id" value="{{ $data->id }}"/>
                <div class="row mb-2">
                    <label class="col-sm-3 fw-medium">Nomor</label>
                    <div class="col-sm-6">
                        :
                        {{ $data->nomor }}
                    </div>
                </div>

                <div class="row mb-2">
                    <label class="col-sm-3 fw-medium">Status</label>
                    <div class="col-sm-6">
                        : @if($data->status == 'belum bayar')
                        <span class="badge bg-danger">Belum Bayar</span>
                        @elseif($data->status == 'sebagian')
                        <span class="badge bg-warning">Sebagian</span>
                        @elseif($data->status == 'pending')
                        <span class="badge bg-info">Menunggu Konfirmasi</span>
                        @elseif($data->status == 'lunas')
                        <span class="badge bg-success">Lunas</span>
                        @elseif($data->status == 'batal')
                        <span class="badge bg-secondary">Batal</span>
                        @endif
                    </div>
                </div>

                <div class="row mb-2">
                    <label class="col-sm-3 fw-medium">Tanggal Main</label>
                    <div class="col-sm-6">
                        :
                        {{ Carbon\Carbon::parse($data->tgl)->translatedFormat('d F Y') }}
                    </div>
                </div>

                <div class="row mb-2">
                    <label class="col-sm-3 fw-medium">Waktu Main</label>
                    <div class="col-sm-6">
                        :
                        {{ Carbon\Carbon::parse($data->mulai)->format('d F Y') }}
                    </div>
                </div>

                <div class="row mb-2">
                    <label class="col-sm-3 fw-medium">Lama Main</label>
                    <div class="col-sm-6">
                        :
                        {{ $data->lama }}
                        Jam
                    </div>
                </div>

                <div class="row mb-2">
                    <label class="col-sm-3 fw-medium">Harga/Jam</label>
                    <div class="col-sm-6">
                        : Rp
                        {{ number_format($data->harga,0,',','.') }}
                    </div>
                </div>

                <div class="row mb-2">
                    <label class="col-sm-3 fw-medium">Diskon</label>
                    <div class="col-sm-6">
                        : Rp
                        {{ number_format($data->diskon,0,',','.') }}
                    </div>
                </div>
                <div class="row mb-2">
                    <label class="col-sm-3 fw-medium">Total Tagihan</label>
                    <div class="col-sm-6">
                        : Rp
                        {{ number_format($data->total_bayar,0,',','.') }}
                    </div>
                </div>
                <div class="row mb-2">
                    <label class="col-sm-3 fw-medium">Total Dibayar</label>
                    <div class="col-sm-6">
                        : Rp
                        {{ number_format($data->bayar_sum_jumlah,0,',','.') }}
                    </div>
                </div>
                <div class="row mb-2">
                    <label class="col-sm-3 fw-medium">Sisa Tagihan</label>
                    <div class="col-sm-6">
                        : Rp
                        {{ number_format($data->total_bayar - $data->bayar_sum_jumlah,0,',','.') }}
                    </div>
                </div>
            </div>
        </div>
        <div class="block block-rounded">
            <div class="block-header">
                <h3 class="block-title">Informasi Pembayaran</h3>
                <div class="block-options">
                    <button
                        type="button"
                        class="btn btn-sm btn-primary"
                        data-bs-toggle="modal"
                        data-bs-target="#modal-normal">
                        Tambah Pembayaran
                    </button>
                </div>
            </div>
            <div class="block-content p-4">
                <table class="table table-bordered datatable w-100 table-vcenter">
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
                                    <label for="field-tgl">Tanggal</label>
                                    <input type="text" class="form-control" id="field-tgl" name="tgl" placeholder="Masukan Tanggal">
                                    <div class="invalid-feedback" id="error-tgl">Invalid feedback</div>
                                </div>
                                <div class="mb-4">
                                    <label for="field-jumlah">Jumlah</label>
                                    <input type="number" value="{{ $data->total_bayar - $data->bayar_sum_jumlah }}" class="form-control" id="field-jumlah" name="jumlah" placeholder="Masukan Jumlah">
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
            <div class="modal-dialog" role="document">
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
                            <div class="block-content fs-sm" id="detailPembayaran">
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
            var table = $('.datatable').DataTable({
                processing: true,
                serverSide: true,
                dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>><'row'<'col-sm-12'tr>><'r" +
                        "ow'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                ajax: {
                    url : "{{ route('admin.payment.index') }}",
                    data : function(data){
                        data.booking_id = "{{ $data->id }}";
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
        });

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

