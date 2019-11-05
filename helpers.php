<?php

if (!function_exists('project_path')) {
    function project_path() {
        return __DIR__;
    }
}

if (!function_exists('file_name')) {
    function file_name($path, $extension = '.txt') {
        return preg_replace('/'.$extension.'/', '', trim($path));
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
        return preg_replace('/--/i', '-', strtolower(remove_none_supported_chars(replace_specials($value, '-'))));
    }
}

if (!function_exists('find_line_number_by_string')) {
    function find_line_number_by_string($content, $search)
    {
        if (!empty($search)) {
            $content_before_string = strstr($content, $search, true);

            if (false !== $content_before_string) {
                return count(explode(PHP_EOL, $content_before_string));
            }
        }
        return null;
    }
}

if (!function_exists('removeBOM')) {
    function removeBOM($str = "")
    {
        if (substr($str, 0, 3) == pack('CCC', 0xef, 0xbb, 0xbf)) {
            $str = substr($str, 3);
        }
        return $str;
    }
}
