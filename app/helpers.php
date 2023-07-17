<?php

if (!function_exists('debug'))
{
    function debug()
    {

        echo '<pre>';
        print_r (func_get_args());
        echo '</pre>';
        die();

    }
}

//converts a stdclass object to an array form
if (!function_exists('convertToArray'))
{
    function convertToArray($std_object)
    {

        return json_decode(json_encode($std_object), true);

    }
}
