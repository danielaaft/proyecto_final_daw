<?php

use chillerlan\QRCode\QRCode;

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
		
        if(!isset($_SESSION['superadmin']) || $_SESSION['superadmin'] != 1)
        {
            $operaciones = Operacion::where('usuario_id','=',$_SESSION['usuario_id'])->get();
			$ultimas_ops = Operacion::where('usuario_id','=',$_SESSION['usuario_id'])->where('estado','!=','auth-init')->orderBy('id','DESC')->take(10)->get();
        }
		else
		{
			$operaciones = Operacion::get();
			$ultimas_ops = Operacion::where('estado','!=','auth-init')->orderBy('id','DESC')->take(10)->get();
		}
		$operacionesPorMes = array_fill(1, 12, 0); // Inicializa un array para contar operaciones por mes

		foreach ($operaciones as $operacion) {
			$mes = date('n', strtotime($operacion['created_at'])); // Extrae el mes de la fecha
			$operacionesPorMes[$mes]++;
		}

		//prueba
		$d = new Demo(Usuario::find($_SESSION['usuario_id'])->apikey,$this->config->base_url);
		$datos = $d->create();
		//generar qr
		$qrcode = new QRCode();
		$qr_src = $qrcode->render($datos->url);


		$view = [
			'usuario' => Usuario::find($_SESSION['usuario_id']),
			'operacionesPorMes' => $operacionesPorMes,
			'operaciones' => $ultimas_ops,
			'qr_src' => $qr_src,
			'url_demo' => $datos->url,
			'uuid' => $datos->uuid,
			'url_estado_demo' => $this->config->base_url . '/demo/' . $datos->uuid
		];
		
		$this->loadView('dashboard.index', $view);
	}

	public function getDemo ($uuid)
	{
		$d = new Demo(Usuario::find($_SESSION['usuario_id'])->apikey,$this->config->base_url);
		$res = $d->info($uuid);

		Response::apiResponse(json_decode($res, true));
	}

}