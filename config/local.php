<?php

$config->conexion = [
    "driver" => "mysql",
    "host" => "127.0.0.1",
    "port" => 3306,
    "database" => "proyecto_daniela",
    "username" => "root",
    "password" => "",
    'trust_server_certificate' => true
];

$config->base_url = 'http://localhost/proyecto_daniela/v1';

$config->session_security_code = '%UTmvHT,RdpRf7>y9tadsafsd@@peN[@/7=5=ke>';
$config->search_secret_key = '%UTmvHT,RdpRf7>@dfsfa2@y9tapeN[@/7=5=ke>';
$config->search_secret_iv = '%UTmvHT,RdpRf7>y9tapeasdasfsa@_saN[@/7=5=ke>';

$config->session_expire_time = 5*60*60; // 5horas

//html tags
$config->html_locale = 'es_ES';
$config->html_type = 'article';
$config->html_title = 'Nombre proyecto';
$config->html_url = $config->base_url;
$config->html_site_name = 'Nombre proyecto';
$config->html_canonical = $config->base_url . $_SERVER['REQUEST_URI'];
$config->html_shortcuticon = $config->base_url . '/favicon.ico';
$config->html_description = '';
$config->html_keywords = '';

