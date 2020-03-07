<!-- Topbar Start -->
<div class="navbar-custom">
    <ul class="list-unstyled topnav-menu float-right mb-0">

        <li class="dropdown notification-list">
            <a class="nav-link dropdown-toggle nav-user mr-0 waves-effect waves-light" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                <img src="@asset('images/placeholder.jpg')" data-original="{{ auth()->user()->myPhoto() }}" alt="user-image" class="lazy rounded-circle">
                <span class="pro-user-name ml-1">
                    {{ auth()->user()->name ?? auth()->user()->username }} <i class="mdi mdi-chevron-down"></i>
                </span>
            </a>

            <div class="dropdown-menu dropdown-menu-right profile-dropdown">

                <!-- item-->
                <div class="dropdown-header noti-title">
                    <h6 class="text-overflow m-0">Selamat Datang!</h6>
                </div>

                <!-- item-->
                <a href="@route('admin.account.index')" class="dropdown-item notify-item">
                    <i class="fe-user"></i>
                    <span>Akun Saya</span>
                </a>

                <!-- item-->
                <a href="@route('admin.settingGroups.index')" class="dropdown-item notify-item">
                    <i class="fe-settings"></i>
                    <span>Pengaturan</span>
                </a>

                <div class="dropdown-divider"></div>

                <!-- item-->
                <a href="javascript:void(0);" class="dropdown-item notify-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fe-log-out"></i>
                    <span>Keluar</span>
                </a>
                <form id="logout-form" action="@route('logout')" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </li>

    </ul>

    <!-- LOGO -->
    <div class="logo-box">
        <a href="@route('admin.dashboard')" class="logo text-center">
            <span class="logo-lg">
                <img src="@asset('images/placeholder.jpg')" data-original="@asset('templates/admin/ubold/assets/images/logo-light.png')" alt="" height="18" class="lazy">
                <!-- <span class="logo-lg-text-light">UBold</span> -->
            </span>
            <span class="logo-sm">
                <!-- <span class="logo-sm-text-dark">U</span> -->
                <img src="@asset('images/placeholder.jpg')" data-original="@asset('templates/admin/ubold/assets/images/logo-sm.png')" alt="" height="24" class="lazy">
            </span>
        </a>
    </div>

    <ul class="list-unstyled topnav-menu topnav-menu-left m-0">
        <li>
            <button class="button-menu-mobile waves-effect waves-light">
                <i class="fe-menu"></i>
            </button>
        </li>
    </ul>
</div>
<!-- end Topbar -->
