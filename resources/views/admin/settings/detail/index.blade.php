@extends('templates.admin.ubold.master')

@section('title', $settingGroup->name)

@section('content')
    <!-- Start Content-->
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="@route('admin.dashboard')">Admin</a></li>
                            <li class="breadcrumb-item"><a href="@route('admin.settingGroups.index')">Pengaturan</a></li>
                            <li class="breadcrumb-item active">{{ $settingGroup->name }}</li>
                        </ol>
                    </div>
                    <h4 class="page-title">{{ $settingGroup->name }}</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
                <div class="card-box">
                    <h4 class="header-title mb-4"><a href="@route('admin.settingGroups.index')" class="btn btn-xs btn-link waves-effect waves-dark"><i class="fa fa-arrow-left"></i></a> Lompat Ke</h4>

                    <div class="row">
                        <div class="col-12">
                            <div class="nav flex-column nav-pills nav-pills-tab" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                @foreach ($settingGroups as $key => $record)
                                    {!! ($record->id == $settingGroup->id ? '<a class="nav-link active show mb-2 waves-effect waves-light" href="'.route('admin.settings.index', ['setting_group' => $record]).'">'.$record->name.'</a>': '<a class="nav-link mb-2 waves-effect waves-blue" href="'.route('admin.settings.index', ['setting_group' => $record]).'">'.$record->name.'</a>') !!}
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- end col -->
            <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12">
                <div class="card-box">
                    @if(auth()->user()->can('setting.create') || auth()->user()->can('setting.update') || auth()->user()->can('setting.delete') || auth()->user()->can('setting.manage'))
                        <a href="@route('admin.settings.manage', ['setting_group' => $settingGroup])" class="btn btn-sm btn-blue waves-effect waves-light float-right">
                            <i class="mdi mdi-format-list-checks"></i> Kelola Pengaturan
                        </a>
                    @endif
                    <h4 class="header-title mb-4">{{ $settingGroup->name }}</h4>

                    <form method="POST" action="javascript:void(0);" enctype="multipart/form-data" id="form-save-setting">
                        @csrf
                        @method('PUT')
                        @foreach ($settingGroup->settings()->orderBy('order', 'ASC')->get() as $record)
                            <div class="form-group">
                                <label for="{{ 'settings-'.$settingGroup->name.'-'.$record->name }}">{{ ucwords(str_replace('_', ' ', $record->name)) }}</label>
                                @if ($record->type == 'text' || $record->type == 'email' || $record->type == 'number' || $record->type == 'file')
                                    <input type="{{ $record->type }}" name="{{ $record->name }}" class="form-control" id="{{ 'settings-'.$settingGroup->name.'-'.$record->name }}" placeholder="{{ 'Masukkan '.ucwords(str_replace('_', ' ', $record->name)) }}" value="{{ $record->value ?? $record->default_value }}" {{ ($record->required ? 'required' : '') }}>
                                @endif
                                @if ($record->type == 'textarea')
                                    <textarea name="{{ $record->name }}" id="{{ 'settings-'.$settingGroup->name.'-'.$record->name }}" class="form-control" cols="4" rows="4" placeholder="{{ 'Masukkan '.ucwords(str_replace('_', ' ', $record->name)) }}" {{ ($record->required ? 'required' : '') }}>{{ $record->value ?? $record->default_value }}</textarea>
                                @endif
                            </div>
                        @endforeach
                        <div class="mt-3">
                            <button type="submit" class="btn btn-success waves-effect waves-light" id="btn-save-setting">Simpan</button>
                            <button type="button" class="btn btn-danger waves-effect waves-light" id="btn-reset-setting">Atur Ulang</button>
                        </div>
                    </form>
                </div>
            </div><!-- end col -->
        </div>
        <!-- end row -->
    </div> <!-- container -->
@endsection

@section('script-bottom')
    <script>
        $(function () {
            "use strict";

            @can('setting.update')
                $("#form-save-setting").on("submit", function (e) {
                    e.preventDefault();

                    var formData = new FormData(this);
                    saveSetting(formData);
                });

                $("#btn-reset-setting").on("click", function () {
                    resetSetting();
                });
            @endcan
        });

        @can('setting.update')
            function saveSetting(formData)
            {
                $.ajax({
                    url: "@route('admin.settings.save', ['setting_group' => $settingGroup])",
                    type: "POST",
                    dataType: "json",
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    beforeSend() {
                        $("#btn-save-setting").html('<span class="spinner-border spinner-border-sm mr-1" role="status" aria-hidden="true"></span> Loading...');
                        $("#btn-save-setting").attr('disabled', 'disabled');
                        $("input").attr('disabled', 'disabled');
                        $("select").attr('disabled', 'disabled');
                        $("textarea").attr('disabled', 'disabled');
                        $("button").attr('disabled', 'disabled');
                    },
                    complete() {
                        $("#btn-save-setting").html('Simpan');
                        $("#btn-save-setting").removeAttr('disabled', 'disabled');
                        $("input").removeAttr('disabled', 'disabled');
                        $("select").removeAttr('disabled', 'disabled');
                        $("textarea").removeAttr('disabled', 'disabled');
                        $("button").removeAttr('disabled', 'disabled');
                    },
                    success : function(result) {
                        notification(result['status'], result['message']);
                    },
                    error : function(xhr, status, error) {
                        var err = eval('(' + xhr.responseText + ')');
                        notification(status, err.message);
                        checkCSRFToken(err.message);
                    }
                })
            }

            function resetSetting()
            {
                $.ajax({
                    url: "@route('admin.settings.reset', ['setting_group' => $settingGroup])",
                    type: "POST",
                    dataType: "json",
                    data: {
                        '_token': '{{ csrf_token() }}',
                        '_method': 'PUT',
                    },
                    beforeSend() {
                        $("#btn-reset-setting").html('<span class="spinner-border spinner-border-sm mr-1" role="status" aria-hidden="true"></span> Loading...');
                        $("#btn-reset-setting").attr('disabled', 'disabled');
                        $("input").attr('disabled', 'disabled');
                        $("select").attr('disabled', 'disabled');
                        $("textarea").attr('disabled', 'disabled');
                        $("button").attr('disabled', 'disabled');
                    },
                    complete() {
                        $("#btn-reset-setting").html('Atur Ulang');
                        $("#btn-reset-setting").removeAttr('disabled', 'disabled');
                        $("input").removeAttr('disabled', 'disabled');
                        $("select").removeAttr('disabled', 'disabled');
                        $("textarea").removeAttr('disabled', 'disabled');
                        $("button").removeAttr('disabled', 'disabled');
                    },
                    success : function(result) {
                        notification(result['status'], result['message']);
                    },
                    error : function(xhr, status, error) {
                        var err = eval('(' + xhr.responseText + ')');
                        notification(status, err.message);
                        checkCSRFToken(err.message);
                    }
                })
            }
        @endcan
    </script>
@endsection
