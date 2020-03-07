@extends('templates.admin.ubold.auth')

@section('title', 'Daftar')

@section('body-class', 'authentication-bg-pattern')

@section('content')
    <div class="account-pages mt-5 mb-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card bg-pattern">

                        <div class="card-body p-4">

                            <div class="text-center w-75 m-auto">
                                <a href="@route('register')">
                                    <span><img src="@asset('images/placeholder.jpg')" data-original="@asset('templates/admin/ubold/assets/images/logo-dark.png')" class="lazy" alt="" height="22"></span>
                                </a>
                                <p class="text-muted mb-4 mt-3">Belum punya akun? Buat akun Anda, dibutuhkan kurang dari satu menit</p>
                            </div>

                            <form action="javascript:void(0)" method="POST" id="register-form">
                                @csrf
                                <div class="form-group">
                                    <label for="username">Nama Pengguna</label>
                                    <input class="form-control" type="text" name="username" id="username" placeholder="Masukkan Nama Pengguna" required autofocus onkeyup="checkUsername(this.value)">
                                    <span class="help-block text-success" id="help-block-register-username">
                                        <div class="spinner-border spinner-border-sm text-primary mr-2" role="status" style="display:none;">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                        <small></small>
                                    </span>
                                </div>
                                <div class="form-group">
                                    <label for="name">Nama</label>
                                    <input class="form-control" type="text" name="name" id="name" placeholder="Masukkan Nama" required>
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input class="form-control" type="email" name="email" id="email" placeholder="Masukkan Email" required>
                                </div>
                                <div class="form-group">
                                    <label for="password">Kata Sandi</label>
                                    <input class="form-control" type="password" name="password" id="password" placeholder="Masukkan Kata Sandi" required>
                                </div>
                                <div class="form-group">
                                    <label for="password-confirmation">Konfirmasi Kata Sandi</label>
                                    <input class="form-control" type="password" name="confirmation_password" id="password-confirmation" placeholder="Masukkan Konfirmasi Kata Sandi" required>
                                </div>
                                <div class="form-group">
                                    <label for="phone">No HP</label>
                                    <input class="form-control" type="text" name="phone" id="phone" placeholder="Masukkan No HP" required>
                                </div>
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="checkbox-signup" required>
                                        <label class="custom-control-label" for="checkbox-signup">Saya menerima <a href="javascript: void(0);" class="text-dark">Syarat dan Ketentuan</a></label>
                                    </div>
                                </div>
                                <div class="form-group mb-0 text-center">
                                    <button class="btn btn-success btn-block" type="submit" id="register-btn"> Daftar </button>
                                </div>

                            </form>

                        </div> <!-- end card-body -->
                    </div>
                    <!-- end card -->

                    <div class="row mt-3">
                        <div class="col-12 text-center">
                            <p class="text-white-50">Sudah punya akun? <a href="@route('login')" class="text-white ml-1"><b>Masuk</b></a></p>
                        </div> <!-- end col -->
                    </div>
                    <!-- end row -->

                </div> <!-- end col -->
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </div>
    <!-- end page -->

    <footer class="footer footer-alt">
        Hak Cipta &copy; CoffeeDev
    </footer>
@endsection

@section('script-bottom')
    <script>
        $(function () {
            "use strict";

            $('#register-form').on('submit', function(e) {
                e.preventDefault();

                if($('#username').val().length == 0 ||
                $('#name').val().length == 0 ||
                $('#email').val().length == 0 ||
                $('#password').val().length == 0 ||
                $('#password-confirmation').val().length == 0 ||
                $('#phone').val().length == 0){
                    notification('warning', 'Harap isi semua kolom.');
                    return false;
                }

                if($("#password").val() != $("#password-confirmation").val()){
                    notification('warning', 'Kata sandi yang dimasukkan tidak sama.');
                    return false;
                }

                register();
            });
        });

        function register()
        {
            var formData = $('#register-form').serialize();

            $.ajax({
                url: "@route('register')",
                type: "POST",
                dataType: "json",
                data: formData,
                beforeSend() {
                    $('#register-btn').html('<span class="spinner-border spinner-border-sm mr-1" role="status" aria-hidden="true"></span> Loading...');
                    $('button').attr('disabled', 'disabled');
                    $('input').attr('disabled', 'disabled');
                },
                complete() {
                    $('#register-btn').html('Daftar');
                    $('button').removeAttr('disabled', 'disabled');
                    $('input').removeAttr('disabled', 'disabled');
                },
                success : function (result) {
                    notification(result['status'], result['message']);

                    setTimeout(() => {
                        $('#username').focus();
                    }, 50);

                    if(result['status'] == 'success'){
                        window.location = "@route('login')";
                    }
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

        function checkUsername(username, type = 'insert')
        {
            var id = 0;

            if(username == ''){
                if(usernameHttpRequest){
                    usernameHttpRequest.abort();
                }
                $('#help-block-register-username small').text('');
                $('#help-block-register-username .spinner-border').hide();

                return false;
            }

            if(usernameHttpRequest && usernameHttpRequest.readyState != 4){
                usernameHttpRequest.abort();
            }

            usernameHttpRequest = $.ajax({
                url: "@route('register.checkUsername')",
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
                        $('#help-block-register-username small').text('');
                        $('#help-block-register-username .spinner-border').show();
                    }
                },
                complete() {
                    if(type == 'insert'){
                        $('#help-block-register-username .spinner-border').hide();
                    }
                },
                success : function(result) {
                    if(type == 'insert'){
                        $('#help-block-register-username small').text(result['message']);

                        if(result['status'] == 'error') {
                            $('#help-block-register-username').addClass('text-danger');
                            $('#help-block-register-username').removeClass('text-success');
                        }

                        if(result['status'] == 'success') {
                            $('#help-block-register-username').addClass('text-success');
                            $('#help-block-register-username').removeClass('text-danger');
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
