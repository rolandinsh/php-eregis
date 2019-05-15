<?php

use Republa\EregShim\Eregis as eregis;

if (!function_exists('ereg')) {
    function ereg($pattern, $string, &$regs = null)
    {
        return func_num_args() === 2
            ? eregis::ereg($pattern, $string)
            : eregis::ereg($pattern, $string, $regs);
    }
}

if (!function_exists('eregi')) {
    function eregi($pattern, $string, &$regs = null)
    {
        return func_num_args() === 2
            ? eregis::eregi($pattern, $string)
            : eregis::eregi($pattern, $string, $regs);
    }
}

if (!function_exists('ereg_replace')) {
    function ereg_replace($pattern , $replacement , $string)
    {
        return eregis::ereg_replace($pattern , $replacement , $string);
    }
}

if (!function_exists('eregi_replace')) {
    function eregi_replace($pattern , $replacement , $string)
    {
        return eregis::eregi_replace($pattern , $replacement , $string);
    }
}

if (!function_exists('split')) {
    function split($pattern, $string, $limit = -1)
    {
        return eregis::split($pattern, $string, $limit);
    }
}

if (!function_exists('spliti')) {
    function spliti($pattern, $string, $limit = -1)
    {
        return eregis::spliti($pattern, $string, $limit);
    }
}
