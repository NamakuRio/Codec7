<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    <head>
        @include('templates.admin.ubold._partials._head')
        @include('templates.admin.ubold._partials._styles')
    </head>
    <body class="authentication-bg @yield('body-class')">
        @yield('content')

        @include('templates.admin.ubold._partials._scripts')
    </body>
</html>
