<x-landing-layout>
    <div class="content">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <!-- Order Header -->
                <div class="block block-rounded">
                    <div class="block-header">
                        <h3 class="block-title fs-base fw-bold">
                            <i class="fa fa-credit-card me-2 text-primary"></i>
                            Pembayaran Pesanan {{ $data->nomor }}
                        </h3>
                        <div class="block-options">
                            <span class="fs-base text-muted">Batas: {{ \Carbon\Carbon::parse($data->tgl_tempo)->translatedFormat('d F Y') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Single Payment Card -->
                <div class="block block-rounded">
                    <div class="block-header">
                        <h3 class="block-title fs-base fw-bold">Form Pembayaran</h3>
                    </div>
                    <div class="block-content">
                        <!-- Order Summary -->
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="d-flex align-items-center p-3 bg-body-light rounded">
                                    <div class="flex-shrink-0">
                                        <i class="fa fa-box fa-2x text-primary"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <div class="fs-base fw-bold mb-1">{{ $data->paket->nama }}</div>
                                        <div class="fs-xs text-muted">{{ $data->durasi }} Bulan</div>
                                        <div>
                                            @if($data->status_pembayaran == 'Belum Bayar')
                                                <span class="badge bg-danger fs-xs">Belum Bayar</span>
                                            @elseif ($data->status_pembayaran == 'Sebagian')
                                                <span class="badge bg-warning fs-xs">Sebagian</span>
                                            @else
                                                <span class="badge bg-success fs-xs">Lunas</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3 bg-light rounded">
                                    <div class="fs-base fw-bold mb-2">Ringkasan Tagihan</div>
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="fs-sm">Total Tagihan:</span>
                                        <span class="fs-sm fw-bold">Rp {{ number_format($data->total,0,',','.') }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="fs-sm">Sudah Dibayar:</span>
                                        <span class="fs-sm text-success">Rp {{ number_format($data->payment->where('status', 'terima')->sum('jumlah'),0,',','.') }}</span>
                                    </div>
                                    <hr class="my-2">
                                    <div class="d-flex justify-content-between">
                                        <span class="fs-sm fw-bold">Sisa Tagihan:</span>
                                        <span class="fs-sm fw-bold text-danger">Rp {{ number_format($data->total - $data->payment->where('status', 'terima')->sum('jumlah'),0,',','.') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3 bg-warning-light rounded">
                                    <div class="fs-base fw-bold mb-2">Informasi</div>
                                    <div class="fs-sm text-muted">
                                        @php
                                            $paid = $data->payment->where('status', 'terima')->sum('jumlah');
                                            $halfTotal = $data->total * 0.5;
                                            $minPayment = $paid < $halfTotal ? $halfTotal - $paid : 10000;
                                        @endphp
                                        • Min: Rp {{ number_format($minPayment,0,',','.') }}<br>
                                        • Pembayaran bertahap<br>
                                        • Upload bukti transfer<br>
                                        • Verifikasi 1x24 jam
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Bank Information -->
                        <div class="alert alert-info mb-4">
                            <div class="fs-base fw-bold mb-3"><i class="fa fa-university me-1"></i> Informasi Rekening</div>
                            <div class="row align-items-center">
                                <div class="col-md-2 text-center">
                                    <img src="/images/bni.png" class="img-fluid" style="max-height: 50px;">
                                </div>
                                <div class="col-md-10">
                                    <div class="fs-base"><strong>Bank BNI</strong></div>
                                    <div class="fs-base font-monospace fw-bold">0604918708</div>
                                    <div class="fs-base">a.n Muh. Iqbal</div>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Form -->
                        <form id="form-payment" method="POST" action="{{ route('user.order.update', $data->id) }}" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="field-tgl" class="form-label fs-base">Tanggal Pembayaran <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control fs-base" id="field-tgl" name="tgl" value="{{ date('Y-m-d') }}" required>
                                        <div class="invalid-feedback" id="error-tgl"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="field-jumlah" class="form-label fs-base">Jumlah Pembayaran <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text fs-base">Rp</span>
                                            <input type="number" class="form-control fs-base" id="field-jumlah" name="jumlah" 
                                                placeholder="Masukan jumlah pembayaran" 
                                                min="{{ $minPayment }}" 
                                                max="{{ $data->total - $data->payment->where('status', 'terima')->sum('jumlah') }}" 
                                                required>
                                        </div>
                                        <div class="form-text fs-base">
                                            Min: Rp {{ number_format($minPayment,0,',','.') }} | 
                                            Max: Rp {{ number_format($data->total - $data->payment->where('status', 'terima')->sum('jumlah'),0,',','.') }}
                                        </div>
                                        <div class="invalid-feedback" id="error-jumlah"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fs-base" for="field-bukti">Bukti Pembayaran <span class="text-danger">*</span></label>
                                <input class="form-control fs-base" type="file" name="bukti" id="field-bukti" accept="image/*" required>
                                <div class="form-text fs-base">Upload foto/screenshot bukti transfer (JPG, PNG, max 2MB)</div>
                                <div class="invalid-feedback" id="error-bukti"></div>
                            </div>

                            <div class="text-end">
                                <a href="{{ route('user.order.index') }}" class="btn btn-secondary fs-base me-2">
                                    <i class="fa fa-arrow-left me-1"></i> Kembali
                                </a>
                                <button type="submit" class="btn btn-success fs-base" id="btn-submit-payment">
                                    <i class="fa fa-paper-plane me-1"></i> Kirim Pembayaran
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            $(document).ready(function() {
                // Handle form submission
                $('#form-payment').on('submit', function(e) {
                    e.preventDefault();
                    
                    const submitBtn = $('#btn-submit-payment');
                    const originalText = submitBtn.html();
                    
                    // Show loading state
                    submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-1"></i> Memproses...');
                    
                    // Clear previous errors
                    clearValidationErrors();
                    
                    // Create FormData for file upload
                    const formData = new FormData(this);
                    
                    $.ajax({
                        url: $(this).attr('action'),
                        method: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            if (response.fail === false) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: 'Pembayaran berhasil dikirim. Kami akan memverifikasi dalam 1x24 jam.',
                                    confirmButtonText: 'OK'
                                }).then(() => {
                                    window.location = "{{ route('user.order.index') }}";
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: 'Terjadi kesalahan saat mengirim pembayaran.',
                                    confirmButtonText: 'OK'
                                });
                            }
                        },
                        error: function(xhr) {
                            if (xhr.status === 422) {
                                const errors = xhr.responseJSON.errors;
                                displayValidationErrors(errors);
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: 'Terjadi kesalahan server. Silakan coba lagi.',
                                    confirmButtonText: 'OK'
                                });
                            }
                        },
                        complete: function() {
                            submitBtn.prop('disabled', false).html(originalText);
                        }
                    });
                });

                // File input validation
                $('#field-bukti').on('change', function() {
                    const file = this.files[0];
                    if (file) {
                        if (file.size > 2097152) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'File Terlalu Besar',
                                text: 'Ukuran file maksimal 2MB',
                                confirmButtonText: 'OK'
                            });
                            $(this).val('');
                            return;
                        }
                        
                        if (!file.type.match('image.*')) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Format File Salah',
                                text: 'Hanya file gambar yang diperbolehkan',
                                confirmButtonText: 'OK'
                            });
                            $(this).val('');
                            return;
                        }
                    }
                });
            });

            // Function to clear validation errors
            function clearValidationErrors() {
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').empty();
            }

            // Function to display validation errors
            function displayValidationErrors(errors) {
                $.each(errors, function(field, messages) {
                    const input = $('[name="' + field + '"]');
                    const errorDiv = $('#error-' + field);
                    
                    input.addClass('is-invalid');
                    errorDiv.text(messages[0]);
                });
            }
        </script>
    @endpush
</x-landing-layout>