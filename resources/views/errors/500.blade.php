@extends('templates.admin.ubold.auth')

@section('body-class', 'authentication-bg-pattern')

@section('content')
    <div class="account-pages mt-5 mb-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card bg-pattern">

                        <div class="card-body p-4">

                            <div class="text-center">
                                <a href="index">
                                    <span><img src="@asset('templates/admin/ubold/assets/images/logo-dark.png')" alt="" height="18"></span>
                                </a>
                            </div>

                            <div class="text-center mt-4">
                                <h1 class="text-error">500</h1>
                                <h3 class="mt-3 mb-2">Kesalahan server dari dalam</h3>
                                <p class="text-muted mb-3">Mengapa tidak mencoba menyegarkan halaman Anda? atau Anda dapat menghubungi <a href="" class="text-dark"><b>Dukungan</b></a></p>
                            </div>

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
