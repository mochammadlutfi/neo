<x-app-layout>

    @push('styles')
    <link rel="stylesheet" href="/js/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="/js/plugins/flatpickr/flatpickr.min.css">
    @endpush
    <div class="content">
        <div class="block block-rounded">
            <div class="block-header">
                <h3 class="block-title fs-base fw-bold">
                    <i class="fa fa-plus me-2 text-primary"></i>
                    Tambah Manajemen Konten
                </h3>
                <div class="block-options">
                    <a href="{{ route('admin.project.index') }}" class="btn btn-sm btn-secondary fs-base me-2">
                        <i class="fa fa-arrow-left me-1"></i>
                        Kembali
                    </a>
                    <button type="submit" form="projectForm" class="btn btn-sm btn-primary fs-base">
                        <i class="fa fa-save me-1"></i>
                        Simpan
                    </button>
                </div>
            </div>
        </div>
        <div class="block block-rounded">
            <div class="block-content p-4">
                <form method="POST" action="{{ route('admin.project.store') }}" enctype="multipart/form-data" id="projectForm">
                    @csrf
                    <div class="row">
                        <div class="col-6">
                            <x-select-field id="user_id" label="Konsumen" name="user_id" placeholder="Pilih" :options="[]"/>
                            <x-input-field type="text" id="nama" name="nama" label="Nama Project" :required="true"/>
                        </div>
                        <div class="col-6">
                            <x-select-field id="order_id" label="No Pemesanan" name="order_id" placeholder="Pilih" :options="[]" disabled/>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-check me-1"></i>
                        Simpan
                    </button>
                </form>
            </div>
        </div>
    </div>


    @push('scripts')
    <script src="/js/plugins/select2/js/select2.full.min.js"></script>
    <script src="/js/plugins/flatpickr/flatpickr.min.js"></script>
    <script src="/js/plugins/flatpickr/l10n/id.js"></script>
    <script>

        $('#field-user_id').select2({
            ajax: {
                url: "{{ route('admin.user.select') }}",
                type: 'POST',
                dataType: 'JSON',
                delay: 250,
                data: function (params) {
                    return {
                        searchTerm: params.term
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
        $('#field-order_id').select2({
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


        $("#field-user_id").on("change", function(e){
            e.preventDefault();
            var id = $(this).val();
            // $("#field-order_id").val();
            $("#field-order_id").trigger('clear');
            if(id){
                $("#field-order_id").prop("disabled", false);
            }else{
                $("#field-order_id").prop( "disabled", true );
            }
        });

        $('#field-jadwal').select2({
            multiple : true,
        });
        
        $("#field-tgl").flatpickr({
            altInput: true,
            altFormat: "j F Y",
            dateFormat: "Y-m-d",
            locale : "id",
            enableTime: false,
            time_24hr: true
        });

        $("#field-durasi").on("change", function(e){
            e.preventDefault();
            hitungTotal();
        });

        function hitungTotal(){
            var lama = $('#field-durasi').val();
            var harga = $('#field-harga').val();
            var total = harga * lama;

            $("#showTotal").text(formatRupiah(total, 'Rp. '));
            $("#field-total").val(total);
        }

        function formatRupiah(angka, prefix){
            var number_string = angka.toString(),
            split   		= number_string.split(','),
            sisa     		= split[0].length % 3,
            rupiah     		= split[0].substr(0, sisa),
            ribuan     		= split[0].substr(sisa).match(/\d{3}/gi);

            if(ribuan){
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return prefix == undefined ? rupiah : (rupiah ? 'Rp ' + rupiah : '');
        }
    </script>
    @endpush
</x-app-layout>

