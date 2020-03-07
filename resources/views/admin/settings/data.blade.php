@foreach ($settingGroups as $key => $settingGroup)
    <div class="col-xl-4">
        <div class="card-box project-box">
            <div class="dropdown float-right">
                <a href="javascript:void(0);" class="dropdown-toggle card-drop arrow-none" data-toggle="dropdown" aria-expanded="false">
                    <i class="mdi mdi-dots-horizontal m-0 text-muted h3"></i>
                </a>
                @if (auth()->user()->can('setting_group.update') || auth()->user()->can('setting_group.delete'))
                    <div class="dropdown-menu dropdown-menu-right">
                        @can('setting_group.update')
                            <a class="dropdown-item" href="javascript:void(0);" data-id="{{ $settingGroup->id }}" onclick="getUpdateData(this)">Edit</a>
                        @endcan
                        @can('setting_group.delete')
                            <a class="dropdown-item" href="javascript:void(0);" data-id="{{ $settingGroup->id }}" onclick="deleteSettingGroup(this)">Hapus</a>
                        @endcan
                    </div>
                @endif
            </div> <!-- end dropdown -->
            <!-- Title-->
            <h4 class="mt-0"><a href="@route('admin.settings.index', ['setting_group' => $settingGroup])" class="text-dark">{{ $settingGroup->name }}</a></h4>
            <!-- Desc-->
            <p class="text-muted font-13 mb-3 sp-line-2">
                {{ $settingGroup->description }}
            </p>
            <a href="@route('admin.settings.index', ['setting_group' => $settingGroup])" class="mb-2 font-weight-bold">Ubah Pengaturan <i class="mdi mdi-chevron-double-right"></i></a>
        </div> <!-- end card box-->
    </div><!-- end col-->
@endforeach
