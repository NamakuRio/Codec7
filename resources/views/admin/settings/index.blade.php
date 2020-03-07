@extends('templates.admin.ubold.master')

@section('title', 'Pengaturan')

@section('css')
        <!-- third party css -->
        <link href="@asset('templates/admin/ubold/assets/libs/select2/select2.min.css')" rel="stylesheet" type="text/css" />
        <!-- third party css end -->
@endsection

@section('content')
    <!-- Start Content-->
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="@route('admin.dashboard')">Admin</a></li>
                            <li class="breadcrumb-item active">Pengaturan</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Pengaturan</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row mb-2">
            @if (auth()->user()->can('setting_group.create') || auth()->user()->can('setting_group.manage'))
                <div class="col-sm-4">
                    @can('setting_group.create')
                        <button type="button" class="btn btn-blue btn-rounded waves-effect waves-light mb-3" onclick="$('#modal-add-setting-group').modal('show');focusable('#add-setting-group-name')"><i class="mdi mdi-plus"></i> Buat Pengaturan</button>
                    @endcan
                    @can('setting_group.manage')
                        <button type="button" class="btn btn-info btn-rounded waves-effect waves-light mb-3"><i class="mdi mdi-reorder-horizontal"></i> Ubah Urutan</button>
                    @endcan
                </div>
            @endif
            <div class="{{ ((auth()->user()->can('setting_group.create') || auth()->user()->can('setting_group.manage')) ? 'col-sm-8' : 'col-sm-12') }}">
                <div class="text-sm-right">
                    <div class="btn-group mb-3 ml-2 d-none d-sm-inline-block">
                        <button type="button" class="btn btn-dark waves-effect waves-light"><i class="mdi mdi-apps"></i></button>
                    </div>
                    <div class="btn-group mb-3 d-none d-sm-inline-block">
                        <button type="button" class="btn btn-link text-dark waves-effect waves-dark"><i class="mdi mdi-format-list-bulleted-type"></i></button>
                    </div>
                </div>
            </div><!-- end col-->
        </div>
        <!-- end row-->

        <div class="row justify-content-center" id="view-loader-page" style="display:none">
            <div class="col-lg-6 col-xl-4 mb-4">
                <div class="text-center justify-content-center mt-5 pt-5">
                    <div class="spinner-border" role="status"></div>
                </div>
            </div>
        </div>

        <div class="row" id="view-setting-groups-page" style="display:none">

        </div><!-- end row -->

        {{-- <div class="row">
            <div class="col-12 order-2">
                <div class="card-box mb-2">
                    <div class="row align-items-center">
                        <div class="col-sm-4">
                            <div class="media">
                                <img class="d-flex align-self-center mr-3 rounded-circle" src="@asset('assets/images/companies/amazon.png')" alt="Generic placeholder image" height="64">
                                <div class="media-body">
                                    <h4 class="mt-0 mb-2 font-16">Pengaturan Umum.</h4>
                                    <p class="mb-1"><b>Location:</b> Seattle, Washington</p>
                                    <p class="mb-0"><b>Category:</b> Ecommerce</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <p class="mb-1 mt-3 mt-sm-0"><i class="mdi mdi-email mr-1"></i> collier@jourrapide.com</p>
                            <p class="mb-0"><i class="mdi mdi-phone-classic mr-1"></i> 828-216-2190</p>
                        </div>
                        <div class="col-sm-2">
                            <div class="text-center mt-3 mt-sm-0">
                                <div class="badge font-14 bg-soft-info text-info p-1">Hot</div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="text-sm-right">
                                <a href="javascript:void(0);" class="action-icon"> <i class="mdi mdi-square-edit-outline"></i></a>
                                <a href="javascript:void(0);" class="action-icon"> <i class="mdi mdi-delete"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}

    </div> <!-- container -->

    @can('setting_group.create')
        <!-- Modal Add -->
        <div class="modal fade" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;" id="modal-add-setting-group">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Tambah Pengaturan Baru</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <form method="POST" action="javascript:void(0)" id="form-add-setting-group">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <div class="form-group col-md-6 col-xs-12">
                                    <label for="add-setting-group-name">Nama</label>
                                    <input type="text" class="form-control" name="name" id="add-setting-group-name" placeholder="Masukkan Nama" onkeyup="createSlug(this.value)">
                                </div>
                                <div class="form-group col-md-6 col-xs-12">
                                    <label for="add-setting-group-slug">Slug</label>
                                    <span class="help-block text-success" id="help-block-add-setting-group-slug">
                                        <div class="spinner-border spinner-border-sm text-primary mr-2" role="status" style="display:none;">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                        <small></small>
                                    </span>
                                    <input type="text" class="form-control" name="slug" id="add-setting-group-slug" placeholder="Masukkan Slug" readonly>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6 col-xs-12">
                                    <label for="add-setting-group-description">Deskripsi</label>
                                    <textarea class="form-control" name="description" id="add-setting-group-description" placeholder="Masukkan Deskripsi" cols="30" rows="10"></textarea>
                                </div>
                                <div class="form-group col-md-6 col-xs-12">
                                    <label for="add-setting-group-icon">Icon</label>
                                    <select class="form-control" name="icon" id="add-setting-group-icon"></select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer bg-whitesmoke br">
                            <button type="submit" class="btn btn-success waves-effect waves-light" id="btn-save-add-setting-group">Simpan</button>
                            <button type="button" class="btn btn-danger waves-effect waves-light m-l-10" data-dismiss="modal">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan

    @can('setting_group.update')
        <!-- Modal Add -->
        <div class="modal fade" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;" id="modal-update-setting-group">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Edit Pengaturan</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <form method="POST" action="javascript:void(0)" id="form-update-setting-group">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <input type="hidden" name="id" id="update-setting-group-id" required>
                            <div class="row">
                                <div class="form-group col-md-6 col-xs-12">
                                    <label for="update-setting-group-name">Nama</label>
                                    <input type="text" class="form-control" name="name" id="update-setting-group-name" placeholder="Masukkan Nama" onkeyup="createSlug(this.value, 'update')">
                                </div>
                                <div class="form-group col-md-6 col-xs-12">
                                    <label for="update-setting-group-slug">Slug</label>
                                    <span class="help-block text-success" id="help-block-update-setting-group-slug">
                                        <div class="spinner-border spinner-border-sm text-primary mr-2" role="status" style="display:none;">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                        <small></small>
                                    </span>
                                    <input type="text" class="form-control" name="slug" id="update-setting-group-slug" placeholder="Masukkan Slug" readonly>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6 col-xs-12">
                                    <label for="update-setting-group-description">Deskripsi</label>
                                    <textarea class="form-control" name="description" id="update-setting-group-description" placeholder="Masukkan Deskripsi" cols="30" rows="10"></textarea>
                                </div>
                                <div class="form-group col-md-6 col-xs-12">
                                    <label for="update-setting-group-icon">Icon</label>
                                    <select class="form-control" name="icon" id="update-setting-group-icon"></select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer bg-whitesmoke br">
                            <button type="submit" class="btn btn-success waves-effect waves-light" id="btn-save-update-setting-group">Simpan</button>
                            <button type="button" class="btn btn-danger waves-effect waves-light m-l-10" data-dismiss="modal">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan
@endsection

@section('script')
        <!-- third party js -->
        <script src="@asset('templates/admin/ubold/assets/libs/select2/select2.min.js')"></script>
@endsection

@section('script-bottom')
    <script>
        $(function () {
            "use strict";

            $('#add-setting-group-icon').select2({
                width: '100%',
                placeholder: 'Pilih Status',
                minimumInputLength: 2,
                ajax: {
                    url: "@route('icons.select2')",
                    type: "POST",
                    dataType: "json",
                    quietMillis: 50,
                    delay: 250,
                    data: function (params) {
                        var text = params.term ? params.term : '';
                        var query = {
                            search: text,
                        };
                        return {
                            data: query,
                            page: params.page || 1,
                            _token: '{{ csrf_token() }}',
                        };
                    },
                    processResults : function(data, params) {
                        params.page = params.page || 1;

                        return {
                            results: $.map(data['data'], function (item) {
                                return {
                                    text: item.icon,
                                    id: item.id
                                }
                            }),
                            pagination: {
                                more: (params.page * 25) < data.total
                            }
                        }
                    }
                },
                templateResult: formatSelect2GroupIcon
            });

            $('#update-setting-group-icon').select2({
                width: '100%',
                placeholder: 'Pilih Status',
                minimumInputLength: 2,
                ajax: {
                    url: "@route('icons.select2')",
                    type: "POST",
                    dataType: "json",
                    quietMillis: 50,
                    delay: 250,
                    data: function (params) {
                        var text = params.term ? params.term : '';
                        var query = {
                            search: text,
                        };
                        return {
                            data: query,
                            page: params.page || 1,
                            _token: '{{ csrf_token() }}',
                        };
                    },
                    processResults : function(data, params) {
                        params.page = params.page || 1;

                        return {
                            results: $.map(data['data'], function (item) {
                                return {
                                    text: item.icon,
                                    id: item.id
                                }
                            }),
                            pagination: {
                                more: (params.page * 25) < data.total
                            }
                        }
                    }
                },
                templateResult: formatSelect2GroupIcon
            });

            @can('setting_group.view')
                getSettingGroups();
            @endcan

            @can('setting_group.create')
                $("#form-add-setting-group").on("submit", function (e) {
                    e.preventDefault();

                    if($("#add-setting-group-name").val().length == 0 ||
                    $("#add-setting-group-slug").val().length == 0 ||
                    $("#add-setting-group-description").val().length == 0 ||
                    $("#add-setting-group-icon").val().length == 0){
                        notification('warning', 'Harap isi semua field.');
                        return false;
                    }

                    addSettingGroup();
                });
            @endcan

            @can('setting_group.update')
                $("#form-update-setting-group").on("submit", function (e) {
                    e.preventDefault();

                    if($("#update-setting-group-name").val().length == 0 ||
                    $("#update-setting-group-slug").val().length == 0 ||
                    $("#update-setting-group-description").val().length == 0 ||
                    $("#update-setting-group-icon").val().length == 0){
                        notification('warning', 'Harap isi semua field.');
                        return false;
                    }

                    updateSettingGroup();
                });
            @endcan
        });

        function formatSelect2GroupIcon(state)
        {
            var result = $('<span><i class="' + state.text + '"></i> ' + state.text + '</span>');

            return result;
        }

        @can('setting_group.view')
            function getSettingGroups()
            {
                $.ajax({
                    url: "@route('admin.settingGroups.getSettingGroups')",
                    type: "POST",
                    dataType: "json",
                    data: {
                        '_token': '{{ csrf_token() }}'
                    },
                    beforeSend() {
                        $("#view-loader-page").show();
                        $("#view-setting-groups-page").hide();
                    },
                    complete() {
                        $("#view-loader-page").hide();
                        $("#view-setting-groups-page").show();
                    },
                    success : function(result) {
                        $("#view-setting-groups-page").html(result['data']);
                    },
                    error : function(xhr, status, error) {
                        var err = eval('(' + xhr.responseText + ')');
                        notification(status, err.message);
                        checkCSRFToken(err.message);
                    }
                });
            }
        @endcan

        @can('setting_group.create')
            function addSettingGroup()
            {
                var formData = $("#form-add-setting-group").serialize();

                $.ajax({
                    url: "@route('admin.settingGroups.store')",
                    type: "POST",
                    dataType: "json",
                    data: formData,
                    beforeSend() {
                        $("#btn-save-add-setting-group").html('<span class="spinner-border spinner-border-sm mr-1" role="status" aria-hidden="true"></span> Loading...');
                        $("#btn-save-add-setting-group").attr('disabled', 'disabled');
                        $("input").attr('disabled', 'disabled');
                        $("select").attr('disabled', 'disabled');
                        $("textarea").attr('disabled', 'disabled');
                        $("button").attr('disabled', 'disabled');
                    },
                    complete() {
                        $("#btn-save-add-setting-group").html('Simpan');
                        $("#btn-save-add-setting-group").removeAttr('disabled', 'disabled');
                        $("input").removeAttr('disabled', 'disabled');
                        $("select").removeAttr('disabled', 'disabled');
                        $("textarea").removeAttr('disabled', 'disabled');
                        $("button").removeAttr('disabled', 'disabled');
                    },
                    success : function(result) {
                        if(result['status'] == 'success'){
                            $("#form-add-setting-group")[0].reset();
                            $('#modal-add-setting-group').modal('hide');
                            getSettingGroups();

                            $('#help-block-add-setting-group-slug small').text('');
                        }

                        notification(result['status'], result['message']);
                    },
                    error : function(xhr, status, error) {
                        var err = eval('(' + xhr.responseText + ')');
                        notification(status, err.message);
                        checkCSRFToken(err.message);
                    }
                });
            }
        @endcan

        @can('setting_group.update')
            function getUpdateData(object)
            {
                var id = $(object).data('id');

                $.ajax({
                    url: "@route('admin.settingGroups.show')",
                    type: "POST",
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'id': id,
                    },
                    dataType: "json",
                    beforeSend() {
                        $('#form-update-setting-group')[0].reset();
                        $("#btn-save-update-setting-group").html('<span class="spinner-border spinner-border-sm mr-1" role="status" aria-hidden="true"></span> Loading...');
                        $("#btn-save-update-setting-group").attr('disabled', 'disabled');
                        $("input").attr('disabled', 'disabled');
                        $("button").attr('disabled', 'disabled');
                        $('#modal-update-setting-group').modal('show');
                        $('#update-setting-group-icon option').remove();
                        if(slugHttpRequest && slugHttpRequest.readyState != 4){
                            slugHttpRequest.abort();
                        }
                        $('#help-block-update-setting-group-slug small').text('');
                        $('#help-block-update-setting-group-slug .spinner-border').hide();
                    },
                    complete() {
                        $("#btn-save-update-setting-group").html('Simpan');
                        $("#btn-save-update-setting-group").removeAttr('disabled', 'disabled');
                        $("input").removeAttr('disabled', 'disabled');
                        $("button").removeAttr('disabled', 'disabled');
                    },
                    success : function(result) {
                        if(result['status'] == 'error'){
                            $('#modal-update-setting-group').modal('hide');
                            notification(result['status'], result['message']);
                        } else {
                            $('#update-setting-group-icon').append('<option value="'+result['icon']['id']+'" selected>'+result['icon']['icon']+'</option>');
                            $('#update-setting-group-id').val(result['data']['id']);
                            $('#update-setting-group-name').val(result['data']['name']);
                            $('#update-setting-group-slug').val(result['data']['slug']);
                            $('#update-setting-group-description').val(result['data']['description']);
                            focusable('#update-setting-group-name');
                        }
                    },
                    error : function(xhr, status, error) {
                        var err = eval('(' + xhr.responseText + ')');
                        notification(status, err.message);
                        checkCSRFToken(err.message);
                    }
                });
            }

            function updateSettingGroup()
            {
                var formData = $("#form-update-setting-group").serialize();

                $.ajax({
                    url: "@route('admin.settingGroups.update')",
                    type: "PUT",
                    dataType: "json",
                    data: formData,
                    beforeSend() {
                        $("#btn-save-update-setting-group").html('<span class="spinner-border spinner-border-sm mr-1" role="status" aria-hidden="true"></span> Loading...');
                        $("#btn-save-update-setting-group").attr('disabled', 'disabled');
                        $("input").attr('disabled', 'disabled');
                        $("button").attr('disabled', 'disabled');
                    },
                    complete() {
                        $("#btn-save-update-setting-group").html('Simpan');
                        $("#btn-save-update-setting-group").removeAttr('disabled', 'disabled');
                        $("input").removeAttr('disabled', 'disabled');
                        $("button").removeAttr('disabled', 'disabled');
                    },
                    success : function(result) {
                        if(result['status'] == 'success'){
                            $("#form-update-setting-group")[0].reset();
                            $('#modal-update-setting-group').modal('hide');
                            getSettingGroups();
                        }

                        notification(result['status'], result['message']);
                    },
                    error : function(xhr, status, error) {
                        var err = eval('(' + xhr.responseText + ')');
                        notification(status, err.message);
                        checkCSRFToken(err.message);
                    }
                });
            }
        @endcan

        @can('setting_group.delete')
            function deleteSettingGroup(object)
            {
                var id = $(object).data('id');

                Swal.fire({
                        title: 'Anda yakin ingin menghapus Grup Pengaturan?',
                        text: 'Anda tidak dapat memulihkannya kembali',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, hapus!',
                        showLoaderOnConfirm:true,
                        preConfirm: () => {
                            ajax =  $.ajax({
                                        url: "@route('admin.settingGroups.destroy')",
                                        type: "POST",
                                        dataType: "json",
                                        data: {
                                            "_method": "DELETE",
                                            "_token": "{{ csrf_token() }}",
                                            "id": id,
                                        },
                                        success : function(result) {
                                            if(result['status'] == 'success'){
                                                getSettingGroups();
                                            }
                                            swalNotification(result['status'], result['message']);
                                        },
                                        error : function(xhr, status, error) {
                                            var err = eval('(' + xhr.responseText + ')');
                                            notification(status, err.message);
                                            checkCSRFToken(err.message);
                                        }
                                    });

                            return ajax;
                        }
                    })
                    .then((result) => {
                        if (result.value) {
                            notification(result.value.status, result.value.message);
                        }
                    });
            }
        @endcan

        var slugHttpRequest;

        function createSlug(value, type = 'insert')
        {
            var id = 0;
            var slug = value.toLowerCase().replace(/ /g,'-').replace(/[^\w-]+/g,'');

            if(type == 'update'){
                id = $('#update-setting-group-id').val();
            }

            if(slugHttpRequest && slugHttpRequest.readyState != 4){
                slugHttpRequest.abort();
            }

            slugHttpRequest = $.ajax({
                url: "@route('admin.settingGroups.checkSlug')",
                type: "POST",
                dataType: "json",
                data: {
                    'id': id,
                    'slug': slug,
                    'type': type,
                    '_token': '{{ csrf_token() }}'
                },
                beforeSend() {
                    if(type == 'insert'){
                        $('#help-block-add-setting-group-slug small').text('');
                        $('#help-block-add-setting-group-slug .spinner-border').show();
                    }
                    else if(type == 'update'){
                        $('#help-block-update-setting-group-slug small').text('');
                        $('#help-block-update-setting-group-slug .spinner-border').show();
                    }
                },
                complete() {
                    if(type == 'insert'){
                        $('#help-block-add-setting-group-slug .spinner-border').hide();
                    }
                    else if(type == 'update'){
                        $('#help-block-update-setting-group-slug .spinner-border').hide();
                    }
                },
                success : function(result) {
                    if(type == 'insert'){
                        $("#add-setting-group-slug").val(result['data']);

                    } else if(type == 'update'){
                        $("#update-setting-group-slug").val(result['data']);
                    }
                },
                error : function(xhr, status, error) {
                    if(status != 'abort'){
                        var err = eval('(' + xhr.responseText + ')');
                        notification(status, err.message);
                        checkCSRFToken(err.message);
                    }
                }
            });
        }
    </script>
@endsection
