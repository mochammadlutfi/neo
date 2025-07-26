<x-app-layout>
    @push('styles')
    @endpush

    <div class="content">
        <form method="POST" action="{{ route('admin.user.store') }}">
            @csrf
            <div class="content-heading d-flex justify-content-between align-items-center">
                <span>Tambah Pesanan Baru</span>
                <div class="space-x-1">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-check me-1"></i>
                        Simpan
                    </button>
                </div>
            </div>
            <div class="block block-rounded">
                <div class="block-content">
                    <input type="hidden" name="is_member" value="0" id="userReguler">
                    <div class="row mb-4">
                        <label class="col-sm-3 col-form-label"  for="field-user_id">Pelanggan</label>
                        <div class="col-sm-6">
                            <select class="form-select  {{ $errors->has('user_id') ? 'is-invalid' : '' }}"
                                id="field-user_id" style="width: 100%;" name="user_id"
                                data-placeholder="Pilih Pelanggan">
                                <option></option>
                                @foreach ($pelanggan as $p)
                                <option value="{{ $p->id }}"
                                    {{ (old('user_id') == $p->id) ? 'selected="selected"' : '' }}>
                                    {{ $p->nama }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('user_id')" class="mt-2" />
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-sm-3 col-form-label" for="field-tgl">Tanggal</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control {{ $errors->has('tgl') ? 'is-invalid' : '' }}"
                                id="field-tgl" name="tgl" placeholder="Masukan Tanggal"
                                value="{{ old('tgl') }}">
                            <x-input-error :messages="$errors->get('tgl')" class="mt-2" />
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-sm-3 col-form-label" for="field-lama">Lama Main</label>
                        <div class="col-sm-6">
                            <select class="form-select {{ $errors->has('lama') ? 'is-invalid' : '' }}"
                                id="field-lama" style="width: 100%;" name="lama">
                                <option value="1" {{ old('lama' == 0) ? 'selected' : '' }}>1 Jam</option>
                                <option value="2" {{ old('lama' == 1) ? 'selected' : '' }}>2 Jam</option>
                                <option value="3" {{ old('lama' == 1) ? 'selected' : '' }}>3 Jam</option>
                                <option value="4" {{ old('lama' == 1) ? 'selected' : '' }}>4 Jam</option>
                                <option value="5" {{ old('lama' == 1) ? 'selected' : '' }}>5 Jam</option>
                            </select>
                            <x-input-error :messages="$errors->get('lama')" class="mt-2" />
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-sm-3 col-form-label" for="field-waktu">Waktu Mulai</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control {{ $errors->has('waktu') ? 'is-invalid' : '' }}"
                                id="field-waktu" name="waktu" placeholder="Masukan Alamat waktu"
                                value="{{ old('waktu') }}">
                            <x-input-error :messages="$errors->get('waktu')" class="mt-2" />
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-sm-3 col-form-label" for="field-alamat">Harga / Jam</label>
                        <div class="col-sm-6">
                            Rp <span id="hargaDisp"><?= number_format(100000,0,',','.'); ?></span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label">Total Harga</label>
                        <div class="col-sm-8">
                            <span id="totHarga">Rp 0</span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label">Diskon</label>
                        <div class="col-sm-8">
                            <input type="hidden" name="diskon" class="form-control" id="field-diskon">
                            <span id="totDiskon">Rp 0</span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label">Total Pembayaran</label>
                        <div class="col-sm-8">
                            <span id="totBayar">Rp 0</span>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        $('#field-user_id').select2();

        
        $("#field-user_id").on("change", function(e){
            var id = $(this).val();
            $.ajax({
                url: "/admin/pelanggan/json/"+ id,
                type: 'GET',
                cache: false,
                contentType: false,
                processData: false,
                success: function (response) {
                    console.log(response);
                    $('#userReguler').val(response.is_member);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error adding / update data');
                }
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

        $("#field-waktu").flatpickr({
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            time_24hr: true,
            minTime: "8:00",
            maxTime: "00:00",
        });
        
        $("#field-waktu").on("change", function(e){
            var waktu = $(this).val();
            var tgl = $('#field-tgl').val();
            var durasi = $('#field-lama').val();
            $("#btn-simpan").prop("disabled", true);
            
            $.ajax({
                url: "{{ route('admin.booking.cek') }}",
                type: "GET",
                data: {
                    tgl : tgl,
                    durasi : durasi,
                    waktu : waktu,
    	            _token: "{{ csrf_token() }}"
                },
                cache: false,
                contentType: false,
                processData: false,
                dataType : 'json',
                success: function (data){
                    obj = JSON.parse(data);
                    if (obj.status == false) {
                        alert('Waktu main sudah ada yang pesan!');
                    }else{
                        let num = parseInt(obj.harga);
                        let rupiahFormat = num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                        $("#hargaDisp").html(rupiahFormat)
                        $("#field-harga").val(obj.harga);
                        $("#btn-simpan").prop("disabled", false);
                    }
                }
            });
        })

        $("#field-lama").on("change", function(e){
            var val = $(this).val();
            var harga = 100000;
            var total = harga * val;
            
            var reguler = $('#userReguler').val();
            var diskon = 0;
            if(reguler == '1'){
                diskon = (total * 10/100);
            }
            console.log(reguler);

            var totBayar = total - diskon;

            var totalBayar = (total - diskon);
            $("#input-waktu").removeClass('d-none');
            $("#totHarga").text(formatRupiah(total, 'Rp. '));

            $("#field-diskon").val(diskon);
            $("#totDiskon").text(formatRupiah(diskon, 'Rp. '));
            $("#totBayar").text(formatRupiah(totBayar, 'Rp. '));
        });

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

