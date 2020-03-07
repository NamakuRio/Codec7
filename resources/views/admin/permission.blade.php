@extends('templates.admin.ubold.master')

@section('title', 'Izin')

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
                            <li class="breadcrumb-item active">Izin</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Izin</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-12">
                <div class="card-box">
                    @can('permission.create')
                        <a href="javascript:void(0);" class="btn btn-sm btn-blue waves-effect waves-light float-right" onclick="openModal('#modal-add-permission', 'fadein', '#add-permission-name')">
                            <i class="mdi mdi-plus-circle"></i> Tambah Izin
                        </a>
                    @endcan
                    <h4 class="header-title mb-4">Kelola Izin</h4>

                    <table class="table table-hover m-0 table-centered nowrap w-100" id="permissions-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama</th>
                                <th>Deskripsi</th>
                                <th class="hidden-sm">Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div><!-- end col -->
        </div>
        <!-- end row -->

    </div> <!-- container -->

    @can('permission.create')
        <!-- Modal Add -->
        <div id="modal-add-permission" class="modal-demo">
            <button type="button" class="close" onclick="Custombox.modal.close();">
                <span>&times;</span><span class="sr-only">Tutup</span>
            </button>
            <h4 class="custom-modal-title">Tambah Izin Baru</h4>
            <div class="custom-modal-text text-left">
                <form method="POST" action="javascript:void(0)" id="form-add-permission">
                    @csrf
                    <div class="form-group">
                        <label for="add-permission-name">Nama</label>
                        <input type="text" class="form-control" name="name" id="add-permission-name" placeholder="Masukkan Nama Izin">
                    </div>
                    <div class="form-group">
                        <label for="add-permission-guard-name">Deskripsi</label>
                        <input type="text" class="form-control" name="guard_name" id="add-permission-guard-name" placeholder="Masukkan Deskripsi Izin">
                    </div>

                    <div class="text-right">
                        <button type="submit" class="btn btn-success waves-effect waves-light" id="btn-save-add-permission">Simpan</button>
                        <button type="button" class="btn btn-danger waves-effect waves-light m-l-10" onclick="Custombox.modal.close();">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    @endcan

    @can('permission.update')
        <!-- Modal Update -->
        <div id="modal-update-permission" class="modal-demo">
            <button type="button" class="close" onclick="Custombox.modal.close();">
                <span>&times;</span><span class="sr-only">Tutup</span>
            </button>
            <h4 class="custom-modal-title">Edit Izin</h4>
            <div class="custom-modal-text text-left">
                <form method="POST" action="javascript:void(0)" id="form-update-permission">
                    @csrf
                    @method("PUT")
                    <input type="hidden" name="id" id="update-permission-id" required>
                    <div class="form-group">
                        <label for="update-permission-name">Nama</label>
                        <input type="text" class="form-control" name="name" id="update-permission-name" placeholder="Masukkan Nama Izin">
                    </div>
                    <div class="form-group">
                        <label for="update-permission-guard-name">Deskripsi</label>
                        <input type="text" class="form-control" name="guard_name" id="update-permission-guard-name" placeholder="Masukkan Deskripsi Izin">
                    </div>

                    <div class="text-right">
                        <button type="submit" class="btn btn-success waves-effect waves-light" id="btn-save-update-permission">Simpan</button>
                        <button type="button" class="btn btn-danger waves-effect waves-light m-l-10" onclick="Custombox.modal.close();">Batal</button>
                    </div>
                </form>
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

            @can('permission.view')
                getPermissions();
            @endcan

            @can('permission.create')
                $("#form-add-permission").on("submit", function (e) {
                    e.preventDefault();

                    if($("#add-permission-name").val().length == 0 || $("#add-permission-guard-name").val().length == 0){
                        notification('warning', 'Harap isi semua field.');
                        return false;
                    }

                    addPermission();
                });
            @endcan

            @can('permission.update')
                $("#form-update-permission").on("submit", function (e) {
                    e.preventDefault();

                    if($("#update-permission-name").val().length == 0 || $("#update-permission-guard-name").val().length == 0){
                        notification('warning', 'Harap isi semua field.');
                        return false;
                    }

                    updatePermission();
                });
            @endcan
        });

        @can('permission.view')
            function getPermissions()
            {
                $("#permissions-table").dataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "@route('admin.permissions.getPermissions')",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}"
                        }
                    },
                    destroy: true,
                    columns: [
                        { data: 'DT_RowIndex' },
                        { data: 'name' },
                        { data: 'guard_name' },
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

        @can('permission.create')
            function addPermission()
            {
                var formData = $("#form-add-permission").serialize();

                $.ajax({
                    url: "@route('admin.permissions.store')",
                    type: "POST",
                    dataType: "json",
                    data: formData,
                    beforeSend() {
                        $("#btn-save-add-permission").html('<span class="spinner-border spinner-border-sm mr-1" role="status" aria-hidden="true"></span> Loading...');
                        $("#btn-save-add-permission").attr('disabled', 'disabled');
                        $("input").attr('disabled', 'disabled');
                        $("button").attr('disabled', 'disabled');
                    },
                    complete() {
                        $("#btn-save-add-permission").html('Simpan');
                        $("#btn-save-add-permission").removeAttr('disabled', 'disabled');
                        $("input").removeAttr('disabled', 'disabled');
                        $("button").removeAttr('disabled', 'disabled');
                    },
                    success : function(result) {
                        if(result['status'] == 'success'){
                            $("#form-add-permission")[0].reset();
                            Custombox.modal.close();
                            getPermissions();
                        }

                        notification(result['status'], result['message']);
                    },
                    error : function(xhr, status, error) {
                        var err = eval('(' + xhr.responseText + ')');
                        notification(status, err.message);
                        checkCSRFToken(err.message);

                        setTimeout(() => {
                            $('#add-permission-name').focus();
                        }, 50);
                    }
                });
            }
        @endcan

        @can('permission.update')
            function getUpdateData(object)
            {
                var id = $(object).data('id');

                $.ajax({
                    url: "@route('admin.permissions.show')",
                    type: "POST",
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'id': id,
                    },
                    dataType: "json",
                    beforeSend() {
                        $('#form-update-permission')[0].reset();
                        $("#btn-save-update-permission").html('<span class="spinner-border spinner-border-sm mr-1" role="status" aria-hidden="true"></span> Loading...');
                        $("#btn-save-update-permission").attr('disabled', 'disabled');
                        $("input").attr('disabled', 'disabled');
                        $("button").attr('disabled', 'disabled');
                        openModal('#modal-update-permission');
                    },
                    complete() {
                        $("#btn-save-update-permission").html('Simpan');
                        $("#btn-save-update-permission").removeAttr('disabled', 'disabled');
                        $("input").removeAttr('disabled', 'disabled');
                        $("button").removeAttr('disabled', 'disabled');
                    },
                    success : function(result) {
                        if(result['status'] == 'error'){
                            Custombox.modal.close();
                            notification(result['status'], result['message']);
                        } else {
                            $('#update-permission-id').val(result['data']['id']);
                            $('#update-permission-name').val(result['data']['name']);
                            $('#update-permission-guard-name').val(result['data']['guard_name']);
                            focusable('#update-permission-name');
                        }
                    },
                    error : function(xhr, status, error) {
                        var err = eval('(' + xhr.responseText + ')');
                        notification(status, err.message);
                        checkCSRFToken(err.message);

                        setTimeout(() => {
                            $('#update-permission-name').focus();
                        }, 50);
                    }
                });
            }

            function updatePermission()
            {
                var formData = $("#form-update-permission").serialize();

                $.ajax({
                    url: "@route('admin.permissions.update')",
                    type: "PUT",
                    dataType: "json",
                    data: formData,
                    beforeSend() {
                        $("#btn-save-update-permission").html('<span class="spinner-border spinner-border-sm mr-1" role="status" aria-hidden="true"></span> Loading...');
                        $("#btn-save-update-permission").attr('disabled', 'disabled');
                        $("input").attr('disabled', 'disabled');
                        $("button").attr('disabled', 'disabled');
                    },
                    complete() {
                        $("#btn-save-update-permission").html('Simpan');
                        $("#btn-save-update-permission").removeAttr('disabled', 'disabled');
                        $("input").removeAttr('disabled', 'disabled');
                        $("button").removeAttr('disabled', 'disabled');
                    },
                    success : function(result) {
                        if(result['status'] == 'success'){
                            $("#form-update-permission")[0].reset();
                            Custombox.modal.close();
                            getPermissions();
                        }

                        notification(result['status'], result['message']);
                    },
                    error : function(xhr, status, error) {
                        var err = eval('(' + xhr.responseText + ')');
                        notification(status, err.message);
                        checkCSRFToken(err.message);

                        setTimeout(() => {
                            $('#update-permission-name').focus();
                        }, 50);
                    }
                });
            }
        @endcan

        @can('permission.delete')
            function deletePermission(object)
            {
                var id = $(object).data('id');

                Swal.fire({
                        title: 'Anda yakin ingin menghapus Izin?',
                        text: 'Anda tidak dapat memulihkannya kembali',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, hapus!',
                        showLoaderOnConfirm:true,
                        preConfirm: () => {
                            ajax =  $.ajax({
                                        url: "@route('admin.permissions.destroy')",
                                        type: "POST",
                                        dataType: "json",
                                        data: {
                                            "_method": "DELETE",
                                            "_token": "{{ csrf_token() }}",
                                            "id": id,
                                        },
                                        success : function(result) {
                                            if(result['status'] == 'success'){
                                                getPermissions();
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
