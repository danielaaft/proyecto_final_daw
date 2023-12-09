<?php

$router = new \Bramus\Router\Router();
//	404
$router->set404(function () {
	global $request_id;
	Security::generateInvalidRouteAttemp($request_id);
    header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
    die();
});

// Middleware
$router->before('GET|POST', '/.*', function () 
{
	header('X-Powered-By: RGest');
});

/**
 * Operaciones
 */

 $router->get('/operaciones/{id}','OperacionesController@getRead');
 $router->get('/operaciones(/[A-Za-z0-9=_-]+)?(/\d+)?(/[a-z0-9_-]+)?(/[A-Za-z0-9_-]+)?(/\d+)?', 'OperacionesController@getIndex');

 $router->post('/operaciones/delete/{id}', 'OperacionesController@postDelete');

 $router->post('/operaciones', 'OperacionesController@postIndex');
//api
$router->post('/api/create', 'ApiOperacionController@postCrearOperacion');
$router->post('/api/info', 'ApiOperacionController@postInfo');

//app

$router->get('/app/rev/{uuid}', 'IdentificacionController@getReverso');
$router->post('/app/rev/{uuid}', 'IdentificacionController@postReverso');
$router->get('/app/anv/{uuid}', 'IdentificacionController@getAnverso');
$router->post('/app/anv/{uuid}', 'IdentificacionController@postAnverso');
$router->get('/app/pv/{uuid}', 'IdentificacionController@getPruebaVida');
$router->post('/app/pv/{uuid}','IdentificacionController@postPruebaVida');

$router->get('/app/{uuid}', 'IdentificacionController@getInit');
/*
*	Home
*/

$router->get('/prueba/{test}', 'HomeController@getPrueba');

/*
*	Login
*/
$router->get('/login', 'LoginController@getIndex');
$router->post('/login', 'LoginController@postIndex');
$router->get('/logout', 'LoginController@getLogout');



/**
 *  Usuarios
 */

$router->get('/usuarios/create','UsuariosController@getCreate');
$router->post('/usuarios/create','UsuariosController@postCreate');
$router->get('/usuarios', 'UsuariosController@getIndex');
$router->get('/usuarios/delete/{id}', 'UsuariosController@getDelete');
$router->post('/usuarios/delete/{id}', 'UsuariosController@postDelete');
$router->get('/usuarios/update/{id}', 'UsuariosController@getUpdate');
$router->post('/usuarios/update/{id}', 'UsuariosController@postUpdate');

$router->get('/demo/{uuid}', 'HomeController@getDemo');
$router->get('/', 'HomeController@getIndex');