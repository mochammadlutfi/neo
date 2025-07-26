<x-app-layout>

    @push('styles')
    <link rel="stylesheet" href="/js/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="/js/plugins/flatpickr/flatpickr.min.css">
    <link href="https://unpkg.com/@yaireo/tagify/dist/tagify.css" rel="stylesheet" type="text/css" />
    @endpush

    <div class="content">
        <div class="content-heading d-flex justify-content-between align-items-center">
            <span>{{ isset($data) ? 'Ubah' : 'Tambah'}} Customer Journey</span>
        </div>
        <div class="block block-rounded">
            <div class="block-content p-4">
                <form method="POST" action="{{ isset($data) ? route('admin.journey.update', $data->id) : route('admin.journey.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-6">
                            <x-select-field name="customer_id" id="customer_id" placeholder="Pilih" label="Konsumen" :options="$konsumen" value="{{ isset($data) ? $data->user_id : '' }}"/>
                        </div>
                        <div class="col-6"><div class="mb-4">
                            <label class="form-label" for="field-order_id">No Pesanan</label>
                            <select class="form-control {{ $errors->has('order_id') ? 'is-invalid' : '' }}" id="field-order_id" name="order_id">
                                <option value="">Pilih</option>
                            </select>
                            <x-input-error :messages="$errors->get('order_id')" class="mt-2" />
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label" for="field-goal">Goal</label>
                        <textarea class="form-control {{ $errors->has('goal') ? 'is-invalid' : '' }}" name="goal" id="field-goal" rows="3">{{ old('goal', isset($data) ? $data->goal : '') }}</textarea>
                        <x-input-error :messages="$errors->get('goal')" class="mt-2" />
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
    <script src="https://unpkg.com/@yaireo/tagify"></script>
    <script src="https://unpkg.com/@yaireo/tagify@3.1.0/dist/tagify.polyfills.min.js"></script>
    <script>
        var isEdit = "{{ isset($data) ? 1 : 0}}";
        var orderVal = "{{ isset($data) ? $data->order_id : ''}}";
        $(document).ready(function() {
            var orderList = $("#field-order_id");

            
            $("#field-customer_id").on("change", function(e){
                e.preventDefault();
                if($(this).val()){
                    getOrderList($(this).val());
                }else{
                    orderList.empty();
                    orderList.append('<option value="">Pilih Order</option>');
                }
            });
            
            if(isEdit == '1'){
                getOrderList();
                $("#field-order_id").val(orderVal).change();
            }
        });

        function getOrderList(userId){
            $.ajax({
                url: "{{ route('admin.order.json') }}",
                type: "GET",
                data: { 
                    user_id: userId 
                },
                success: function(data) {
                    console.log(orderVal);
                    var orderList = $("#field-order_id");
                    orderList.empty();
                    orderList.append('<option value="">Pilih Order</option>');
                    $.each(data, function(index, order) {
                        if(order.id != orderVal){
                            orderList.append('<option value="' + order.id + '">' + order.nomor + '</option>');
                        }else{
                            orderList.append('<option value="' + order.id + '" selected="selected">' + order.nomor + '</option>');
                        }
                    });
                },
                error: function() {
                    alert('Gagal mengambil data order.');
                }
            });
        }
    </script>
    @endpush
</x-app-layout>

