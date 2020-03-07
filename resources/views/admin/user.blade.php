@extends('templates.admin.ubold.master')

@section('title', 'Pengguna')

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
                            <li class="breadcrumb-item active">Pengguna</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Pengguna</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-md-6 col-xl-3">
                <div class="widget-rounded-circle card-box">
                    <div class="row">
                        <div class="col-6">
                            <div class="avatar-lg rounded-circle bg-primary">
                                <i class="fe-users font-22 avatar-title text-white"></i>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-right">
                                <h3 class="text-dark mt-1"><span data-plugin="counterup">{{ $countAllUser }}</span></h3>
                                <p class="text-muted mb-1 text-truncate">Total Pengguna</p>
                            </div>
                        </div>
                    </div> <!-- end row-->
                </div> <!-- end widget-total-users-->
            </div> <!-- end col-->

            <div class="col-md-6 col-xl-3">
                <div class="widget-rounded-circle card-box">
                    <div class="row">
                        <div class="col-6">
                            <div class="avatar-lg rounded-circle bg-warning">
                                <i class="fe-clock font-22 avatar-title text-white"></i>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-right">
                                <h3 class="text-dark mt-1"><span data-plugin="counterup">{{ $countInactiveUser }}</span></h3>
                                <p class="text-muted mb-1 text-truncate">Belum Aktif</p>
                            </div>
                        </div>
                    </div> <!-- end row-->
                </div> <!-- end widget-users-inactive-->
            </div> <!-- end col-->

            <div class="col-md-6 col-xl-3">
                <div class="widget-rounded-circle card-box">
                    <div class="row">
                        <div class="col-6">
                            <div class="avatar-lg rounded-circle bg-success">
                                <i class="fe-check-circle font-22 avatar-title text-white"></i>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-right">
                                <h3 class="text-dark mt-1"><span data-plugin="counterup">{{ $countActiveUser }}</span></h3>
                                <p class="text-muted mb-1 text-truncate">Sudah Aktif</p>
                            </div>
                        </div>
                    </div> <!-- end row-->
                </div> <!-- end widget-users-active-->
            </div> <!-- end col-->

            <div class="col-md-6 col-xl-3">
                <div class="widget-rounded-circle card-box">
                    <div class="row">
                        <div class="col-6">
                            <div class="avatar-lg rounded-circle bg-danger">
                                <i class="fe-alert-circle font-22 avatar-title text-white"></i>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-right">
                                <h3 class="text-dark mt-1"><span data-plugin="counterup">{{ $countBlockedUser }}</span></h3>
                                <p class="text-muted mb-1 text-truncate">Diblokir</p>
                            </div>
                        </div>
                    </div> <!-- end row-->
                </div> <!-- end widget-users-blocked-->
            </div> <!-- end col-->
        </div>
        <!-- end row -->

        <div class="row">
            <div class="col-12">
                <div class="card-box">
                    @can('user.create')
                        <a href="javascript:void(0);" class="btn btn-sm btn-blue waves-effect waves-light float-right" onclick="$('#modal-add-user').modal('show');focusable('#add-user-username')">
                            <i class="mdi mdi-plus-circle"></i> Tambah Pengguna
                        </a>
                    @endcan
                    <h4 class="header-title mb-4">Kelola Pengguna</h4>

                    <table class="table table-hover m-0 table-centered nowrap w-100" id="users-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Peran</th>
                                <th>Nama</th>
                                <th>Nama Pengguna</th>
                                <th>Email</th>
                                <th>No. HP</th>
                                <th>Status</th>
                                <th>Verifikasi Email</th>
                                <th>Verifikasi No HP</th>
                                <th class="hidden-sm">Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div><!-- end col -->
        </div>
        <!-- end row -->

    </div> <!-- container -->

    @can('user.create')
        <!-- Modal Add -->
        <div class="modal fade" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;" id="modal-add-user">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Tambah Pengguna Baru</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <form method="POST" action="javascript:void(0)" id="form-add-user">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <div class="form-group col-md-6 col-xs-12">
                                    <label for="add-user-role">Peran</label>
                                    <select name="role" id="add-user-role" class="form-control"></select>
                                </div>
                                <div class="form-group col-md-6 col-xs-12">
                                    <label for="add-user-username">Nama Pengguna</label>
                                    <input type="text" class="form-control" name="username" id="add-user-username" placeholder="Masukkan Nama Pengguna" onkeyup="checkUsername(this.value)">
                                    <span class="help-block text-success" id="help-block-add-user-username">
                                        <div class="spinner-border spinner-border-sm text-primary mr-2" role="status" style="display:none;">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                        <small></small>
                                    </span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6 col-xs-12">
                                    <label for="add-user-name">Nama</label>
                                    <input type="text" class="form-control" name="name" id="add-user-name" placeholder="Masukkan Nama Pengguna">
                                </div>
                                <div class="form-group col-md-6 col-xs-12">
                                    <label for="add-user-email">Email</label>
                                    <input type="text" class="form-control" name="email" id="add-user-email" placeholder="Masukkan Email Pengguna">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6 col-xs-12">
                                    <label for="add-user-password">Kata Sandi</label>
                                    <input type="password" class="form-control" name="password" id="add-user-password" placeholder="Masukkan Kata Sandi Pengguna">
                                </div>
                                <div class="form-group col-md-6 col-xs-12">
                                    <label for="add-user-password-confirmation">Konfirmasi Kata Sandi</label>
                                    <input type="password" class="form-control" name="confirmation_password" id="add-user-password-confirmation" placeholder="Masukkan Konfirmasi Kata Sandi Pengguna">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-12">
                                    <label for="add-user-phone">No HP</label>
                                    <input type="text" class="form-control" name="phone" id="add-user-phone" placeholder="Masukkan No HP Pengguna">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer bg-whitesmoke br">
                            <button type="submit" class="btn btn-success waves-effect waves-light" id="btn-save-add-user">Simpan</button>
                            <button type="button" class="btn btn-danger waves-effect waves-light m-l-10" data-dismiss="modal">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan

    @can('user.update')
        <!-- Modal Update -->
        <div class="modal fade" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;" id="modal-update-user">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Edit Pengguna</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <form method="POST" action="javascript:void(0)" id="form-update-user">
                        @csrf
                        @method("PUT")
                        <div class="modal-body">
                            <input type="hidden" name="id" id="update-user-id" required>
                            <div class="row">
                                <div class="form-group col-md-6 col-xs-12">
                                    <label for="add-user-role">Peran</label>
                                    <select name="role" id="update-user-role" class="form-control"></select>
                                </div>
                                <div class="form-group col-md-6 col-xs-12">
                                    <label for="update-user-username">Nama Pengguna</label>
                                    <input type="text" class="form-control" name="username" id="update-user-username" placeholder="Masukkan Nama Pengguna" onkeyup="checkUsername(this.value, 'update')">
                                    <span class="help-block text-success" id="help-block-update-user-username">
                                        <div class="spinner-border spinner-border-sm text-primary mr-2" role="status" style="display:none;">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                        <small></small>
                                    </span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6 col-xs-12">
                                    <label for="update-user-name">Nama</label>
                                    <input type="text" class="form-control" name="name" id="update-user-name" placeholder="Masukkan Nama Pengguna">
                                </div>
                                <div class="form-group col-md-6 col-xs-12">
                                    <label for="update-user-email">Email</label>
                                    <input type="text" class="form-control" name="email" id="update-user-email" placeholder="Masukkan Email Pengguna">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6 col-xs-12">
                                    <label for="update-user-password">Kata Sandi</label>
                                    <input type="password" class="form-control" name="password" id="update-user-password" placeholder="Masukkan Kata Sandi Pengguna">
                                    <span class="help-block">
                                        <small>Kosongkan Kata Sandi jika tidak ingin mengubahnya</small>
                                    </span>
                                </div>
                                <div class="form-group col-md-6 col-xs-12">
                                    <label for="update-user-password-confirmation">Konfirmasi Kata Sandi</label>
                                    <input type="password" class="form-control" name="confirmation_password" id="update-user-password-confirmation" placeholder="Masukkan Konfirmasi Kata Sandi Pengguna">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-12">
                                    <label for="update-user-phone">No HP</label>
                                    <input type="text" class="form-control" name="phone" id="update-user-phone" placeholder="Masukkan No HP Pengguna">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer bg-whitesmoke br">
                            <button type="submit" class="btn btn-success waves-effect waves-light" id="btn-save-update-user">Simpan</button>
                            <button type="button" class="btn btn-danger waves-effect waves-light m-l-10" data-dismiss="modal">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;" id="modal-update-user-status">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Edit Status</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <form method="POST" action="javascript:void(0)" id="form-update-user-status">
                        @csrf
                        @method("PUT")
                        <div class="modal-body">
                            <input type="hidden" name="id" id="update-user-status-id" required>
                            <div class="row">
                                <div class="form-group col-12">
                                    <label for="update-user-status-selection">Status</label>
                                    <select name="status" id="update-user-status-selection" class="form-control">
                                        {{-- <option value="0">Belum Aktif</option>
                                        <option value="1">Aktif</option>
                                        <option value="2">Diblokir</option> --}}
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer bg-whitesmoke br">
                            <button type="submit" class="btn btn-success waves-effect waves-light" id="btn-save-update-user-status">Simpan</button>
                            <button type="button" class="btn btn-danger waves-effect waves-light m-l-10" data-dismiss="modal">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan

    @can('user.manage')
        <!--  Modal Manage -->
        <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;" id="modal-manage-user">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Kelola Pengguna</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <form method="POST" action="javascript:void(0)" id="form-manage-user">
                        @csrf
                        @method("PUT")
                        <div class="modal-body" id="view-manage-user">

                        </div>
                        <div class="modal-footer bg-whitesmoke br">
                            <button type="submit" class="btn btn-success waves-effect waves-light" id="btn-save-manage-user">Simpan</button>
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

            $('#update-user-status-selection').select2({
                width: '100%',
                placeholder: 'Pilih Status',
                data: [
                    {
                        id: 0,
                        text: 'Belum Aktif',
                    },
                    {
                        id: 1,
                        text: 'Aktif',
                    },
                    {
                        id: 2,
                        text: 'Diblokir',
                    },
                ]
            });

            $('#add-user-role').select2({
                width: '100%',
                placeholder: 'Pilih Peran',
                minimumInputLength: 1,
                ajax: {
                    url: "@route('admin.roles.select2')",
                    type: "POST",
                    dataType: "json",
                    quietMillis: 50,
                    delay: 250,
                    data: function (term) {
                        var text = term.term ? term.term : '';
                        var query = {
                            _type: term._type,
                            term: text,
                        };
                        return {
                            data: query,
                            _token: '{{ csrf_token() }}',
                        };
                    },
                    processResults : function(data) {;
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item.name,
                                    id: item.id
                                }
                            })
                        }
                    }
                }
            });

            $('#update-user-role').select2({
                width: '100%',
                placeholder: 'Pilih Peran',
                minimumInputLength: 1,
                ajax: {
                    url: "@route('admin.roles.select2')",
                    type: "POST",
                    dataType: "json",
                    quietMillis: 50,
                    delay: 250,
                    data: function (term) {
                        var text = term.term ? term.term : '';
                        var query = {
                            _type: term._type,
                            term: text,
                        };
                        return {
                            data: query,
                            _token: '{{ csrf_token() }}',
                        };
                    },
                    processResults : function(data) {;
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item.name,
                                    id: item.id
                                }
                            })
                        }
                    }
                }
            });

            @can('user.view')
                getUsers();
            @endcan

            @can('user.create')
                $("#form-add-user").on("submit", function (e) {
                    e.preventDefault();

                    if($("#add-user-username").val().length == 0 ||
                    $("#add-user-name").val().length == 0 ||
                    $("#add-user-email").val().length == 0 ||
                    $("#add-user-password").val().length == 0 ||
                    $("#add-user-password-confirmation").val().length == 0 ||
                    $("#add-user-phone").val().length == 0){
                        notification('warning', 'Harap isi semua field.');
                        return false;
                    }

                    if($("#add-user-password").val() != $("#add-user-password-confirmation").val()){
                        notification('warning', 'Kata sandi yang dimasukkan tidak sama.');
                        return false;
                    }

                    addUser();
                });
            @endcan

            @can('user.update')
                $("#form-update-user").on("submit", function (e) {
                    e.preventDefault();

                    if($("#update-user-username").val().length == 0 ||
                    $("#update-user-name").val().length == 0 ||
                    $("#update-user-email").val().length == 0 ||
                    $("#update-user-phone").val().length == 0){
                        notification('warning', 'Harap isi semua field.');
                        return false;
                    }

                    if($("#update-user-password").val() != $("#update-user-password-confirmation").val()){
                        notification('warning', 'Kata sandi yang dimasukkan tidak sama.');
                        return false;
                    }

                    updateUser();
                });

                $("#form-update-user-status").on("submit", function (e) {
                    e.preventDefault();

                    updateStatus();
                });
            @endcan

            @can('user.manage')
                $("#form-manage-user").on("submit", function (e) {
                    e.preventDefault();
                    manageUser();
                });
            @endcan
        });

        @can('user.view')
            function getUsers()
            {
                $("#users-table").dataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "@route('admin.users.getUsers')",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}"
                        }
                    },
                    destroy: true,
                    columns: [
                        { data: 'DT_RowIndex' },
                        { data: 'role' },
                        { data: 'name' },
                        { data: 'username' },
                        { data: 'email' },
                        { data: 'phone' },
                        { data: 'status' },
                        { data: 'activation' },
                        { data: 'phone_verification' },
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
                    columnDefs: [
                        {
                            className: "table-user",
                            targets: [
                                2
                            ],
                        },
                        {
                            className: "text-center",
                            targets: [
                                7,8
                            ],
                        }
                    ]
                });
            }
        @endcan

        @can('user.create')
            function addUser()
            {
                var formData = $("#form-add-user").serialize();

                $.ajax({
                    url: "@route('admin.users.store')",
                    type: "POST",
                    dataType: "json",
                    data: formData,
                    beforeSend() {
                        $("#btn-save-add-user").html('<span class="spinner-border spinner-border-sm mr-1" role="status" aria-hidden="true"></span> Loading...');
                        $("#btn-save-add-user").attr('disabled', 'disabled');
                        $("input").attr('disabled', 'disabled');
                        $("button").attr('disabled', 'disabled');
                    },
                    complete() {
                        $("#btn-save-add-user").html('Simpan');
                        $("#btn-save-add-user").removeAttr('disabled', 'disabled');
                        $("input").removeAttr('disabled', 'disabled');
                        $("button").removeAttr('disabled', 'disabled');
                    },
                    success : function(result) {
                        if(result['status'] == 'success'){
                            $("#form-add-user")[0].reset();
                            $('#modal-add-user').modal('hide');
                            getUsers();

                            $('#help-block-add-user-username small').text('');
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

        @can('user.update')
            function getUpdateData(object)
            {
                var id = $(object).data('id');

                $.ajax({
                    url: "@route('admin.users.show')",
                    type: "POST",
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'id': id,
                    },
                    dataType: "json",
                    beforeSend() {
                        $('#form-update-user')[0].reset();
                        $("#btn-save-update-user").html('<span class="spinner-border spinner-border-sm mr-1" role="status" aria-hidden="true"></span> Loading...');
                        $("#btn-save-update-user").attr('disabled', 'disabled');
                        $("input").attr('disabled', 'disabled');
                        $("button").attr('disabled', 'disabled');
                        $('#modal-update-user').modal('show');
                        $('#update-user-role option').remove();
                        if(usernameHttpRequest && usernameHttpRequest.readyState != 4){
                            usernameHttpRequest.abort();
                        }
                        $('#help-block-update-user-username small').text('');
                        $('#help-block-update-user-username .spinner-border').hide();
                    },
                    complete() {
                        $("#btn-save-update-user").html('Simpan');
                        $("#btn-save-update-user").removeAttr('disabled', 'disabled');
                        $("input").removeAttr('disabled', 'disabled');
                        $("button").removeAttr('disabled', 'disabled');
                    },
                    success : function(result) {
                        if(result['status'] == 'error'){
                            $('#modal-update-user').modal('hide');
                            notification(result['status'], result['message']);
                        } else {
                            $('#update-user-role').append('<option value="'+result['role']['id']+'" selected>'+result['role']['name']+'</option>');
                            $('#update-user-id').val(result['data']['id']);
                            $('#update-user-username').val(result['data']['username']);
                            $('#update-user-name').val(result['data']['name']);
                            $('#update-user-email').val(result['data']['email']);
                            $('#update-user-phone').val(result['data']['phone']);
                            focusable('#update-user-username');
                        }
                    },
                    error : function(xhr, status, error) {
                        var err = eval('(' + xhr.responseText + ')');
                        notification(status, err.message);
                        checkCSRFToken(err.message);
                    }
                });
            }

            function updateUser()
            {
                var formData = $("#form-update-user").serialize();

                $.ajax({
                    url: "@route('admin.users.update')",
                    type: "PUT",
                    dataType: "json",
                    data: formData,
                    beforeSend() {
                        $("#btn-save-update-user").html('<span class="spinner-border spinner-border-sm mr-1" role="status" aria-hidden="true"></span> Loading...');
                        $("#btn-save-update-user").attr('disabled', 'disabled');
                        $("input").attr('disabled', 'disabled');
                        $("button").attr('disabled', 'disabled');
                    },
                    complete() {
                        $("#btn-save-update-user").html('Simpan');
                        $("#btn-save-update-user").removeAttr('disabled', 'disabled');
                        $("input").removeAttr('disabled', 'disabled');
                        $("button").removeAttr('disabled', 'disabled');
                    },
                    success : function(result) {
                        if(result['status'] == 'success'){
                            $("#form-update-user")[0].reset();
                            $('#modal-update-user').modal('hide');
                            getUsers();
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

            function getUpdateStatus(object)
            {
                var id = $(object).data('id');

                $.ajax({
                    url: "@route('admin.users.show')",
                    type: "POST",
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'id': id,
                    },
                    dataType: "json",
                    beforeSend() {
                        $('#form-update-user-status')[0].reset();
                        $("#btn-save-update-user-status").html('<span class="spinner-border spinner-border-sm mr-1" role="status" aria-hidden="true"></span> Loading...');
                        $("#btn-save-update-user-status").attr('disabled', 'disabled');
                        $("select").attr('disabled', 'disabled');
                        $("button").attr('disabled', 'disabled');
                        $('#modal-update-user-status').modal('show');
                    },
                    complete() {
                        $("#btn-save-update-user-status").html('Simpan');
                        $("#btn-save-update-user-status").removeAttr('disabled', 'disabled');
                        $("select").removeAttr('disabled', 'disabled');
                        $("button").removeAttr('disabled', 'disabled');
                    },
                    success : function(result) {
                        if(result['status'] == 'error'){
                            $('#modal-update-user-status').modal('hide');
                            notification(result['status'], result['message']);
                        } else {
                            $('#update-user-status-id').val(result['data']['id']);
                            $('#update-user-status-selection').val(result['data']['status']);
                            $('#update-user-status-selection').trigger('change.select2');
                        }
                    },
                    error : function(xhr, status, error) {
                        var err = eval('(' + xhr.responseText + ')');
                        notification(status, err.message);
                        checkCSRFToken(err.message);
                    }
                });
            }

            function updateStatus()
            {
                var formData = $("#form-update-user-status").serialize();

                $.ajax({
                    url: "@route('admin.users.update.status')",
                    type: "PUT",
                    dataType: "json",
                    data: formData,
                    beforeSend() {
                        $("#btn-save-update-user-status").html('<span class="spinner-border spinner-border-sm mr-1" role="status" aria-hidden="true"></span> Loading...');
                        $("#btn-save-update-user-status").attr('disabled', 'disabled');
                        $("select").attr('disabled', 'disabled');
                        $("button").attr('disabled', 'disabled');
                    },
                    complete() {
                        $("#btn-save-update-user-status").html('Simpan');
                        $("#btn-save-update-user-status").removeAttr('disabled', 'disabled');
                        $("select").removeAttr('disabled', 'disabled');
                        $("button").removeAttr('disabled', 'disabled');
                    },
                    success : function(result) {
                        if(result['status'] == 'success'){
                            $("#form-update-user-status")[0].reset();
                            $('#modal-update-user-status').modal('hide');
                            getUsers();
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

            function updateVerification(object, type = 'email')
            {
                var id = $(object).data('id');

                var title   = "Anda yakin ingin mengubah Verifikasi Email?",
                    text    = "Anda ada beberapa perubahan yang dilakukan",
                    url     = "@route('admin.users.update.verification')";

                if(type == 'phone') {
                    title = "Anda yakin ingin mengubah Verifikasi No HP?";
                }

                Swal.fire({
                        title: title,
                        text: text,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, ubah!',
                        showLoaderOnConfirm:true,
                        preConfirm: () => {
                            ajax =  $.ajax({
                                        url:url,
                                        type: "POST",
                                        dataType: "json",
                                        data: {
                                            "_method": "PUT",
                                            "_token": "{{ csrf_token() }}",
                                            "id": id,
                                            "type": type
                                        },
                                        success : function(result) {
                                            if(result['status'] == 'success'){
                                                getUsers();
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

        @can('user.delete')
            function deleteUser(object)
            {
                var id = $(object).data('id');

                Swal.fire({
                        title: 'Anda yakin ingin menghapus Pengguna?',
                        text: 'Anda tidak dapat memulihkannya kembali',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, hapus!',
                        showLoaderOnConfirm:true,
                        preConfirm: () => {
                            ajax =  $.ajax({
                                        url: "@route('admin.users.destroy')",
                                        type: "POST",
                                        dataType: "json",
                                        data: {
                                            "_method": "DELETE",
                                            "_token": "{{ csrf_token() }}",
                                            "id": id,
                                        },
                                        success : function(result) {
                                            if(result['status'] == 'success'){
                                                getUsers();
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

        @can('user.manage')
            function getManageData(object)
            {
                var id = $(object).data('id');

                $.ajax({
                    url: "@route('admin.users.show.manage')",
                    type: "POST",
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'id': id,
                    },
                    dataType: "json",
                    beforeSend() {
                        $('#form-manage-user')[0].reset();
                        $("#btn-save-manage-user").html('<span class="spinner-border spinner-border-sm mr-1" role="status" aria-hidden="true"></span> Loading...');
                        $("#btn-save-manage-user").attr('disabled', 'disabled');
                        $("input").attr('disabled', 'disabled');
                        $("button").attr('disabled', 'disabled');
                        $('#modal-manage-user').modal('show');
                        $("#view-manage-user").html('<h4 class="text-center my-4">Loading . . .</h4>');
                    },
                    complete() {
                        $("#btn-save-manage-user").html('Simpan');
                        $("#btn-save-manage-user").removeAttr('disabled', 'disabled');
                        $("input").removeAttr('disabled', 'disabled');
                        $("button").removeAttr('disabled', 'disabled');
                    },
                    success : function(result) {
                        if(result['status'] == 'error'){
                            $('#modal-manage-user').modal('hide');
                            notification(result['status'], result['message']);
                        } else {
                            $("#view-manage-user").html(result['data']);
                        }
                    },
                    error : function(xhr, status, error) {
                        var err = eval('(' + xhr.responseText + ')');
                        notification(status, err.message);
                        checkCSRFToken(err.message);
                    }
                });
            }

            function manageUser()
            {
                var formData = $("#form-manage-user").serialize();

                $.ajax({
                    url: "@route('admin.users.manage')",
                    type: "PUT",
                    dataType: "json",
                    data: formData,
                    beforeSend() {
                        $("#btn-save-manage-user").html('<span class="spinner-border spinner-border-sm mr-1" role="status" aria-hidden="true"></span> Loading...');
                        $("#btn-save-manage-user").attr('disabled', 'disabled');
                        $("input").attr('disabled', 'disabled');
                        $("button").attr('disabled', 'disabled');
                    },
                    complete() {
                        $("#btn-save-manage-user").html('Simpan');
                        $("#btn-save-manage-user").removeAttr('disabled', 'disabled');
                        $("input").removeAttr('disabled', 'disabled');
                        $("button").removeAttr('disabled', 'disabled');
                    },
                    success : function(result) {
                        if(result['status'] == 'success'){
                            $("#form-manage-user")[0].reset();
                            $('#modal-manage-user').modal('hide');
                            getUsers();
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

        var usernameHttpRequest;

        function checkUsername(username, type = 'insert')
        {
            var id = 0;

            if(username == ''){
                if(usernameHttpRequest){
                    usernameHttpRequest.abort();
                }
                if(type == 'insert'){
                    $('#help-block-add-user-username small').text('');
                    $('#help-block-add-user-username .spinner-border').hide();
                }
                else if(type == 'update'){
                    $('#help-block-update-user-username small').text('');
                    $('#help-block-update-user-username .spinner-border').hide();
                }

                return false;
            }

            if(type == 'update'){
                id = $('#update-user-id').val();
            }

            if(usernameHttpRequest && usernameHttpRequest.readyState != 4){
                usernameHttpRequest.abort();
            }

            usernameHttpRequest = $.ajax({
                url: "@route('admin.users.checkUsername')",
                type: "POST",
                dataType: "json",
                data: {
                    _token: '{{ csrf_token() }}',
                    username: username,
                    type: type,
                    id: id,
                },
                beforeSend() {
                    if(type == 'insert'){
                        $('#help-block-add-user-username small').text('');
                        $('#help-block-add-user-username .spinner-border').show();
                    }
                    else if(type == 'update'){
                        $('#help-block-update-user-username small').text('');
                        $('#help-block-update-user-username .spinner-border').show();
                    }
                },
                complete() {
                    if(type == 'insert'){
                        $('#help-block-add-user-username .spinner-border').hide();
                    }
                    else if(type == 'update'){
                        $('#help-block-update-user-username .spinner-border').hide();
                    }
                },
                success : function(result) {
                    if(type == 'insert'){
                        $('#help-block-add-user-username small').text(result['message']);

                        if(result['status'] == 'error') {
                            $('#help-block-add-user-username').addClass('text-danger');
                            $('#help-block-add-user-username').removeClass('text-success');
                        }

                        if(result['status'] == 'success') {
                            $('#help-block-add-user-username').addClass('text-success');
                            $('#help-block-add-user-username').removeClass('text-danger');
                        }
                    } else if(type == 'update'){
                        $('#help-block-update-user-username small').text(result['message']);

                        if(result['status'] == 'error') {
                            $('#help-block-update-user-username').addClass('text-danger');
                            $('#help-block-update-user-username').removeClass('text-success');
                        }

                        if(result['status'] == 'success') {
                            $('#help-block-update-user-username').addClass('text-success');
                            $('#help-block-update-user-username').removeClass('text-danger');
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
