@extends('templates.admin.ubold.master')

@section('title', auth()->user()->name)

@section('css')
    <link href="@asset('templates/admin/ubold/assets/libs/dropify/dropify.min.css')" rel="stylesheet" type="text/css" />
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
                            <li class="breadcrumb-item active">Akun</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Akun</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-3">Edit Akun</h4>

                        <form action="javascript:void(0)" method="POST" id="form-update-account" enctype="multipart/form-data">
                            @csrf
                            @method("PUT")
                            <div class="row">
                                <div class="col-md-6 col-xs-12 form-group">
                                    <label for="">Nama Pengguna</label>
                                    <input type="text" class="form-control" name="username" id="username" placeholder="Masukkan Nama Pengguna" value="{{ auth()->user()->username }}" onkeyup="checkUsername(this.value)" required autofocus>
                                    <span class="help-block text-success" id="help-block-update-account-username">
                                        <div class="spinner-border spinner-border-sm text-primary mr-2" role="status" style="display:none;">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                        <small></small>
                                    </span>
                                </div>
                                <div class="col-md-6 col-xs-12 form-group">
                                    <label for="">Nama</label>
                                    <input type="text" class="form-control" name="name" id="name" placeholder="Masukkan Nama" value="{{ auth()->user()->name }}" required>
                                </div>
                                <div class="col-md-6 col-xs-12 form-group">
                                    <label for="">Email - <i class="{{ (auth()->user()->activation == 0 ? 'text-danger' : 'text-success') }}">{{ (auth()->user()->activation == 0 ? 'Belum Diaktivasi' : 'Sudah Diaktivasi') }}</i></label>
                                    <input type="email" class="form-control" name="email" id="email" placeholder="Masukkan Email" value="{{ auth()->user()->email }}" required>
                                </div>
                                <div class="col-md-6 col-xs-12 form-group">
                                    <label for="">No HP - <i class="{{ (auth()->user()->phone_verification == 0 ? 'text-danger' : 'text-success') }}">{{ (auth()->user()->phone_verification == 0 ? 'Belum Diverifikasi' : 'Sudah Diverifikasi') }}</i></label>
                                    <input type="text" class="form-control" name="phone" id="phone" placeholder="Masukkan No HP" value="{{ auth()->user()->phone }}" required>
                                </div>
                                <div class="col-md-6 col-xs-12 form-group">
                                    <label for="">Kata Sandi</label>
                                    <input type="password" class="form-control" name="password" id="password" placeholder="Masukkan Kata Sandi">
                                    <span class="help-block">
                                        <small>Kosongkan Kata Sandi jika tidak ingin mengubahnya</small>
                                    </span>
                                </div>
                                <div class="col-md-6 col-xs-12 form-group">
                                    <label for="">Konfirmasi Kata Sandi</label>
                                    <input type="password" class="form-control" name="confirmation_password" id="password-confirmation" placeholder="Masukkan Konfirmasi Kata Sandi">
                                </div>
                                <div class="col-12 form-group">
                                    <label for="">Foto</label>
                                    <input type="file" class="form-control" name="photo_url" id="photo-url" data-default-file="{{ auth()->user()->myPhoto() }}"/>
                                    @if (auth()->user()->photo_url != null)
                                    <div class="custom-control custom-checkbox mt-2">
                                        <input type="checkbox" class="custom-control-input" id="null-photo" name="null_photo" value="1">
                                        <label class="custom-control-label" for="null-photo">Kosongkan Foto</label>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary waves-effect waves-light" id="btn-save-update-account">Simpan Perubahan</button>
                        </form>
                    </div>
                </div>
            </div><!-- end col -->
        </div>
        <!-- end row -->

    </div> <!-- container -->
@endsection

@section('script')
    <script src="@asset('templates/admin/ubold/assets/libs/dropify/dropify.min.js')"></script>
@endsection

@section('script-bottom')
    <script>
        $(function () {
            "use strict";

            $("#form-update-account").on("submit", function (e) {
                e.preventDefault();

                if($("#username").val().length == 0 ||
                $("#name").val().length == 0 ||
                $("#email").val().length == 0 ||
                $("#phone").val().length == 0) {
                    notification('warning', 'Harap isi semua field.');
                    return false;
                }

                if($("#password").val() != $("#password-confirmation").val()){
                    notification('warning', 'Kata sandi yang dimasukkan tidak sama.');
                    return false;
                }

                var formData = new FormData(this);
                updateAccount(formData);
            });

            $('#photo-url').dropify({
                messages: {
                    'default': 'Seret dan taruh file di sini atau klik',
                    'replace': 'Seret dan lepas atau klik untuk mengganti',
                    'remove': 'Hapus',
                    'error': 'Ups, ada yang salah ditambahkan.'
                },
                error: {
                    'fileSize': 'Ukuran file terlalu besar (maks. 1M).'
                }
            });
        });

        function updateAccount(formData)
        {
            $.ajax({
                url: "@route('admin.account.update')",
                type: "POST",
                dataType: "json",
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                beforeSend() {
                    $("#btn-save-update-account").html('<span class="spinner-border spinner-border-sm mr-1" role="status" aria-hidden="true"></span> Loading...');
                    $("#btn-save-update-account").attr('disabled', 'disabled');
                    $("input").attr('disabled', 'disabled');
                },
                complete() {
                    $("#btn-save-update-account").html('Simpan Perubahan');
                    $("#btn-save-update-account").removeAttr('disabled', 'disabled');
                    $("input").removeAttr('disabled', 'disabled');
                },
                success : function(result) {
                    if(result['status'] == 'success'){
                        $("#password").val('');
                        $("#password-confirmation").val('');
                    }

                    notification(result['status'], result['message']);
                },
                error : function(xhr, status, error) {
                    var err = eval('(' + xhr.responseText + ')');
                    notification(status, err.message);
                    checkCSRFToken(err.message);

                    setTimeout(() => {
                        $('#username').focus();
                    }, 50);
                }
            })
        }

        var usernameHttpRequest;

        function checkUsername(username)
        {
            var id = "{{ auth()->user()->id }}";

            if(username == ''){
                if(usernameHttpRequest){
                    usernameHttpRequest.abort();
                }

                $('#help-block-update-account-username small').text('');
                $('#help-block-update-account-username .spinner-border').hide();

                return false;
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
                    type: 'update',
                    id: id,
                },
                beforeSend() {
                    $('#help-block-update-account-username small').text('');
                    $('#help-block-update-account-username .spinner-border').show();
                },
                complete() {
                    $('#help-block-update-account-username .spinner-border').hide();
                },
                success : function(result) {
                    $('#help-block-update-account-username small').text(result['message']);

                    if(result['status'] == 'error') {
                        $('#help-block-update-account-username').addClass('text-danger');
                        $('#help-block-update-account-username').removeClass('text-success');
                    }

                    if(result['status'] == 'success') {
                        $('#help-block-update-account-username').addClass('text-success');
                        $('#help-block-update-account-username').removeClass('text-danger');
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
