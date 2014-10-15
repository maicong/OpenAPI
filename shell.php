<?php

function trim_backslash(&$code)
{
    $rs = strpos($code, '\\') !== false;
    $code = preg_replace('/\\\\./', '', $code);
    return $rs;
}

function trim_string(&$code) {
    $rs = strpos($code, '"') !== false || strpos($code, "'") !== false;
    $code = preg_replace('/"[^"]*?"|'."'[^']*?'".'/', 'X', $code);
    return $rs;
}
function trim_params(&$code) {
    $rs = strpos($code, ',') !== false;
    $code = preg_replace('/(\$?\w+|^[\d.e]+)(,\s*(\$?\w+|^[\d.e]+))+/', 'X', $code);
    return $rs;
}
function trim_function(&$code) {
    $changed = false;
    $code = preg_replace_callback('/(\w+)\s*\(\s*(\$?\w+|^[\d.e]+)\s*\)/', function ($m) use (&$changed) {
        if (in_array($m[1], array('if', 'while', 'do', 'for'))) {

        } else {
            $changed = true;
        }
    }, $code);
    return $changed;
}
function is_assign($code)
{
    return strpos($code, '=') !== false && strpos($code, '==') === false;
}

function formulize($code) {
    while (true) {
        $changed = false;
        $changed |= trim_backslash($code);
        $changed |= trim_string($code);
        $changed |= trim_params($code);
        $changed |= trim_function($code);
        // $changed |= trim_expression($code);
        if (!$changed) {
            break;
        }
    }
    return $code;
}
function is_expression($code)
{
    $code = formulize($code);
    return (count(explode(';', $code)) === 1 && !is_assign($code));
    return preg_match('/^\$?\w+$|^[\d.e]+$/i', $code);
}

function complete_expr($code) {
    $code = trim($code);
    if (empty($code)) {
        return array(null, null);
    }
    if (in_array($code, array('quit'))) {
        return array($code, null);
    }
    $last_char = $code[strlen($code)-1];
    if (is_expression($code)) {
        $code = '$__rs = '.$code;
    }
    if ($last_char !== ';' && $last_char !== '}') {
        $code .= ';';
    }
    // var_dump($code);
    return array(null, $code);
}

function execute_command($cmd)
{
    if ($cmd === 'quit') {
        exit();
    }
}

while (true) {
    echo "phpsh > ";
    $str = fread(STDIN,1000);
    if (empty($str)) {
        continue;
    }
    list($cmd, $code) = complete_expr($str);
    if ($cmd) {
        execute_command($cmd);
    } elseif ($code) {
        eval($code);
    }
    if (isset($__rs)) {
        var_dump($__rs);
        unset($__rs);
    }
}