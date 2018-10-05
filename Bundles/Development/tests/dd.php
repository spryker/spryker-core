<?php

if (!function_exists('dd')) {
    function dd($var)
    {
        dump($var);
        $backtrace = debug_backtrace(false, 1);
        $msg = 'dd-location: ' . $backtrace[0]['file'] . ':' . $backtrace[0]['line'];
        echo $msg;
        exit(1);
    }
}
