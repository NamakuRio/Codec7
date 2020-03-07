<?php

if (!function_exists('checkPermission')) {

    /**
     * description
     *
     * @param
     * @return
     */
    function checkPermission($permission)
    {
        return (bool) (!auth()->user()->can($permission));
    }
}
