<?php

use DeviceDetector\DeviceDetector;
use DeviceDetector\Parser\Device\AbstractDeviceParser;

class LoginController extends BaseController
{
    private $config;
    private $srv;
	private $req_id;

	public function __construct ()
	{
		global $config;
        global $request_id;
		$this->config = $config;
		$this->req_id = $request_id;
		$this->srv = new LoginService();
	}

	public function getIndex ()
	{
		$view = [
			'url_form_post' => $this->config->base_url . '/login'
		];

		$this->loadView('login.index', $view);
	}

	public function postIndex ()
	{
		$this->sanitizeRequestData();
		$usuario = Usuario::verificacionUsuario($this->request->email, $this->request->password);
		if($usuario)
			$this->redirect($this->config->base_url,['bienvenid@ ' . $usuario->nombre, 'success','']);
		else
			$this->redirect($this->config->base_url,['Contraseña no válida ', 'danger','']);

	}

	public function getRecuperarPass ()
	{
		$view = [
			'url_form_post' => $this->config->base_url . '/recuperar-pass'
		];
		$this->loadView('login.recuperar_pass', $view);
	}

	public function postRecuperarPass ()
	{
		die('recuperar_pass');
	}

	public function getLogout ()
    {
    	session_destroy();
		$this->redirect('login',['Sesión cerrada con éxito','success','']);
    }
}