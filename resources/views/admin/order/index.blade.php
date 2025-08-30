<x-app-layout>
    <div class="content">
        <!-- Page Header -->
        <div class="block block-rounded">
            <div class="block-header">
                <h3 class="block-title fs-base fw-bold">
                    <i class="fa fa-shopping-cart me-2 text-primary"></i>
                    Kelola Pesanan
                </h3>
                <div class="block-options">
                    {{-- @if(in_array(auth()->guard('admin')->user()->level, ['Marketing']))
                    <a href="{{ route('admin.order.create') }}" class="btn btn-sm btn-primary fs-base me-2">
                        <i class="fa fa-plus me-1"></i>
                        Tambah
                    </a>
                    @endif --}}
                    <button type="button" class="btn btn-sm btn-info fs-base" data-bs-toggle="modal" data-bs-target="#reportModal">
                        <i class="fa fa-print me-1"></i>
                        Download Report
                    </button>
                </div>
            </div>
        </div>
        <div class="block block-rounded">
            <div class="block-content p-3">
                <table class="table table-bordered datatable w-100 table-vcenter">
                    <thead>
                        <tr>
                            <th width="200px">Tgl Pemesanan</th>
                            <th width="250px">Nomor</th>
                            <th width="300px">Konsumen</th>
                            <th width="200px">Paket</th>
                            <th width="200px">Durasi</th>
                            <th width="150px">Status</th>
                            <th width="200px">Tgl Selesai</th>
                            <th width="60px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="fs-sm">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.order.report') }}" method="GET">
                    <div class="block block-rounded shadow-none mb-0">
                        <div class="block-header bg-gd-dusk">
                            <h3 class="block-title text-white" id="modalFormTitle">Download Report</h3>
                            <div class="block-options">
                                <button type="button" class="text-white btn-block-option" data-bs-dismiss="modal" aria-label="Close">
                                    <i class="fa fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="block-content">
                            <x-input-field type="text" name="tgl" id="tgl" label="Periode Tanggal"/> 
                        </div>
                        <div class="block-content block-content-full block-content-sm text-end border-top">
                            <button type="button" class="btn btn-alt-secondary" data-bs-dismiss="modal">
                              Batal
                            </button>
                            <button type="submit" class="btn btn-gd-main">
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
                $('.datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    order: [[0, 'desc']],
                    dom : "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>><'row'<'col-sm-12'tr>><'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                    ajax: "{{ route('admin.order.index') }}",
                    columns: [
                        {data: 'tgl', name: 'tgl'},
                        {data: 'nomor', name: 'nomor'},
                        {data: 'user.nama', name: 'user.nama'},
                        {data: 'paket.nama', name: 'paket.nama'},
                        {data: 'durasi', name: 'durasi'},
                        {data: 'status', name: 'status'},
                        {data: 'tgl_selesai', name: 'tgl_selesai'},
                        {
                            data: 'action', 
                            name: 'action', 
                            orderable: false, 
                            searchable: false
                        },
                    ]
                });
            });
            
        $("#field-tgl").flatpickr({
            altInput: true,
            altFormat: "j F Y",
            dateFormat: "Y-m-d",
            locale : "id",
            defaultDate: [new Date(Date.now() - 7 * 24 * 60 * 60 * 1000), new Date()],
            mode: "range"
        });
        
        $("#modal-status").on("submit",function (e) {
            e.preventDefault();
            var fomr = $('form#form-status')[0];
            var formData = new FormData(fomr);
            let token   = $("meta[name='csrf-token']").attr("content");
            formData.append('_token', token);

            $.ajax({
                url: "/admin/Produk/status",
                type: "POST",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function (response) {
                    if (response.fail == false) {
                        $('.datatable').DataTable().ajax.reload();
                        const el = document.getElementById('modal-status');
                        var myModal = bootstrap.Modal.getOrCreateInstance(el);
                        myModal.hide();
                        fomr.reset();
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

        function edit(id, status){

            $("#field-id").val(id);
            $("#field-status").val(status);

            const el = document.getElementById('modal-status');
            const modalForm = bootstrap.Modal.getOrCreateInstance(el);
            modalForm.show();
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
                        url: "/admin/Produk/"+ id +"/delete",
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
                                    window.location.replace("{{ route('admin.user.index') }}");
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

