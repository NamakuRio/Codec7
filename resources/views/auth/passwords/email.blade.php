@extends('templates.admin.ubold.auth')

@section('title', 'Lupa Kata Sandi')

@section('body-class', 'authentication-bg-pattern')

@section('content')
    <div class="account-pages mt-5 mb-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card bg-pattern">

                        <div class="card-body p-4">

                            <div class="text-center w-75 m-auto">
                                <a href="@route('password.forgot')">
                                    <span><img src="@asset('images/placeholder.jpg')" data-original="@asset('templates/admin/ubold/assets/images/logo-dark.png')" class="lazy" alt="" height="22"></span>
                                </a>
                                <p class="text-muted mb-4 mt-3">Masukkan alamat email Anda dan kami akan mengirimi Anda email berisi instruksi untuk mereset kata sandi Anda.</p>
                            </div>

                            <form action="javascript:void(0)" method="POST" id="reset-password-form">
                                @csrf
                                <div class="form-group mb-3">
                                    <label for="email">Alamat Email</label>
                                    <input class="form-control" type="email" name="email" id="email" required="" placeholder="Masukkan email Anda" autofocus>
                                </div>

                                <div class="form-group mb-0 text-center">
                                    <button class="btn btn-primary btn-block" type="submit" id="reset-password-btn"> Setel Ulang Kata Sandi </button>
                                </div>

                            </form>

                        </div> <!-- end card-body -->
                    </div>
                    <!-- end card -->

                    <div class="row mt-3">
                        <div class="col-12 text-center">
                            <p class="text-white-50">Kembali <a href="@route('login')" class="text-white ml-1"><b>Masuk</b></a></p>
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

            $('#reset-password-form').on('submit', function(e) {
                e.preventDefault();

                if($('#email').val().length == 0){
                    notification('warning', 'Harap isi semua kolom.');
                    return false;
                }

                resetPassword();
            });
        });

        function resetPassword()
        {
            var formData = $('#reset-password-form').serialize();

            $.ajax({
                url: "@route('password.forgot')",
                type: "POST",
                dataType: "json",
                data: formData,
                beforeSend() {
                    $('#reset-password-btn').html('<span class="spinner-border spinner-border-sm mr-1" role="status" aria-hidden="true"></span> Loading...');
                    $('button').attr('disabled', 'disabled');
                    $('input').attr('disabled', 'disabled');
                },
                complete() {
                    $('#reset-password-btn').html('Setel Ulang Kata Sandi');
                    $('button').removeAttr('disabled', 'disabled');
                    $('input').removeAttr('disabled', 'disabled');
                },
                success : function (result) {
                    notification(result['status'], result['message']);

                    setTimeout(() => {
                        $('#email').focus();
                    }, 50);

                    if(result['status'] == 'success'){
                        $('#email').val('');
                    //     window.location = "@route('login')";
                    }
                },
                error : function(xhr, status, error) {
                    var err = eval('(' + xhr.responseText + ')');
                    notification(status, err.message);
                    checkCSRFToken(err.message);

                    setTimeout(() => {
                        $('#email').focus();
                    }, 50);
                }
            })
        }
    </script>
@endsection
