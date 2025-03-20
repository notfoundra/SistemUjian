<?php

if (!function_exists('set_active')) {
    function set_active($routeName)
    {
        $currentPath = service('uri')->getSegment(2);
        return $currentPath === $routeName ? 'active' : '';
    }
}
