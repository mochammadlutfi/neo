<x-app-layout>
    <div class="content">
        <!-- Page Header -->
        <div class="block block-rounded">
            <div class="block-header">
                <h3 class="block-title fs-base fw-bold">
                    <i class="fa fa-route me-2 text-primary"></i>
                    Customer Journey: {{ $data->user->nama }}
                </h3>
                <div class="block-options">
                    <a href="{{ route('admin.journey.index') }}" class="btn btn-sm btn-secondary fs-base me-2">
                        <i class="fa fa-arrow-left me-1"></i>
                        Kembali
                    </a>
                    <a class="btn btn-sm btn-primary fs-base" target="_blank" href="{{ route('admin.journey.pdf', $data->id)}}">
                        <i class="fa fa-download me-1"></i>
                        Download PDF
                    </a>
                </div>
            </div>
        </div>
        <input type="hidden" id="adminLevel" value="{{  auth()->guard('admin')->user()->level }}" />
        <div class="block rounded">
            <div class="block-content p-4">
                <div class="row">
                    <div class="col-md-6">
                        <x-show-field label="Konsumen" value="{{ $data->user->nama }}"/>
                        <x-show-field label="No Pesanan" value="{{ $data->order->nomor }}"/>
                    </div>
                    <div class="col-md-6">
                        <x-show-field label="Goal" value="{{ $data->goal }}"/>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-heading d-flex justify-content-between align-items-center">
            <div class="content-title">
                Tahapan
            </div>
            <div class="space-x">
                @if(in_array(auth()->guard('admin')->user()->level, ['Marketing']))
                <button class="btn btn-alt-primary btn-sm" onclick="openModal()">
                    <i class="fa fa-plus me-1"></i>
                    Tambah Tahapan
                </button>
                @endif
            </div>
        </div>
        <div class="block rounded">
            <div class="block-content p-0">
                <table class="table table-bordered w-100 rounded" id="journeyTable">
                    <tbody>
                        <tr id="stageRow">
                            <th width="100px">Stage</th>
                        </tr>
                        <tr id="experienceRow">
                            <th width="100px">Experiences</th>
                        </tr>
                        <tr id="opportunitiesRow">
                            <th width="100px">Opportunities</th>
                        </tr>
                        <tr id="expectationRow">
                            <th width="100px">Expectations</th>
                        </tr>
                        <tr id="feelingsRow">
                            <th width="100px">Feelings</th>
                        </tr>
                        <tr id="touchPointRow">
                            <th width="100px">Touch Point</th>
                        </tr>
                        @if(in_array(auth()->guard('admin')->user()->level, ['Marketing']))
                        <tr id="actionRow">
                            <th width="100px">Aksi</th>
                        </tr>
                        @endif
                    </tbody>
                </table>
                <div class="text-center p-4" id="journeyEmpty">
                    <h3 class="mb-0">Data tidak ada</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="modal" id="modal-form" aria-labelledby="modal-form" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form id="formData" onsubmit="return false;" enctype="multipart/form-data">
                    <div class="block block-rounded shadow-none mb-0">
                        <input type="hidden" name="id" id="field-id" value=""/>
                        <input type="hidden" name="journey_id" value="{{  $data->id }}"/>
                        <div class="block-header bg-gd-dusk">
                            <h3 class="block-title text-white" id="modalFormTitle">A</h3>
                            <div class="block-options">
                                <button type="button" class="text-white btn-block-option" data-bs-dismiss="modal" aria-label="Close">
                                    <i class="fa fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="block-content p-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <x-input-field type="text" id="stage" name="stage" label="Stage" isAjax/>
                                    <x-input-field type="textarea" id="experience" name="experience" label="Experience" isAjax/>
                                    <x-input-field type="textarea" id="opportunities" name="opportunities" label="Opportunities" isAjax/>
                                </div>
                                <div class="col-md-6">
                                    <x-input-field type="textarea" id="expectation" name="expectation" label="Expectation" isAjax/>
                                    <x-input-field type="textarea" id="feeling" name="feeling" label="Feeling" isAjax/>
                                    <x-input-field type="textarea" id="touch_point" name="touch_point" label="Touch Point" isAjax/>
                                </div>
                            </div>
                        </div>
                        <div class="block-content block-content-full block-content-sm text-end border-top">
                            <button type="button" class="btn btn-alt-danger" data-bs-dismiss="modal">
                                <i class="fa fa-times me-1"></i>
                                Batal
                            </button>
                            <button type="submit" class="btn btn-alt-primary" id="btn-simpan">
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

        $(document).ready(function () {
            fetchData();
            
            $("#formData").on("submit",function (e) {
                e.preventDefault();
                var fomr = $('form#formData')[0];
                var formData = new FormData(fomr);
                let token   = $("meta[name='csrf-token']").attr("content");
                formData.append('_token', token);

                let id = $('#field-id').val();

                let url = (id) ? `/admin/step/${id}/update` : `/admin/step/store`;

                $.ajax({
                    url: url,
                    type: "POST",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        if (response.fail == false) {
                            fetchData();
                            var myModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('modal-form'));
                            myModal.hide();
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
        });
        
        function fetchData(){
            
            $.ajax({
                url: '{{ route("admin.step.index")}}', // Panggil API Laravel
                type: "GET",
                data: { 
                    journey_id: "{{ $data->id }}" 
                },
                success: function (data) {
                    if(data.length){
                        $("#journeyEmpty").hide();
                        $("#journeyTable").show();

                        var adminLevel = $("#adminLevel").val();
                        
                        $("#stageRow").find("td").remove();
                        $("#experienceRow").find("td").remove();
                        $("#opportunitiesRow").find("td").remove();
                        $("#expectationRow").find("td").remove();
                        $("#feelingsRow").find("td").remove();
                        $("#touchPointRow").find("td").remove();
                        $("#actionRow").find("td").remove();

                        $.each(data, function (index, item) {
                            $("#stageRow").append("<td>" + item.stage + "</td>");
                            $("#experienceRow").append("<td>" + item.experience + "</td>");
                            $("#opportunitiesRow").append("<td>" + item.opportunities + "</td>");
                            $("#expectationRow").append("<td>" + item.expectation + "</td>");
                            $("#feelingsRow").append("<td>" + item.feeling + "</td>");
                            $("#touchPointRow").append("<td>" + item.touch_point + "</td>");
                            if(adminLevel == 'Marketing'){
                                $("#actionRow").append(`<td>
                                    <button class="btn btn-sm btn-primary" onclick="ubah(${item.id})">Ubah</button>
                                    <button class="btn btn-sm btn-danger" onclick="hapus(${item.id})">Hapus</button>
                                </td>`);
                            }
                        });
                    }else{
                        $("#journeyEmpty").show();
                        $("#journeyTable").hide();
                    }

                },
                error: function () {
                    alert("Gagal mengambil data.");
                }
            });
        }

        function openModal(){
            $('#field-id').val('');
            $('#field-stage').val('');
            $('#field-experience').val('');
            $('#field-expectation').val('');
            $('#field-opportunities').val('');
            $('#field-feeling').val('');
            $('#field-touch_point').val('');
            var modalForm = bootstrap.Modal.getOrCreateInstance(document.getElementById('modal-form'));
            $("#modalFormTitle").html('Tambah Tahapan');
            modalForm.show();
        }
        
        function ubah(id){
            $.ajax({
                url: `/admin/step/${id}`,
                type: "GET",
                dataType: "json",
                success: function (response) {
                    $('#field-id').val(response.id);
                    $('#field-stage').val(response.stage);
                    $('#field-experience').val(response.experience);
                    $('#field-expectation').val(response.expectation);
                    $('#field-opportunities').val(response.opportunities);
                    $('#field-feeling').val(response.feeling);
                    $('#field-touch_point').val(response.touch_point);
                    $("#modalFormTitle").html('Ubah Tahapan');
                    var el = document.getElementById('modal-form');
                    var myModal = bootstrap.Modal.getOrCreateInstance(el);
                    myModal.show();
                },
                error: function (error) {
                }

            });
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
                        url: "/admin/step/"+ id +"/delete",
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
                                    fetchData();
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

