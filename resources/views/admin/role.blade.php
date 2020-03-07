@extends('templates.admin.ubold.master')

@section('title', 'Peran')

@section('css')
        <!-- third party css -->
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
                            <li class="breadcrumb-item active">Peran</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Peran</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-12">
                <div class="card-box">
                    @can('role.create')
                        <a href="javascript:void(0);" class="btn btn-sm btn-blue waves-effect waves-light float-right" onclick="openModal('#modal-add-role', 'fadein', '#add-role-name')">
                            <i class="mdi mdi-plus-circle"></i> Tambah Peran
                        </a>
                    @endcan
                    <h4 class="header-title mb-4">Kelola Peran</h4>

                    <table class="table table-hover m-0 table-centered nowrap w-100" id="roles-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama</th>
                                <th>Pengguna Default</th>
                                <th>Tujuan Login</th>
                                <th class="hidden-sm">Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div><!-- end col -->
        </div>
        <!-- end row -->

    </div> <!-- container -->

    @can('role.create')
        <!-- Modal Add -->
        <div id="modal-add-role" class="modal-demo">
            <button type="button" class="close" onclick="Custombox.modal.close();">
                <span>&times;</span><span class="sr-only">Tutup</span>
            </button>
            <h4 class="custom-modal-title">Tambah Peran Baru</h4>
            <div class="custom-modal-text text-left">
                <form method="POST" action="javascript:void(0)" id="form-add-role">
                    @csrf
                    <div class="form-group">
                        <label for="add-role-name">Nama</label>
                        <input type="text" class="form-control" name="name" id="add-role-name" placeholder="Masukkan Nama Peran">
                    </div>
                    <div class="form-group">
                        <label for="add-role-login-destination">Tujuan Login</label>
                        <input type="text" class="form-control" name="login_destination" id="add-role-login-destination" placeholder="Masukkan Tujuan Login Peran" value="/">
                    </div>

                    <div class="text-right">
                        <button type="submit" class="btn btn-success waves-effect waves-light" id="btn-save-add-role">Simpan</button>
                        <button type="button" class="btn btn-danger waves-effect waves-light m-l-10" onclick="Custombox.modal.close();">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    @endcan

    @can('role.update')
        <!-- Modal Update -->
        <div id="modal-update-role" class="modal-demo">
            <button type="button" class="close" onclick="Custombox.modal.close();">
                <span>&times;</span><span class="sr-only">Tutup</span>
            </button>
            <h4 class="custom-modal-title">Edit Peran</h4>
            <div class="custom-modal-text text-left">
                <form method="POST" action="javascript:void(0)" id="form-update-role">
                    @csrf
                    @method("PUT")
                    <input type="hidden" name="id" id="update-role-id" required>
                    <div class="form-group">
                        <label for="update-role-name">Nama</label>
                        <input type="text" class="form-control" name="name" id="update-role-name" placeholder="Masukkan Nama Peran">
                    </div>
                    <div class="form-group">
                        <label for="update-role-login-destination">Tujuan Login</label>
                        <input type="text" class="form-control" name="login_destination" id="update-role-login-destination" placeholder="Masukkan Tujuan Login Peran">
                    </div>

                    <div class="text-right">
                        <button type="submit" class="btn btn-success waves-effect waves-light" id="btn-save-update-role">Simpan</button>
                        <button type="button" class="btn btn-danger waves-effect waves-light m-l-10" onclick="Custombox.modal.close();">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    @endcan

    @can('role.manage')
        <!--  Modal Manage -->
        <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;" id="modal-manage-role">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Kelola Peran</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <form method="POST" action="javascript:void(0)" id="form-manage-role">
                        @csrf
                        @method("PUT")
                        <div class="modal-body" id="view-manage-role">

                        </div>
                        <div class="modal-footer bg-whitesmoke br">
                            <button type="submit" class="btn btn-success waves-effect waves-light" id="btn-save-manage-role">Simpan</button>
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
        <script src="@asset('templates/admin/ubold/assets/libs/datatables/datatables.min.js')"></script>
@endsection

@section('script-bottom')
    <script>
        $(function () {
            "use strict";

            @can('role.view')
                getRoles();
            @endcan

            @can('role.create')
                $("#form-add-role").on("submit", function (e) {
                    e.preventDefault();

                    if($("#add-role-name").val().length == 0 || $("#add-role-login-destination").val().length == 0){
                        notification('warning', 'Harap isi semua field.');
                        return false;
                    }

                    addRole();
                });
            @endcan

            @can('role.update')
                $("#form-update-role").on("submit", function (e) {
                    e.preventDefault();

                    if($("#update-role-name").val().length == 0 || $("#update-role-login-destination").val().length == 0){
                        notification('warning', 'Harap isi semua field.');
                        return false;
                    }

                    updateRole();
                });
            @endcan

            @can('role.manage')
                $("#form-manage-role").on("submit", function (e) {
                    e.preventDefault();
                    manageRole();
                });
            @endcan
        });

        @can('role.view')
            function getRoles()
            {
                $("#roles-table").dataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "@route('admin.roles.getRoles')",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}"
                        }
                    },
                    destroy: true,
                    columns: [
                        { data: 'DT_RowIndex' },
                        { data: 'name' },
                        { data: 'default_user' },
                        { data: 'login_destination' },
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
                    }
                });
            }
        @endcan

        @can('role.create')
            function addRole()
            {
                var formData = $("#form-add-role").serialize();

                $.ajax({
                    url: "@route('admin.roles.store')",
                    type: "POST",
                    dataType: "json",
                    data: formData,
                    beforeSend() {
                        $("#btn-save-add-role").html('<span class="spinner-border spinner-border-sm mr-1" role="status" aria-hidden="true"></span> Loading...');
                        $("#btn-save-add-role").attr('disabled', 'disabled');
                        $("input").attr('disabled', 'disabled');
                        $("button").attr('disabled', 'disabled');
                    },
                    complete() {
                        $("#btn-save-add-role").html('Simpan');
                        $("#btn-save-add-role").removeAttr('disabled', 'disabled');
                        $("input").removeAttr('disabled', 'disabled');
                        $("button").removeAttr('disabled', 'disabled');
                    },
                    success : function(result) {
                        if(result['status'] == 'success'){
                            $("#form-add-role")[0].reset();
                            Custombox.modal.close();
                            getRoles();
                        }

                        notification(result['status'], result['message']);
                    },
                    error : function(xhr, status, error) {
                        var err = eval('(' + xhr.responseText + ')');
                        notification(status, err.message);
                        checkCSRFToken(err.message);

                        setTimeout(() => {
                            $('#add-role-name').focus();
                        }, 50);
                    }
                });
            }
        @endcan

        @can('role.update')
            function getUpdateData(object)
            {
                var id = $(object).data('id');

                $.ajax({
                    url: "@route('admin.roles.show')",
                    type: "POST",
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'id': id,
                    },
                    dataType: "json",
                    beforeSend() {
                        $('#form-update-role')[0].reset();
                        $("#btn-save-update-role").html('<span class="spinner-border spinner-border-sm mr-1" role="status" aria-hidden="true"></span> Loading...');
                        $("#btn-save-update-role").attr('disabled', 'disabled');
                        $("input").attr('disabled', 'disabled');
                        $("button").attr('disabled', 'disabled');
                        openModal('#modal-update-role');
                    },
                    complete() {
                        $("#btn-save-update-role").html('Simpan');
                        $("#btn-save-update-role").removeAttr('disabled', 'disabled');
                        $("input").removeAttr('disabled', 'disabled');
                        $("button").removeAttr('disabled', 'disabled');
                    },
                    success : function(result) {
                        if(result['status'] == 'error'){
                            Custombox.modal.close();
                            notification(result['status'], result['message']);
                        } else {
                            $('#update-role-id').val(result['data']['id']);
                            $('#update-role-name').val(result['data']['name']);
                            $('#update-role-login-destination').val(result['data']['login_destination']);
                            focusable('#update-role-name');
                        }
                    },
                    error : function(xhr, status, error) {
                        var err = eval('(' + xhr.responseText + ')');
                        notification(status, err.message);
                        checkCSRFToken(err.message);

                        setTimeout(() => {
                            $('#update-role-name').focus();
                        }, 50);
                    }
                });
            }

            function updateRole()
            {
                var formData = $("#form-update-role").serialize();

                $.ajax({
                    url: "@route('admin.roles.update')",
                    type: "PUT",
                    dataType: "json",
                    data: formData,
                    beforeSend() {
                        $("#btn-save-update-role").html('<span class="spinner-border spinner-border-sm mr-1" role="status" aria-hidden="true"></span> Loading...');
                        $("#btn-save-update-role").attr('disabled', 'disabled');
                        $("input").attr('disabled', 'disabled');
                        $("button").attr('disabled', 'disabled');
                    },
                    complete() {
                        $("#btn-save-update-role").html('Simpan');
                        $("#btn-save-update-role").removeAttr('disabled', 'disabled');
                        $("input").removeAttr('disabled', 'disabled');
                        $("button").removeAttr('disabled', 'disabled');
                    },
                    success : function(result) {
                        if(result['status'] == 'success'){
                            $("#form-update-role")[0].reset();
                            Custombox.modal.close();
                            getRoles();
                        }

                        notification(result['status'], result['message']);
                    },
                    error : function(xhr, status, error) {
                        var err = eval('(' + xhr.responseText + ')');
                        notification(status, err.message);
                        checkCSRFToken(err.message);

                        setTimeout(() => {
                            $('#update-role-name').focus();
                        }, 50);
                    }
                });
            }
        @endcan

        @can('role.delete')
            function deleteRole(object)
            {
                var id = $(object).data('id');

                Swal.fire({
                        title: 'Anda yakin ingin menghapus Peran?',
                        text: 'Anda tidak dapat memulihkannya kembali',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, hapus!',
                        showLoaderOnConfirm:true,
                        preConfirm: () => {
                            ajax =  $.ajax({
                                        url: "@route('admin.roles.destroy')",
                                        type: "POST",
                                        dataType: "json",
                                        data: {
                                            "_method": "DELETE",
                                            "_token": "{{ csrf_token() }}",
                                            "id": id,
                                        },
                                        success : function(result) {
                                            if(result['status'] == 'success'){
                                                getRoles();
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

        @can('role.manage')
            function getManageData(object)
            {
                var id = $(object).data('id');

                $.ajax({
                    url: "@route('admin.roles.show.manage')",
                    type: "POST",
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'id': id,
                    },
                    dataType: "json",
                    beforeSend() {
                        $('#form-manage-role')[0].reset();
                        $("#btn-save-manage-role").html('<span class="spinner-border spinner-border-sm mr-1" role="status" aria-hidden="true"></span> Loading...');
                        $("#btn-save-manage-role").attr('disabled', 'disabled');
                        $("input").attr('disabled', 'disabled');
                        $("button").attr('disabled', 'disabled');
                        $('#modal-manage-role').modal('show');
                        $("#view-manage-role").html('<h4 class="text-center my-4">Loading . . .</h4>');
                    },
                    complete() {
                        $("#btn-save-manage-role").html('Simpan');
                        $("#btn-save-manage-role").removeAttr('disabled', 'disabled');
                        $("input").removeAttr('disabled', 'disabled');
                        $("button").removeAttr('disabled', 'disabled');
                    },
                    success : function(result) {
                        if(result['status'] == 'error'){
                            $('#modal-manage-role').modal('hide');
                            notification(result['status'], result['message']);
                        } else {
                            $("#view-manage-role").html(result['data']);
                        }
                    },
                    error : function(xhr, status, error) {
                        var err = eval('(' + xhr.responseText + ')');
                        notification(status, err.message);
                        checkCSRFToken(err.message);
                    }
                });
            }

            function manageRole()
            {
                var formData = $("#form-manage-role").serialize();

                $.ajax({
                    url: "@route('admin.roles.manage')",
                    type: "PUT",
                    dataType: "json",
                    data: formData,
                    beforeSend() {
                        $("#btn-save-manage-role").html('<span class="spinner-border spinner-border-sm mr-1" role="status" aria-hidden="true"></span> Loading...');
                        $("#btn-save-manage-role").attr('disabled', 'disabled');
                        $("input").attr('disabled', 'disabled');
                        $("button").attr('disabled', 'disabled');
                    },
                    complete() {
                        $("#btn-save-manage-role").html('Simpan');
                        $("#btn-save-manage-role").removeAttr('disabled', 'disabled');
                        $("input").removeAttr('disabled', 'disabled');
                        $("button").removeAttr('disabled', 'disabled');
                    },
                    success : function(result) {
                        if(result['status'] == 'success'){
                            $("#form-manage-role")[0].reset();
                            $('#modal-manage-role').modal('hide');
                            getRoles();
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

            function setDefault(object)
            {
                var id = $(object).data('id');

                Swal.fire({
                        title: 'Anda yakin ingin mengubah Pengguna Default Peran?',
                        text: 'Klik Ya untuk melanjutkan',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya',
                        showLoaderOnConfirm:true,
                        preConfirm: () => {
                            ajax =  $.ajax({
                                        url: "@route('admin.roles.setDefault')",
                                        type: "POST",
                                        dataType: "json",
                                        data: {
                                            "_method": "PUT",
                                            "_token": "{{ csrf_token() }}",
                                            "id": id,
                                        },
                                        success : function(result) {
                                            if(result['status'] == 'success'){
                                                getRoles();
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
    </script>
@endsection
