<?php
function array_flatten($array)
{
    if (!is_array($array)) {
        return array($array);
    } else {
        return call_user_func_array("array_merge", array_map("array_flatten", $array));
    }
}
