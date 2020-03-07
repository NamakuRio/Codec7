<?php

use App\Models\Role;

if (!function_exists('defaultRole')) {

    /**
     * description
     *
     * @param
     * @return
     */
    function defaultRole()
    {
        $role = Role::where('default_user', 1);

        if($role->count() == 0){
            return 0;
        }

        return $role->first()->id;
    }
}
