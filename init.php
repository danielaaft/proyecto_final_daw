<?php

require "vendor/autoload.php";

require('config.php');

header("Strict-Transport-Security:max-age=63072000; includeSubDomains");
header("X-Frame-Options: SAMEORIGIN");
header("X-XSS-Protection: 1");
header( 'X-Content-Type-Options: nosniff' );

require('bootstrap.php');

//timezone
if(!isset($_SESSION['time_zone']))
	date_default_timezone_set('Europe/Madrid');
else
	date_default_timezone_set($_SESSION['time_zone']);

if($config->entorno != 'pro')
{
	ini_set('display_errors', '1');
	ini_set('display_startup_errors', '1');
	error_reporting(E_ALL);
}
else
{
	//ini_set('session.cookie_httponly', 1);
	//ini_set('session.use_only_cookies', 1);
	//ini_set('session.cookie_secure', 1);
	error_reporting(0);
	ini_set('display_errors', 0);
}

require_once('security.php');

//routes
$router = new \Bramus\Router\Router();
require_once('routes.php');
$router->run();