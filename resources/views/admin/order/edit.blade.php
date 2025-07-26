<x-app-layout>

    @push('styles')
    <link rel="stylesheet" href="/js/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="/js/plugins/flatpickr/flatpickr.min.css">
    @endpush

    <div class="bg-gd-dusk">
        <div class="content text-center">
            <div class="py-4">
                <h1 class="fw-bold text-white mb-2">Ubah Pesanan</h1>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="block block-rounded">
            <div class="block-content p-4">
                <form method="POST" action="{{ route('admin.order.update', $data->id) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-6">
                            <x-select-field id="user_id" label="Konsumen" name="user_id" value="{{ $data->user_id }}" :options="[]"/>
                            <x-input-field type="text" id="tgl" name="tgl" label="Tanggal" value="{{ $data->tgl }}" :required="true"/>
                        </div>
                        <div class="col-6">
                            <x-select-field id="paket_id" label="Paket" name="paket_id" value="{{ $data->paket_id }}" :options="[]"/>
                            <x-select-field id="durasi" label="Durasi" name="durasi" value="{{ $data->durasi }}" :options="[
                                ['value' => 1,'label' => '1 Bulan'],
                                ['value' => 3,'label' => '3 Bulan'],
                                ['value' => 6,'label' => '6 Bulan'],
                                ['value' => 12,'label' => '1 Tahun'],
                            ]"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label>Harga / Bulan</label>
                                <div class="fs-base fw-bold" id="showHarga">Rp {{ number_format($data->harga,0,',','.') }}</div>
                                <input type="hidden" id="field-harga" name="harga"/>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label>Total Pemesanan</label>
                                <div class="fs-base fw-bold" id="showTotal">Rp {{ number_format($data->total,0,',','.') }}</div>
                                <input type="hidden" id="field-total" name="total"/>
                            </div>
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
        const userSelect = $('#field-user_id');
        const paketSelect = $('#field-paket_id');
        userSelect.select2({
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
        if (userSelect.find("option[value='" + '{!! $data->user_id !!}' + "']").length) {
            userSelect.val(data.id).trigger('change');
        } else { 
            // Create a DOM Option and pre-select by default
            // Append it to the select
            userSelect.append(new Option('{!! $data->user->nama !!}', '{!! $data->user_id !!}', true, true)).trigger('change');
        } 

        $('#field-paket_id').select2({
            ajax: {
                url: "{{ route('admin.paket.select') }}",
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

        if (paketSelect.find("option[value='" + '{!! $data->paket_id !!}' + "']").length) {
            paketSelect.val(data.id).trigger('change');
        } else { 
            // Create a DOM Option and pre-select by default
            // Append it to the select
            paketSelect.append(new Option('{!! $data->paket->nama !!}', '{!! $data->paket_id !!}', true, true)).trigger('change');
        } 


        $("#field-paket_id").on("change", function(e){
            e.preventDefault();
            var id = $(this).val();
            
            $.ajax({
                url: "{{ route('admin.paket.json') }}",
                type: "GET",
                data: {
                    id : id,
                    _token : $("meta[name='csrf-token']").attr("content"),
                },
                success: function (data){
                    let num = parseInt(data.harga);
                    let rupiahFormat = num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                    $("#showHarga").html(rupiahFormat);
                    $('#field-harga').val(data.harga);
                    hitungTotal();
                }
            });
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

