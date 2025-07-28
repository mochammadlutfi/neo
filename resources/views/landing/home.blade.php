<x-landing-layout>
    <div class="position-relative d-flex align-items-center">
        <div class="content content-full">
            <div class="row g-6 w-100 py-7 overflow-hidden">
                <div class="col-md-4 order-md-last py-4 d-md-flex align-items-md-center justify-content-md-end">
                    <img class="img-fluid" src="/images/mobile.png" alt="Hero Promo">
                </div>
                <div class="col-md-8 py-4 d-flex align-items-center">
                    <div class="text-center text-md-start">
                        <h1 class="fw-bold fs-2 mb-3">
                            Social Profil Management
                        </h1>
                        <p class="text-muted fw-medium mb-4">
                            Tingkatkan penjualan bisnis Anda menggunakan Jasa Pengelolaan Media Sosial. Ada tidak perlu repot, Team NEO Agency Advertising akan melakukannya untuk Anda.</a>.
                        </p>
                        <a class="btn btn-gd-main py-3 px-4" href="#harga">
                            <i class="fa fa-arrow-right opacity-50 me-1"></i> Pesan Sekarang
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="bg-body-light">
        <div class="content content-full" id="harga">
            <div class="pt-5 pb-5">
                <div class="pb-4 position-relative">
                    <h2 class="fw-bold text-center mb-2">
                        Harga
                    </h2>
                    <p class="text-center fs-base text-muted">Tidak Puas Dengan Layanan Kami ?<br/>Kami Beri Garansi Uang Kembali Berlaku dari 30 Hari Sejak Tanggal Pembelian</p>
                </div>
                <div class="row py-4 justify-content-center">
                    @foreach ($paket as $d)
                    <div class="col-md-6 col-xl-3">
                        <!-- Startup Plan -->
                        <div class="block block-link-pop block-rounded block-bordered text-center" href="javascript:void(0)">
                            <div class="block-header">
                                <h3 class="block-title fs-lg fw-bold">{{ $d->nama }}</h3>
                            </div>
                            <div class="block-content p-3 bg-body-light">
                                <div class="fs-2 fw-bold mb-1">
                                    Rp {{ number_format($d->harga,0,',','.') }}</div>
                                <div class="fs-5 text-muted mb-2">per bulan</div>
                                <p class="text-sm mb-2">{{ $d->deskripsi }}</p>
                            </div>
                            <div class="block-content text-start">
                                <ul class="fa-ul">
                                    @foreach(explode(',', $d->fitur) as $f)
                                    <li><i class="fa fa-check fa-li"></i>{{ $f }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="block-content block-content-full">
                                <button class="btn btn-gd-main" onclick="openModal({{ json_encode($d) }})">
                                    <i class="fa fa-arrow-up opacity-50 me-1"></i> Pesan Sekarang
                                </button>
                            </div>
                        </div>
                        <!-- END Startup Plan -->

                        <!-- Modal -->
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalOrder" tabindex="-1" aria-labelledby="modalOrderLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-lg">
            <div class="modal-content">
                <form method="POST" action="{{ route('user.order.register') }}">
                    @csrf
                    <input type="hidden" name="paket_id" id="field-id"/>
                    <input type="hidden" name="harga" id="field-harga"/>
                    <input type="hidden" name="total" id="field-total"/>
                    <div class="block block-rounded shadow-none mb-0">
                        <div class="block-header block-header-default">
                            <h3 class="block-title">Pesan Paket {{ $d->nama }}</h3>
                            <div class="block-options">
                                <button type="button" class="btn-block-option" data-bs-dismiss="modal"
                                    aria-label="Close">
                                    <i class="fa fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="block-content p-3">
                            <div class="row mb-3">
                                <div class="col-3 d-flex">
                                    <div class="my-auto">
                                        Harga
                                    </div>
                                </div>
                                <div class="col-8 fw-semibold">
                                    : <span id="showHarga">Rp 0</span> / Bulan
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-3 d-flex">
                                    <div class="my-auto">
                                        Durasi
                                    </div>
                                </div>
                                <div class="col-8 fw-semibold d-flex">
                                    : <select class="form-control ms-1" id="field-lama" name="lama">
                                        @for($i =1; $i <= 12; $i++)
                                        <option value="{{ $i }}">{{$i}} Bulan</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-3 d-flex">
                                    <div class="my-auto">
                                        Total
                                    </div>
                                </div>
                                <div class="col-8 fw-semibold">
                                    : <span id="showTotal">Rp 0</span>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-3 d-flex">
                                    <div class="my-auto">
                                        Minimal Pembayaran
                                    </div>
                                </div>
                                <div class="col-8 fw-semibold">
                                    : <span id="showDP">Rp 0</span>
                                </div>
                            </div>
                        </div>
                        <div
                            class="block-content block-content-full block-content-sm text-end border-top">
                            <button type="button" class="btn rounded-pill btn-danger px-3" data-bs-dismiss="modal">
                                Batal
                            </button>
                            <button type="submit" class="btn rounded-pill btn-primary px-3">
                                Lanjut Pembayaran
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            var el = $('#modalOrder');

            function openModal(param){
                el.find('.block-title').html(`Pesan Paket ${param.nama}`);
                el.find("#showHarga").html(formatRupiah(param.harga));
                el.find('#field-harga').val(param.harga);
                el.find('#field-id').val(param.id);

                var total = $("#field-lama").val() * el.find("#field-harga").val();
                el.find("#showTotal").html(formatRupiah(total));
                el.find('#field-total').val(total);

                // Calculate minimal DP (50% of total)
                var minimalDP = Math.ceil(total * 0.5);
                el.find("#showDP").html(formatRupiah(minimalDP) + " (50%)");

                var myModal = bootstrap.Modal.getOrCreateInstance(el);
                myModal.show();
            }

            $("#field-lama").on('change', function(e){
                e.preventDefault();

                var total = $("#field-lama").val() * el.find("#field-harga").val();
                el.find("#showTotal").html(formatRupiah(total));
                el.find('#field-total').val(total);

                // Recalculate minimal DP when duration changes
                var minimalDP = Math.ceil(total * 0.5);
                el.find("#showDP").html(formatRupiah(minimalDP) + " (50%)");
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
</x-landing-layout>