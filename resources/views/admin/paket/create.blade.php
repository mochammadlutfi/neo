<x-app-layout>

    @push('styles')
    <link rel="stylesheet" href="/js/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="/js/plugins/flatpickr/flatpickr.min.css">
    <link href="https://unpkg.com/@yaireo/tagify/dist/tagify.css" rel="stylesheet" type="text/css" />
    <style>
        .ck-editor__editable_inline {
            min-height: 400px;
        }
    </style>
    @endpush

    <div class="content">
        <div class="content-heading d-flex justify-content-between align-items-center">
            <span>{{ isset($data) ? 'Edit Paket' : 'Tambah Paket' }}</span>
        </div>
        <div class="block block-rounded">
            <div class="block-content p-4">
                <form method="POST" action="{{ route('admin.paket.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-4">
                            <x-input-field type="text" id="nama" name="nama" label="Nama" :required="true"/>
                        </div>
                        <div class="col-4">
                            <x-input-field type="text" id="kode" name="kode" label="Kode" :required="true"/>
                        </div>
                        <div class="col-4">
                            <x-input-field type="text" id="harga" name="harga" label="Harga / Bulan" :required="true"/>
                        </div>
                    </div>
                    <div class="form-floating mb-4">
                        <textarea class="form-control {{ $errors->has('deskripsi') ? 'is-invalid' : '' }}" style="height: 141px"
                            id="field-deskripsi" name="deskripsi" placeholder="Deskripsi">{{ old('deskripsi') }}</textarea>
                            <label class="form-label" for="field-deskripsi">Deskripsi</label>
                        <x-input-error :messages="$errors->get('deskripsi')" class="mt-2" />
                    </div>
                    <x-input-field type="text" id="fitur" inputClass="d-flex fs-xs p-1" name="fitur" label="Fitur" :required="true"/>
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

        var inputElm = document.querySelector('input[name=fitur]');

        var tagify = new Tagify(inputElm);

        $('#field-kategori').select2();

        $('#field-jadwal').select2({
            multiple : true,
        });
        
        $(".tgl").flatpickr({
            altInput: true,
            altFormat: "j F Y H:i",
            dateFormat: "Y-m-d H:i",
            locale : "id",
            enableTime: true,
            time_24hr: true
        });

        
        $("#field-tgl_daftar").flatpickr({
            altInput: true,
            altFormat: "d M Y",
            dateFormat: "Y-m-d",
            locale : "id",
            mode: "range"
        });

        
        $("#field-tgl_training").flatpickr({
            altInput: true,
            altFormat: "d M Y",
            dateFormat: "Y-m-d",
            locale : "id",
            mode: "range"
        });


        $(".time").flatpickr({
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            time_24hr: true
        });

        function add_row()
        {
            $rowno= ($("#spek tbody tr").length == 1) ? 0 : 1;
            $rowno=$rowno+1;
            $row = "<tr id='row"+$rowno+"' class='bb'>";
            $row += `<td><input type="text" class="form-control" name="spek[${ $rowno }][label]"></td>`;
            $row += `<td><input type="text" class="form-control" name="spek[${ $rowno }][value]"></td>`;
            $row += `<td><button type="button" class="btn btn-danger w-100" onclick=delete_row('row${ $rowno }')><i class="fa fa-times"></i></button></td>`;
            $row += "</tr>"
            $("#spek tbody tr:last").after($row);
        }
        function delete_row(rowno)
        {
            $('#'+rowno).remove();
        }

    </script>
    @endpush
</x-app-layout>

