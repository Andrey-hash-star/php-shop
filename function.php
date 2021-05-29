<?php

function show_img($number_img)
{

    $dir = 'D:/Open_Server/OpenServer/domains/shop/img';

    $files = scandir($dir);

    foreach ($files as $value) {

        if ($value == $number_img) {
            return $value;
        }
    }
}

function print_array(array $array)
{
    echo '<pre>';
    print_r($array);
    echo '</pre>';
}

function dumper($obj)
{
    echo
        "<pre>",
        htmlspecialchars(dumperGet($obj)),
        "</pre>";
}

function dumperGet($obj, $leftSp = "")
{
    if (is_array($obj)) {
        $type = "Array[" . count($obj) . "]";
    } elseif (is_object($obj)) {
        $type = "Object";
    } elseif (gettype($obj) == "boolean") {
        return $obj ? "true" : "false";
    } else {
        return "\"$obj\"";
    }
    $buf = $type;
    $leftSp .= "  ";
    for (Reset($obj); list($k, $v) = each($obj);) {
        if ($k === "GLOBALS") continue;
        $buf .= "\n$leftSp$k => " . dumperGet($v, $leftSp);
    }
    return $buf;
}
