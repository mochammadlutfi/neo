<x-landing-layout>

    <div class="content">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="block">
                    <div class="block-content text-center p-4">
                        <h3>Menunggu Pembayaran</h3>
                        <p>Mohon selesaikan pembayaran anda sebelum tanggal <b>{{ \Carbon\Carbon::parse($data->tgl)->addDay(7)->translatedFormat('d F Y') }}</b></p>
                        <div class="g-2 row justify-content-center">
                            <div class="col-3 d-flex text-end">
                                <img src="/images/bni.png" class="w-100">
                            </div>
                            <div class="col-2 text-start">
                                <div class="fs-base fw-semibold">No Rekening</div>
                                <div class="fs-base fw-bold">0604918708</div>
                                <div class="fs-sm">A.n Muh. Iqbal</div>
                            </div>
                        </div>
                        <p>Jumlah yang harus dibayar sebesar:</p>
                        <h3 class="fs-3">
                            Rp {{ number_format($data->total,0,',','.') }}
                        </h3>
                        <p>Jika sudah melakukan pembayaran silahkan konfirmasi pembayaran disini</p>
                        <button class="btn btn-gd-main px-3 rounded-pill"  data-bs-toggle="modal" data-bs-target="#modal-form">
                            Konfirmasi Pembayaran
                        </button>
                    </div>
                </div>
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
                            <div class="row">
                                <div class="col-md-6">
                                    <x-input-field type="text" id="tgl" name="tgl" label="Tanggal" :required="true" isAjax/>
                                </div>
                                <div class="col-md-6">
                                    <x-input-field type="text" id="jumlah" name="jumlah" label="Jumlah" value="{{ $data->total }}" :required="true" isAjax/>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="form-label" for="field-bukti">Bukti Bayar</label>
                                <input class="form-control" type="file" name="bukti" id="field-bukti">
                                <div class="invalid-feedback" id="error-bukti">Invalid feedback</div>
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

    @push('scripts')
        <script>

        $("#field-tgl").flatpickr({
            altInput: true,
            altFormat: "j F Y",
            dateFormat: "Y-m-d",
            locale : "id",
            enableTime: false,
            time_24hr: true,
            defaultDate : new Date()
        });

            $("#formData").on("submit",function (e) {
                e.preventDefault();
                var fomr = $('form#formData')[0];
                var formData = new FormData(fomr);
                let token   = $("meta[name='csrf-token']").attr("content");
                formData.append('_token', token);

                var url = "{{ route('user.order.update', $data->id)}}";

                $.ajax({
                    url: url,
                    type: "POST",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        if (response.fail == false) {
                            window.location = "{{ route('user.order.index')}}";
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
</x-landing-layout>