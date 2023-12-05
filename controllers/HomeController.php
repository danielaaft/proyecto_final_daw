<?php

class HomeController extends BaseController 
{
	private $config;

	public function __construct ()
	{
		global $config;
		$this->config = $config;

		//permisos
		//Usuario::checkLogedIn();
	}

    public function getIndex ()
	{
		Usuario::checkLogedIn();

		$view = [
			'usuario' => Usuario::find($_SESSION['usuario_id'])
		];
		
		$this->loadView('dashboard.index', $view);
	}

	public function getPrueba ($test)
	{
		var_dump($test);
		die('prueba');
	}

}