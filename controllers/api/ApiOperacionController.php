<?php

use Ramsey\Uuid\Uuid;

class ApiOperacionController extends BaseController 
{
	private $config;

	public function __construct ()
	{
		global $config;
		$this->config = $config;
	}

    //recibe json con apikey y devuelve url + uuid para realizar la operación y después obtener los datos
    public function postCrearOperacion ()
	{
		$this->jsonData();

		//comprobar apikey es válid a y pertenece a un usuario | si intenta varias veces y falla bloquear ip
		$usuario = Usuario::where('apikey','=',$this->json_data->apiKey)->first();
		if(!$usuario)
			Response::apiResponse(['code' => 400, 'msg' => 'Apikey no válida']);
		
		//crear operación en estado inicial
		$op = new Operacion();
		$op->estado = 'auth-init';
		$op->uuid = rand(0,200) . Uuid::uuid4()->toString() .time();
		$op->usuario_id = $usuario->id;
		$op->redirect_url = $this->json_data->redirect_url;
		$op->save();

		//codificar uuid
		$uuid_enc = Search::encrypt_decrypt('encrypt',$op->uuid);

		$return = [];
		$return['url'] = $this->config->base_url . '/app/' . $uuid_enc;
		$return['uuid'] = $op->uuid;

		Response::apiResponse($return);
	}

    //recibe json con apikey y uuid, devuelve datos de la operación
    public function postInfo ()
	{
		$this->jsonData();

		//comprueba si existe el uuid y la operación, si existe devolvemos datos, bloquear ip si varios intentos erróneos
		$usuario = Usuario::where('apikey','=',$this->json_data->apiKey)->first();
		if(!$usuario)
			Response::apiResponse(['code' => 400, 'msg' => 'Apikey no válida']);

		
		$op = Operacion::where('uuid','=',$this->json_data->uuid)->where('usuario_id','=',$usuario->id)->first();
		if(!$op)
			Response::apiResponse(['code' => 400, 'msg' => 'Operación no encontrada']);

		Response::apiResponse(['code' => 200, $op]);
	}

}