<?php

if (!function_exists('project_path')) {
    function project_path() {
        return __DIR__;
    }
}

if (!function_exists('file_name')) {
    function file_name($path, $extension = '.txt') {
        return preg_replace('/'.$extension.'/', '', preg_replace('/\s/i', '-', trim($path)));
    }
}

if (!function_exists('replace_specials')) {
    function replace_specials($value, $delimiter) {
        return preg_replace("/[\/\&%#\$\s]/i", $delimiter, trim($value));
    }
}

if (!function_exists('remove_none_supported_chars')) {
    function remove_none_supported_chars($value) {
        return preg_replace("/[\"\'\.\*]/i", '', $value);
    }
}

if (!function_exists('slug')) {
    function slug($value) {
        return remove_none_supported_chars(replace_specials($value, '-'));
    }
}
