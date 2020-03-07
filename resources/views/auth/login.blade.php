@extends('templates.admin.ubold.auth')

@section('title', 'Masuk')

@section('body-class', 'authentication-bg-pattern')

@section('content')
    <div class="account-pages mt-5 mb-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <x-alert-component/>
                    <div class="card bg-pattern">

                        <div class="card-body p-4">

                            <div class="text-center w-75 m-auto">
                                <a href="@route('login')">
                                    <span><img src="@asset('images/placeholder.jpg')" data-original="@asset('templates/admin/ubold/assets/images/logo-dark.png')" class="lazy" alt="" height="22"></span>
                                </a>
                                <p class="text-muted mb-4 mt-3">Masukkan alamat email dan kata sandi Anda untuk mengakses panel admin.</p>
                            </div>

                            <form action="javascript:void(0)" method="POST" id="login-form">
                                @csrf
                                <div class="form-group mb-3">
                                    <label for="username">Nama pengguna</label>
                                    <input class="form-control" type="text" name="username" id="username" placeholder="Masukkan nama pengguna Anda" required="" autofocus>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="password">Kata Sandi</label>
                                    <input class="form-control" type="password" name="password" id="password" placeholder="Masukkan kata sandi Anda" required="">
                                </div>

                                <div class="form-group mb-3">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" name="remember" id="remember-me" checked>
                                        <label class="custom-control-label" for="checkbox-signin">Ingat saya</label>
                                    </div>
                                </div>

                                <div class="form-group mb-0 text-center">
                                    <button class="btn btn-primary btn-block" type="submit" id="login-btn"> Masuk </button>
                                </div>

                            </form>

                        </div> <!-- end card-body -->
                    </div>
                    <!-- end card -->

                    <div class="row mt-3">
                        <div class="col-12 text-center">
                            <p> <a href="@route('password.forgot')" class="text-white-50 ml-1">Lupa Kata Sandi?</a></p>
                            <p class="text-white-50">Belum memiliki akun? <a href="@route('register')" class="text-white ml-1"><b>Daftar</b></a></p>
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

            $('#login-form').on('submit', function(e) {
                e.preventDefault();

                if($('#username').val().length == 0 || $('#password').val().length == 0){
                    notification('warning', 'Harap isi semua kolom.');
                    return false;
                }

                login();
            });
        });

        function login()
        {
            var formData = $('#login-form').serialize();

            $.ajax({
                url: "@route('login')",
                type: "POST",
                dataType: "json",
                data: formData,
                beforeSend() {
                    $('#login-btn').html('<span class="spinner-border spinner-border-sm mr-1" role="status" aria-hidden="true"></span> Loading...');
                    $('button').attr('disabled', 'disabled');
                    $('input').attr('disabled', 'disabled');
                },
                complete() {
                    $('#login-btn').html('Masuk');
                    $('button').removeAttr('disabled', 'disabled');
                    $('input').removeAttr('disabled', 'disabled');
                },
                success : function (result) {
                    notification(result['status'], result['message']);

                    setTimeout(() => {
                        $('#username').focus();
                    }, 50);

                    if(result['status'] == 'success'){
                        window.location = url + '/' +result['login_destination'];
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
    </script>
@endsection
