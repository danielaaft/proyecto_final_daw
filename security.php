<?php

use Ramsey\Uuid\Uuid;
$data_log = strip_tags(file_get_contents("php://input"));

function get_string_between($string, $start, $end)
{
    $string = ' ' . $string;
    $ini = strpos($string, $start);
    if ($ini == 0) return '';
    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    $res = substr($string, $ini, $len);
    if(!$res && strpos($string, $start) !== false)
    {
    	$e = explode($start, $string);
    	return end($e);
    }
    return $res;
}

//datos comprometidos 
$remove = [];
$remove[] = get_string_between($data_log, 'contrasena=', '&');
$remove[] = get_string_between($data_log, 'password=', '&');
$remove[] = get_string_between($data_log, 'pass=', '&');
foreach($remove as $r)
{
	$data_log = str_replace($r,'--DELETED--', $data_log);
}
// /d

if(strlen($data_log) > 500)
{
	$data_log = substr($data_log, 0, 150) .'...'. substr($data_log, -150);
}
//Security::checkBlocked();
$request_id = str_replace('.','',microtime(true)) . '-' . rand(0,99999) . '-' .Uuid::uuid4()->toString();
Security::generateRequestLog($request_id, $data_log, false);