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

 $router->get('/op/list', 'OperacionesController@getIndex');
 $router->get('/op/list/{id}','OperacionesController@getRead');
 $router->post('/op/delete/{id}', 'OperacionesController@postDelete');


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

$router->get('/', 'HomeController@getIndex');

/**
 *  Usuarios
 */

$router->get('/create','UsuariosController@getCreate');
$router->post('/create','UsuariosController@postCreate');
$router->get('/list', 'UsuariosController@getIndex');
$router->get('/delete/{id}', 'UsuariosController@getDelete');
$router->post('/delete/{id}', 'UsuariosController@postDelete');
$router->get('/update/{id}', 'UsuariosController@getUpdate');
$router->post('/update/{id}', 'UsuariosController@postUpdate');

