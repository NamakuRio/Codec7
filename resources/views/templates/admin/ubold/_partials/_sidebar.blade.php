<!-- ========== Left Sidebar Start ========== -->
<div class="left-side-menu">
    <div class="slimscroll-menu">
        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <ul class="metismenu" id="side-menu">
                <li class="menu-title">Menu</li>
                <li>
                    <a href="@route('admin.dashboard')">
                        <i class="fe-airplay"></i>
                        <span> Beranda </span>
                    </a>
                </li>
                <li class="menu-title">Lainnya</li>
                <li>
                    <a href="@route('admin.account.index')">
                        <i class="fe-user"></i>
                        <span> Akun </span>
                    </a>
                </li>
                @if(auth()->user()->can('user.view') || auth()->user()->can('role.view') || auth()->user()->can('permission.view'))
                    <li>
                        <a href="javascript: void(0);">
                            <i class="fe-users"></i>
                            <span> Kelola Pengguna </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul class="nav-second-level nav" aria-expanded="false">
                            @can('user.view')
                                <li>
                                    <a href="@route('admin.users.index')">
                                        <span> Pengguna </span>
                                    </a>
                                </li>
                            @endcan
                            @can('role.view')
                                <li>
                                    <a href="@route('admin.roles.index')">
                                        <span> Peran </span>
                                    </a>
                                </li>
                            @endcan
                            @can('permission.view')
                                <li>
                                    <a href="@route('admin.permissions.index')">
                                        <span> Izin </span>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endif
                @can('log.view')
                    <li>
                        <a href="@route('log-viewer::dashboard')" target="_blank">
                            <i class="fe-server"></i>
                            <span> Log </span>
                        </a>
                    </li>
                @endcan
                @can('setting_group.view')
                    <li>
                        <a href="@route('admin.settingGroups.index')">
                            <i class="fe-settings"></i>
                            <span> Pengaturan </span>
                        </a>
                    </li>
                @endcan
            </ul>
        </div>
        <!-- End Sidebar -->
        <div class="clearfix"></div>
    </div>
    <!-- Sidebar -left -->
</div>
<!-- Left Sidebar End -->
