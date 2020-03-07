<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    <head>
        @include('templates.admin.ubold._partials._head')
        @include('templates.admin.ubold._partials._styles')
    </head>
    <body>
        <!-- Begin page -->
        <div id="wrapper">
            @include('templates.admin.ubold._partials._topbar')
            @include('templates.admin.ubold._partials._sidebar')
            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            <div class="content-page">
                <!-- Start content -->
                <div class="content">
                    <!-- Pre-loader -->
                    <div id="preloader">
                        <div id="status">
                            <div class="spinner">Loading...</div>
                        </div>
                    </div>
                    <!-- End Pre-loader -->

                    @yield('content')
                </div> <!-- content -->
                @include('templates.admin.ubold._partials._footer')
            </div>
            <!-- ============================================================== -->
            <!-- End Right content here -->
            <!-- ============================================================== -->
        </div>
        <!-- END wrapper -->
        @include('templates.admin.ubold._partials._scripts')
    </body>
</html>
