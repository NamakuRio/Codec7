<?php

Route::name('admin.')->group(function () {
    Route::get('/', 'DashboardController@index')->name('dashboard');

    Route::name('account.')->prefix('account')->group(function () {
        Route::get('/', 'AccountController@index')->name('index');

        Route::middleware('ajax')->group(function () {
            Route::put('/', 'AccountController@update')->name('update');
        });
    });

    Route::name('users.')->prefix('users')->group(function () {
        Route::get('/', 'UserController@index')->name('index');

        Route::middleware('ajax')->group(function () {
            // CRUD
            Route::post('/', 'UserController@store')->name('store');
            Route::post('/show', 'UserController@show')->name('show');
            Route::put('/', 'UserController@update')->name('update');
            Route::delete('/', 'UserController@destroy')->name('destroy');

            // manage user
            Route::post('/manage', 'UserController@showManage')->name('show.manage');
            Route::put('/manage', 'UserController@manage')->name('manage');

            // server-side datatable
            Route::post('/get-users', 'UserController@getUsers')->name('getUsers');

            // check available username
            Route::post('/check-username', 'UserController@checkUsername')->name('checkUsername');

            // status & verification user
            Route::put('/status', 'UserController@updateStatus')->name('update.status');
            Route::put('/verification', 'UserController@updateVerification')->name('update.verification');
        });
    });

    Route::name('roles.')->prefix('roles')->group(function () {
        Route::get('/', 'RoleController@index')->name('index');

        Route::middleware('ajax')->group(function () {
            // CRUD
            Route::post('/', 'RoleController@store')->name('store');
            Route::post('/show', 'RoleController@show')->name('show');
            Route::put('/', 'RoleController@update')->name('update');
            Route::delete('/', 'RoleController@destroy')->name('destroy');

            // manage role
            Route::post('/manage', 'RoleController@showManage')->name('show.manage');
            Route::put('/manage', 'RoleController@manage')->name('manage');

            // set default
            Route::put('/set-default', 'RoleController@setDefault')->name('setDefault');

            // server-side datatable
            Route::post('/get-roles', 'RoleController@getRoles')->name('getRoles');

            // select2
            Route::post('/select2', 'RoleController@select2')->name('select2');
        });
    });

    Route::name('permissions.')->prefix('permissions')->group(function () {
        Route::get('/', 'PermissionController@index')->name('index');

        Route::middleware('ajax')->group(function () {
            // CRUD
            Route::post('/', 'PermissionController@store')->name('store');
            Route::post('/show', 'PermissionController@show')->name('show');
            Route::put('/', 'PermissionController@update')->name('update');
            Route::delete('/', 'PermissionController@destroy')->name('destroy');

            // server-side datatable
            Route::post('/get-permissions', 'PermissionController@getPermissions')->name('getPermissions');
        });
    });

    Route::prefix('settings')->group(function () {
        Route::name('settingGroups.')->group(function () {
            Route::get('/', 'SettingGroupController@index')->name('index');

            Route::middleware('ajax')->group(function () {
                // CRUD
                Route::post('/', 'SettingGroupController@store')->name('store');
                Route::post('/show', 'SettingGroupController@show')->name('show');
                Route::put('/', 'SettingGroupController@update')->name('update');
                Route::delete('/', 'SettingGroupController@destroy')->name('destroy');

                // server-side
                Route::post('/get-setting-groups', 'SettingGroupController@getSettingGroups')->name('getSettingGroups');

                // check available slug
                Route::post('/check-slug', 'SettingGroupController@checkSlug')->name('checkSlug');
            });
        });

        Route::name('settings.')->group(function () {
            Route::get('/{setting_group}', 'SettingController@index')->name('index');
            Route::get('/{setting_group}/manage', 'SettingController@manage')->name('manage');

            Route::middleware('ajax')->group(function () {
                // check available name
                Route::post('/check-name', 'SettingController@checkName')->name('checkName');

                // CRUD
                Route::post('/{setting_group}', 'SettingController@store')->name('store');
                Route::post('/{setting_group}/show', 'SettingController@show')->name('show');
                Route::put('/{setting_group}', 'SettingController@update')->name('update');
                Route::delete('/{setting_group}', 'SettingController@destroy')->name('destroy');

                // server-side
                Route::post('/{setting_group}/get-settings', 'SettingController@getSettings')->name('getSettings');

                // save setting
                Route::put('/{setting_group}/save', 'SettingController@save')->name('save');

                // reset setting
                Route::put('/{setting_group}/reset', 'SettingController@reset')->name('reset');
            });
        });
    });
});
