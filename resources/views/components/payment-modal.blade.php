@props(['orderId' => null, 'orderNumber' => '', 'modalId' => 'paymentModal'])

<!-- Payment Modal -->
<div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-labelledby="{{ $modalId }}Label" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="block block-rounded shadow-none mb-0">
                <form id="form-payment-{{ $modalId }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="block-header block-header-default">
                        <h3 class="block-title">
                            <i class="fa fa-credit-card me-2"></i>
                            Form Pembayaran - <span id="{{ $modalId }}-order-number">{{ $orderNumber }}</span>
                        </h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">
                                <i class="fa fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content">
                        <!-- Payment Summary -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded">
                                    <div class="fs-base fw-bold mb-2">Ringkasan Tagihan</div>
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="fs-base">Total Tagihan:</span>
                                        <span class="fs-base fw-bold" id="{{ $modalId }}-total-amount">Rp 0</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="fs-base">Sudah Dibayar:</span>
                                        <span class="fs-base text-success" id="{{ $modalId }}-paid-amount">Rp 0</span>
                                    </div>
                                    <hr class="my-2">
                                    <div class="d-flex justify-content-between">
                                        <span class="fs-base fw-bold">Sisa Tagihan:</span>
                                        <span class="fs-base fw-bold text-danger" id="{{ $modalId }}-remaining-amount">Rp 0</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 bg-warning-light rounded">
                                    <div class="fs-base fw-bold mb-2">Informasi Pembayaran</div>
                                    <div class="fs-base text-muted">
                                        • Minimal pembayaran: <span id="{{ $modalId }}-min-payment">Rp 0</span> (50%)<br>
                                        • Pembayaran dapat dilakukan bertahap<br>
                                        • Upload bukti transfer yang jelas<br>
                                        • Pembayaran akan diverifikasi dalam 1x24 jam
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Form -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="{{ $modalId }}-field-tgl" class="form-label fs-base">Tanggal Pembayaran <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control fs-base" id="{{ $modalId }}-field-tgl" name="tgl" value="{{ date('Y-m-d') }}" required>
                                    <div class="invalid-feedback" id="{{ $modalId }}-error-tgl"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="{{ $modalId }}-field-jumlah" class="form-label fs-base">Jumlah Pembayaran <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text fs-base">Rp</span>
                                        <input type="number" class="form-control fs-base" id="{{ $modalId }}-field-jumlah" name="jumlah" 
                                            placeholder="Masukan jumlah pembayaran" required>
                                    </div>
                                    <div class="form-text fs-base" id="{{ $modalId }}-payment-range">
                                        Min: Rp 0 | Max: Rp 0
                                    </div>
                                    <div class="invalid-feedback" id="{{ $modalId }}-error-jumlah"></div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fs-base" for="{{ $modalId }}-field-bukti">Bukti Pembayaran <span class="text-danger">*</span></label>
                            <input class="form-control fs-base" type="file" name="bukti" id="{{ $modalId }}-field-bukti" accept="image/*" required>
                            <div class="form-text fs-base">Upload foto/screenshot bukti transfer (JPG, PNG, max 2MB)</div>
                            <div class="invalid-feedback" id="{{ $modalId }}-error-bukti"></div>
                        </div>

                        <!-- Bank Information -->
                        <div class="alert alert-info">
                            <div class="fs-base fw-bold mb-2"><i class="fa fa-university me-1"></i> Informasi Rekening</div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="fs-base"><strong>Bank BNI</strong></div>
                                    <div class="fs-base font-monospace">0604918708</div>
                                    <div class="fs-base">a.n Muh. Iqbal</div>
                                </div>
                                {{-- <div class="col-md-6">
                                    <div class="fs-base"><strong>Bank Mandiri</strong></div>
                                    <div class="fs-base font-monospace">0987654321</div>
                                    <div class="fs-base">a.n. Kalaman Multimedia Karya</div>
                                </div> --}}
                            </div>
                        </div>
                        
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary fs-base" data-bs-dismiss="modal">
                                <i class="fa fa-times me-1"></i> Batal
                            </button>
                            <button type="submit" class="btn btn-success fs-base" id="{{ $modalId }}-btn-submit-payment">
                                <i class="fa fa-paper-plane me-1"></i> Kirim Pembayaran
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@once
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    class PaymentModal {
        constructor(modalId) {
            this.modalId = modalId;
            this.currentOrderId = null;
            this.currentOrderData = {};
            this.init();
        }

        init() {
            const modal = document.getElementById(this.modalId);
            const form = document.getElementById(`form-payment-${this.modalId}`);
            
            if (!modal || !form) return;

            // Clear form when modal is hidden
            $(`#${this.modalId}`).on('hidden.bs.modal', () => {
                form.reset();
                this.clearValidationErrors();
                this.currentOrderId = null;
                this.currentOrderData = {};
            });

            // Handle form submission
            $(`#form-payment-${this.modalId}`).on('submit', (e) => {
                this.handleFormSubmit(e);
            });

            // File input validation
            $(`#${this.modalId}-field-bukti`).on('change', (e) => {
                this.validateFile(e.target);
            });
        }

        openModal(orderId, orderNumber, totalAmount, paidAmount) {
            this.currentOrderId = orderId;
            
            // Convert to numbers
            const total = parseInt(totalAmount);
            const paid = parseInt(paidAmount);
            const remaining = total - paid;
            
            // Minimum payment logic:
            // 1. If no payment made yet: minimum is 50% of total
            // 2. If already paid but less than 50%: minimum is (50% of total - already paid)
            // 3. If already paid 50% or more: minimum is 1 (any amount up to remaining)
            let minPayment;
            const halfTotal = Math.floor(total * 0.5);
            
            if (paid < halfTotal) {
                // Still need to reach 50% minimum
                minPayment = halfTotal - paid;
            } else {
                // Already paid 50% or more, can pay any remaining amount
                minPayment = Math.min(10000, remaining); // Minimum 10k or remaining amount
            }

            // Store data
            this.currentOrderData = {
                total: total,
                paid: paid,
                remaining: remaining,
                minPayment: minPayment
            };

            // Update modal content
            document.getElementById(`${this.modalId}-order-number`).textContent = orderNumber;
            document.getElementById(`${this.modalId}-total-amount`).textContent = 'Rp ' + total.toLocaleString('id-ID');
            document.getElementById(`${this.modalId}-paid-amount`).textContent = 'Rp ' + paid.toLocaleString('id-ID');
            document.getElementById(`${this.modalId}-remaining-amount`).textContent = 'Rp ' + remaining.toLocaleString('id-ID');
            document.getElementById(`${this.modalId}-min-payment`).textContent = 'Rp ' + minPayment.toLocaleString('id-ID');
            document.getElementById(`${this.modalId}-payment-range`).textContent = 
                'Min: Rp ' + minPayment.toLocaleString('id-ID') + ' | Max: Rp ' + remaining.toLocaleString('id-ID');

            // Set form action
            document.getElementById(`form-payment-${this.modalId}`).action = `/user/pesanan/${orderId}`;

            // Set input constraints
            const jumlahInput = document.getElementById(`${this.modalId}-field-jumlah`);
            jumlahInput.min = minPayment;
            jumlahInput.max = remaining;

            // Show modal
            const paymentModal = new bootstrap.Modal(document.getElementById(this.modalId));
            paymentModal.show();
        }

        handleFormSubmit(e) {
            e.preventDefault();
            
            const submitBtn = $(`#${this.modalId}-btn-submit-payment`);
            const originalText = submitBtn.html();
            
            // Show loading state
            submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-1"></i> Memproses...');
            
            // Clear previous errors
            this.clearValidationErrors();
            
            // Create FormData for file upload
            const formData = new FormData(e.target);
            
            $.ajax({
                url: $(e.target).attr('action'),
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: (response) => {
                    if (response.fail === false) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Pembayaran berhasil dikirim. Kami akan memverifikasi dalam 1x24 jam.',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            location.reload();
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
                error: (xhr) => {
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        this.displayValidationErrors(errors);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Terjadi kesalahan server. Silakan coba lagi.',
                            confirmButtonText: 'OK'
                        });
                    }
                },
                complete: () => {
                    submitBtn.prop('disabled', false).html(originalText);
                }
            });
        }

        validateFile(fileInput) {
            const file = fileInput.files[0];
            if (file) {
                if (file.size > 2097152) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'File Terlalu Besar',
                        text: 'Ukuran file maksimal 2MB',
                        confirmButtonText: 'OK'
                    });
                    $(fileInput).val('');
                    return;
                }
                
                if (!file.type.match('image.*')) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Format File Salah',
                        text: 'Hanya file gambar yang diperbolehkan',
                        confirmButtonText: 'OK'
                    });
                    $(fileInput).val('');
                    return;
                }
            }
        }

        clearValidationErrors() {
            $(`.is-invalid`).removeClass('is-invalid');
            $(`.invalid-feedback`).empty();
        }

        displayValidationErrors(errors) {
            $.each(errors, (field, messages) => {
                const input = $(`[name="${field}"]`);
                const errorDiv = $(`#${this.modalId}-error-${field}`);
                
                input.addClass('is-invalid');
                errorDiv.text(messages[0]);
            });
        }
    }

    // Global function to create and manage payment modal instances
    window.PaymentModalManager = {
        instances: {},
        
        getInstance(modalId) {
            if (!this.instances[modalId]) {
                this.instances[modalId] = new PaymentModal(modalId);
            }
            return this.instances[modalId];
        },
        
        openPaymentModal(modalId, orderId, orderNumber, totalAmount, paidAmount) {
            const instance = this.getInstance(modalId);
            instance.openModal(orderId, orderNumber, totalAmount, paidAmount);
        }
    };

    // Global functions for backward compatibility
    window.openPaymentModal = function(orderId, orderNumber, totalAmount, paidAmount, modalId = 'paymentModal') {
        window.PaymentModalManager.openPaymentModal(modalId, orderId, orderNumber, totalAmount, paidAmount);
    };
</script>
@endpush
@endonce