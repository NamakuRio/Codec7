@extends('templates.admin.ubold.master')

@section('title', $settingGroup->name)

@section('css')
        <!-- third party css -->
        <link href="@asset('templates/admin/ubold/assets/libs/select2/select2.min.css')" rel="stylesheet" type="text/css" />
        <link href="@asset('templates/admin/ubold/assets/libs/datatables/datatables.min.css')" rel="stylesheet" type="text/css" />
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
                            <li class="breadcrumb-item"><a href="@route('admin.settingGroups.index')">Pengaturan</a></li>
                            <li class="breadcrumb-item"><a href="@route('admin.settings.index', ['setting_group' => $settingGroup])">{{ $settingGroup->name }}</a></li>
                            <li class="breadcrumb-item active">Kelola</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Kelola {{ $settingGroup->name }}</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-12">
                <div class="card-box">
                    @can('setting.create')
                        <a href="javascript:void(0);" class="btn btn-sm btn-blue waves-effect waves-light float-right" onclick="$('#modal-add-setting').modal('show');focusable('#add-setting-name')">
                            <i class="mdi mdi-plus-circle"></i> Tambah Pengaturan
                        </a>
                    @endcan
                    <h4 class="header-title mb-4"><a href="@route('admin.settings.index', ['setting_group' => $settingGroup])" class="btn btn-xs btn-link waves-effect waves-dark"><i class="fa fa-arrow-left"></i></a> Kelola {{ $settingGroup->name }}</h4>

                    <table class="table table-hover m-0 table-centered nowrap w-100" id="settings-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Grup Pengaturan</th>
                                <th>Nama</th>
                                <th>Isi</th>
                                <th>Isi Default</th>
                                <th>Tipe</th>
                                <th>Komentar</th>
                                <th>Require</th>
                                <th class="hidden-sm">Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div><!-- end col -->
        </div>
        <!-- end row -->
    </div> <!-- container -->

    @can('setting.create')
        <!-- Modal Add -->
        <div class="modal fade" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;" id="modal-add-setting">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Tambah Pengaturan Baru</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <form method="POST" action="javascript:void(0)" id="form-add-setting">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <div class="form-group col-md-6 col-xs-12">
                                    <label for="add-setting-type">Tipe</label>
                                    <select name="type" id="add-setting-type" class="form-control" required>
                                        <option value="text">Teks</option>
                                        <option value="email">Email</option>
                                        <option value="number">Nomor</option>
                                        <option value="file">File</option>
                                        <option value="textarea">Textarea</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6 col-xs-12">
                                    <label for="add-setting-name">Nama</label>
                                    <input type="text" class="form-control" name="name" id="add-setting-name" placeholder="Masukkan Nama" onkeyup="checkName(this.value)" required>
                                    <span class="help-block text-success" id="help-block-add-setting-name">
                                        <div class="spinner-border spinner-border-sm text-primary mr-2" role="status" style="display:none;">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                        <small></small>
                                    </span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6 col-xs-12">
                                    <label for="add-setting-default-value">Isi Default</label>
                                    <input type="text" class="form-control" name="default_value" id="add-setting-default-value" placeholder="Masukkan Isi Default">
                                </div>
                                <div class="form-group col-md-6 col-xs-12">
                                    <label for="add-setting-value">Isi</label>
                                    <input type="text" class="form-control" name="value" id="add-setting-value" placeholder="Masukkan Isi">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6 col-xs-12">
                                    <label for="add-setting-comment">Komentar</label>
                                    <input type="text" class="form-control" name="comment" id="add-setting-comment" placeholder="Masukkan Komentar">
                                </div>
                                <div class="form-group col-md-6 col-xs-12">
                                    <label for="add-setting-required">Required</label>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" name="required" id="add-setting-required" value="1">
                                        <label for="add-setting-required" class="custom-control-label"></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer bg-whitesmoke br">
                            <button type="submit" class="btn btn-success waves-effect waves-light" id="btn-save-add-setting">Simpan</button>
                            <button type="button" class="btn btn-danger waves-effect waves-light m-l-10" data-dismiss="modal">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan

    @can('setting.update')
        <!-- Modal Add -->
        <div class="modal fade" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;" id="modal-update-setting">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Edit Pengaturan</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <form method="POST" action="javascript:void(0)" id="form-update-setting">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <input type="hidden" name="id" id="update-setting-id" required>
                            <div class="row">
                                <div class="form-group col-md-6 col-xs-12">
                                    <label for="add-setting-type">Tipe</label>
                                    <select name="type" id="update-setting-type" class="form-control" required>
                                        <option value="text">Teks</option>
                                        <option value="email">Email</option>
                                        <option value="number">Nomor</option>
                                        <option value="file">File</option>
                                        <option value="textarea">Textarea</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6 col-xs-12">
                                    <label for="add-setting-name">Nama</label>
                                    <input type="text" class="form-control" name="name" id="update-setting-name" placeholder="Masukkan Nama" onkeyup="checkName(this.value, 'update')" required>
                                    <span class="help-block text-success" id="help-block-update-setting-name">
                                        <div class="spinner-border spinner-border-sm text-primary mr-2" role="status" style="display:none;">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                        <small></small>
                                    </span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6 col-xs-12">
                                    <label for="add-setting-default-value">Isi Default</label>
                                    <input type="text" class="form-control" name="default_value" id="update-setting-default-value" placeholder="Masukkan Isi Default">
                                </div>
                                <div class="form-group col-md-6 col-xs-12">
                                    <label for="add-setting-value">Isi</label>
                                    <input type="text" class="form-control" name="value" id="update-setting-value" placeholder="Masukkan Isi">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6 col-xs-12">
                                    <label for="add-setting-comment">Komentar</label>
                                    <input type="text" class="form-control" name="comment" id="update-setting-comment" placeholder="Masukkan Komentar">
                                </div>
                                <div class="form-group col-md-6 col-xs-12">
                                    <label for="update-setting-required">Required</label>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" name="required" id="update-setting-required" value="1">
                                        <label for="update-setting-required" class="custom-control-label"></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer bg-whitesmoke br">
                            <button type="submit" class="btn btn-success waves-effect waves-light" id="btn-save-update-setting">Simpan</button>
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
        <script src="@asset('templates/admin/ubold/assets/libs/datatables/datatables.min.js')"></script>
@endsection

@section('script-bottom')
    <script>
        $(function () {
            "use strict";

            $('#add-setting-type').select2({
                width: '100%',
                placeholder: 'Pilih Tipe',
            });

            $('#update-setting-type').select2({
                width: '100%',
                placeholder: 'Pilih Tipe',
            });

            @can('setting.view')
                getSettings();
            @endcan

            @can('setting.create')
                $("#form-add-setting").on("submit", function (e) {
                    e.preventDefault();

                    if($("#add-setting-type").val().length == 0 ||
                    $("#add-setting-name").val().length == 0){
                        notification('warning', 'Harap isi semua field.');
                        return false;
                    }

                    addSetting();
                });
            @endcan

            @can('setting.update')
                $("#form-update-setting").on("submit", function (e) {
                    e.preventDefault();

                    if($("#update-setting-type").val().length == 0 ||
                    $("#update-setting-name").val().length == 0){
                        notification('warning', 'Harap isi semua field.');
                        return false;
                    }

                    updateSetting();
                });
            @endcan
        });

        @can('setting.view')
            function getSettings()
            {
                $("#settings-table").dataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "@route('admin.settings.getSettings', ['setting_group' => $settingGroup])",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}"
                        }
                    },
                    destroy: true,
                    columns: [
                        { data: 'order' },
                        { data: 'setting_group_name' },
                        { data: 'name' },
                        { data: 'value' },
                        { data: 'default_value' },
                        { data: 'type' },
                        { data: 'comment' },
                        { data: 'required' },
                        { data: 'action' },
                    ],
                    scrollX: true,
                    language: {
                        paginate: {
                            previous: "<i class='mdi mdi-chevron-left'>",
                            next: "<i class='mdi mdi-chevron-right'>"
                        }
                    },
                    drawCallback: function drawCallback() {
                        $('.dataTables_paginate > .pagination').addClass('pagination-rounded');
                    },
                    order: [
                        [0, 'ASC']
                    ]
                });
            }
        @endcan

        @can('setting.create')
            function addSetting()
            {
                var formData = $("#form-add-setting").serialize();

                $.ajax({
                    url: "@route('admin.settings.store', ['setting_group' => $settingGroup])",
                    type: "POST",
                    dataType: "json",
                    data: formData,
                    beforeSend() {
                        $("#btn-save-add-setting").html('<span class="spinner-border spinner-border-sm mr-1" role="status" aria-hidden="true"></span> Loading...');
                        $("#btn-save-add-setting").attr('disabled', 'disabled');
                        $("input").attr('disabled', 'disabled');
                        $("select").attr('disabled', 'disabled');
                        $("button").attr('disabled', 'disabled');
                    },
                    complete() {
                        $("#btn-save-add-setting").html('Simpan');
                        $("#btn-save-add-setting").removeAttr('disabled', 'disabled');
                        $("input").removeAttr('disabled', 'disabled');
                        $("select").removeAttr('disabled', 'disabled');
                        $("button").removeAttr('disabled', 'disabled');
                    },
                    success : function(result) {
                        if(result['status'] == 'success'){
                            $("#form-add-setting")[0].reset();
                            $('#modal-add-setting').modal('hide');
                            getSettings();

                            $('#help-block-add-setting-name small').text('');
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

        @can('setting.update')
            function getUpdateData(object)
            {
                var id = $(object).data('id');

                $.ajax({
                    url: "@route('admin.settings.show', ['setting_group' => $settingGroup])",
                    type: "POST",
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'id': id,
                    },
                    dataType: "json",
                    beforeSend() {
                        $('#form-update-setting')[0].reset();
                        $("#btn-save-update-setting").html('<span class="spinner-border spinner-border-sm mr-1" role="status" aria-hidden="true"></span> Loading...');
                        $("#btn-save-update-setting").attr('disabled', 'disabled');
                        $("input").attr('disabled', 'disabled');
                        $("select").attr('disabled', 'disabled');
                        $("button").attr('disabled', 'disabled');
                        $('#modal-update-setting').modal('show');
                        if(nameHttpRequest && nameHttpRequest.readyState != 4){
                            nameHttpRequest.abort();
                        }
                        $('#help-block-update-setting-name small').text('');
                        $('#help-block-update-setting-name .spinner-border').hide();
                    },
                    complete() {
                        $("#btn-save-update-setting").html('Simpan');
                        $("#btn-save-update-setting").removeAttr('disabled', 'disabled');
                        $("input").removeAttr('disabled', 'disabled');
                        $("select").removeAttr('disabled', 'disabled');
                        $("button").removeAttr('disabled', 'disabled');
                    },
                    success : function(result) {
                        if(result['status'] == 'error'){
                            $('#modal-update-setting').modal('hide');
                            notification(result['status'], result['message']);
                        } else {
                            $('#update-setting-id').val(result['data']['id']);
                            $('#update-setting-type').val(result['data']['type']).trigger('change');
                            $('#update-setting-name').val(result['data']['name']);
                            $('#update-setting-default-value').val(result['data']['default_value']);
                            $('#update-setting-value').val(result['data']['value']);
                            $('#update-setting-comment').val(result['data']['comment']);
                            if(result['data']['required'] == 1) $('#update-setting-required').attr('checked', true);
                            if(result['data']['required'] == 0) $('#update-setting-required').attr('checked', false);
                            focusable('#update-setting-name');
                        }
                    },
                    error : function(xhr, status, error) {
                        var err = eval('(' + xhr.responseText + ')');
                        notification(status, err.message);
                        checkCSRFToken(err.message);
                    }
                });
            }

            function updateSetting()
            {
                var formData = $("#form-update-setting").serialize();

                $.ajax({
                    url: "@route('admin.settings.update', ['setting_group' => $settingGroup])",
                    type: "PUT",
                    dataType: "json",
                    data: formData,
                    beforeSend() {
                        $("#btn-save-update-setting").html('<span class="spinner-border spinner-border-sm mr-1" role="status" aria-hidden="true"></span> Loading...');
                        $("#btn-save-update-setting").attr('disabled', 'disabled');
                        $("input").attr('disabled', 'disabled');
                        $("select").attr('disabled', 'disabled');
                        $("button").attr('disabled', 'disabled');
                    },
                    complete() {
                        $("#btn-save-update-setting").html('Simpan');
                        $("#btn-save-update-setting").removeAttr('disabled', 'disabled');
                        $("input").removeAttr('disabled', 'disabled');
                        $("select").removeAttr('disabled', 'disabled');
                        $("button").removeAttr('disabled', 'disabled');
                    },
                    success : function(result) {
                        if(result['status'] == 'success'){
                            $("#form-update-setting")[0].reset();
                            $('#modal-update-setting').modal('hide');
                            getSettings();
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

        @can('setting.delete')
            function deleteSetting(object)
            {
                var id = $(object).data('id');

                Swal.fire({
                        title: 'Anda yakin ingin menghapus Pengaturan?',
                        text: 'Anda tidak dapat memulihkannya kembali',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, hapus!',
                        showLoaderOnConfirm:true,
                        preConfirm: () => {
                            ajax =  $.ajax({
                                        url: "@route('admin.settings.destroy', ['setting_group' => $settingGroup])",
                                        type: "POST",
                                        dataType: "json",
                                        data: {
                                            "_method": "DELETE",
                                            "_token": "{{ csrf_token() }}",
                                            "id": id,
                                        },
                                        success : function(result) {
                                            if(result['status'] == 'success'){
                                                getSettings();
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

        var nameHttpRequest;

        function checkName(name, type = 'insert')
        {
            var id = 0;

            if(type == 'update'){
                id = $('#update-setting-id').val();
            }

            if(nameHttpRequest && nameHttpRequest.readyState != 4){
                nameHttpRequest.abort();
            }

            nameHttpRequest = $.ajax({
                url: "@route('admin.settings.checkName')",
                type: "POST",
                dataType: "json",
                data: {
                    _token: '{{ csrf_token() }}',
                    name: name,
                    type: type,
                    id: id,
                },
                beforeSend() {
                    if(type == 'insert'){
                        $('#help-block-add-setting-name small').text('');
                        $('#help-block-add-setting-name .spinner-border').show();
                    }
                    else if(type == 'update'){
                        $('#help-block-update-setting-name small').text('');
                        $('#help-block-update-setting-name .spinner-border').show();
                    }
                },
                complete() {
                    if(type == 'insert'){
                        $('#help-block-add-setting-name .spinner-border').hide();
                    }
                    else if(type == 'update'){
                        $('#help-block-update-setting-name .spinner-border').hide();
                    }
                },
                success : function(result) {
                    if(type == 'insert'){
                        $('#help-block-add-setting-name small').text(result['message']);

                        if(result['status'] == 'error') {
                            $('#help-block-add-setting-name').addClass('text-danger');
                            $('#help-block-add-setting-name').removeClass('text-success');
                        }

                        if(result['status'] == 'success') {
                            $('#help-block-add-setting-name').addClass('text-success');
                            $('#help-block-add-setting-name').removeClass('text-danger');
                        }
                    } else if(type == 'update'){
                        $('#help-block-update-setting-name small').text(result['message']);

                        if(result['status'] == 'error') {
                            $('#help-block-update-setting-name').addClass('text-danger');
                            $('#help-block-update-setting-name').removeClass('text-success');
                        }

                        if(result['status'] == 'success') {
                            $('#help-block-update-setting-name').addClass('text-success');
                            $('#help-block-update-setting-name').removeClass('text-danger');
                        }
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
