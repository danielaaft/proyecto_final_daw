<?php

use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;
$capsule->addConnection($config->conexion);
$capsule->setAsGlobal();
$capsule->bootEloquent();
$link = $capsule->getConnection()->getPdo();

//$link = mysqli_connect($config->bd_sesiones['host'], $config->bd_sesiones['username'], $config->bd_sesiones['password'], $config->bd_sesiones['database']);
//$session = new Zebra_Session($link, $config->session_security_code);

//sesiones en servidor
session_start();