@extends('templates.admin.ubold.auth')

@section('title', 'Setel Ulang Kata Sandi')

@section('body-class', 'authentication-bg-pattern')

@section('content')
    <div class="account-pages mt-5 mb-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card bg-pattern">

                        <div class="card-body p-4">

                            <div class="text-center w-75 m-auto">
                                <a href="@route('password.reset')">
                                    <span><img src="@asset('images/placeholder.jpg')" data-original="@asset('templates/admin/ubold/assets/images/logo-dark.png')" class="lazy" alt="" height="22"></span>
                                </a>
                                <p class="text-muted mb-4 mt-3">Buat Kata Sandi baru Anda. Saya harap Anda tidak lupa dengan Kata Sandi Anda</p>
                            </div>

                            <form action="javascript:void(0)" method="POST" id="reset-password-form">
                                @csrf
                                <input type="hidden" name="token" value="{{ $token }}">

                                <div class="form-group mb-3">
                                    <label for="email">Email</label>
                                    <input class="form-control" type="email" name="email" id="email" placeholder="Masukkan email Anda" value="{{ $email }}" readonly required>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="password">Kata Sandi</label>
                                    <input class="form-control" type="password" name="password" id="password" placeholder="Masukkan Kata Sandi Baru" required autofocus>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="confirmation-password">Konfirmasi Kata Sandi</label>
                                    <input class="form-control" type="password" name="password_confirmation" id="confirmation-password" placeholder="Masukkan Konfirmasi Kata Sandi Baru" required>
                                </div>

                                <div class="form-group mb-0 text-center">
                                    <button class="btn btn-primary btn-block" type="submit" id="reset-password-btn"> Simpan Kata Sandi </button>
                                </div>

                            </form>

                        </div> <!-- end card-body -->
                    </div>
                    <!-- end card -->

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

            $('#reset-password-form').on('submit', function(e) {
                e.preventDefault();

                if($('#email').val().length == 0 ||
                $('#password').val().length == 0 ||
                $('#confirmation-password').val().length == 0){
                    notification('warning', 'Harap isi semua kolom.');
                    return false;
                }

                if($("#password").val() != $("#confirmation-password").val()){
                    notification('warning', 'Kata sandi yang Anda masukkan tidak sama.');
                    return false;
                }

                resetPassword();
            });
        });

        function resetPassword()
        {
            var formData = $('#reset-password-form').serialize();

            $.ajax({
                url: "@route('password.reset')",
                type: "POST",
                dataType: "json",
                data: formData,
                beforeSend() {
                    $('#reset-password-btn').html('<span class="spinner-border spinner-border-sm mr-1" role="status" aria-hidden="true"></span> Loading...');
                    $('button').attr('disabled', 'disabled');
                    $('input').attr('disabled', 'disabled');
                },
                complete() {
                    $('#reset-password-btn').html('Simpan Kata Sandi');
                    $('button').removeAttr('disabled', 'disabled');
                    $('input').removeAttr('disabled', 'disabled');
                },
                success : function (result) {
                    notification(result['status'], result['message']);

                    setTimeout(() => {
                        $('#password').focus();
                    }, 50);

                    if(result['status'] == 'success'){
                        $('#email').val('');
                        $('#password').val('');
                        $('#confirmation-password').val('');
                        window.location = "@route('login')";
                    }
                },
                error : function(xhr, status, error) {
                    var err = eval('(' + xhr.responseText + ')');
                    notification(status, err.message);
                    checkCSRFToken(err.message);

                    setTimeout(() => {
                        $('#password').focus();
                    }, 50);
                }
            })
        }
    </script>
@endsection
