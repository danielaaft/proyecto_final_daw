<?php

class Security
{
	public function __construct (){

	}

	public static function getIp (){
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
		    $ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
		    $ip = $_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}

	public static function getUserData (){
		$info = new StdClass();
	    $info->remote_addr = @$_SERVER['REMOTE_ADDR'];
        $info->remote_port = @$_SERVER['REMOTE_PORT'];
        $info->client_ip = @$_SERVER['HTTP_CLIENT_IP'];
        $info->forwarded_for = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $info->browser = @$_SERVER['HTTP_USER_AGENT'];

        return $info;
	}

	public static function generateInvalidApiKeyAttemp ($req_id){
		$log = new Log();
		$log->type = 'InvalidApiKeyAttemp';
		$log->ip = self::getIp();
		$log->connection_data = json_encode(self::getUserData());
		$log->request_id = $req_id;
		$log->uri = $_SERVER['REQUEST_URI'];
		$log->project = 'front';
		$log->session_info_id = isset($_SESSION['session_info_id']) ? $_SESSION['session_info_id'] : null ;
		$log->save();
	}

	public static function generateInvalidLoginAttemp ($req_id){
		$log = new Log();
		$log->type = 'InvalidLoginAttemp';
		$log->ip = self::getIp();
		$log->connection_data = json_encode(self::getUserData());
		$log->request_id = $req_id;
		$log->uri = $_SERVER['REQUEST_URI'];
		$log->project = 'front';
		$log->session_info_id = isset($_SESSION['session_info_id']) ? $_SESSION['session_info_id'] : null ;
		$log->save();
	}

	public static function generateInvalidAuthAttemp ($req_id){
		$log = new Log();
		$log->type = 'InvalidAuthAttemp';
		$log->ip = self::getIp();
		$log->connection_data = json_encode(self::getUserData());
		$log->request_id = $req_id;
		$log->uri = $_SERVER['REQUEST_URI'];
		$log->project = 'front';
		$log->session_info_id = isset($_SESSION['session_info_id']) ? $_SESSION['session_info_id'] : null ;
		$log->save();
	}

	public static function generateInvalidRouteAttemp ($req_id){
		$log = new Log();
		$log->type = 'InvalidRouteAttemp';
		$log->ip = self::getIp();
		$log->connection_data = json_encode(self::getUserData());
		$log->request_id = $req_id;
		$log->uri = $_SERVER['REQUEST_URI'];
		$log->project = 'front';
		$log->session_info_id = isset($_SESSION['session_info_id']) ? $_SESSION['session_info_id'] : null ;
		$log->save();
	}

	public static function checkBlocked ()
	{
		//
		$check = Log::where(function ($q) {
			$q->where('type','=','InvalidApiKeyAttemp')
			->orWhere('type','=','InvalidLoginAttemp')
			->orWhere('type','=','InvalidAuthAttemp')
			->orWhere('type','=','InvalidRouteAttemp');
		})
		->where('ip','=',self::getIp())
		->where('created_at','>',date('Y-m-d H:i:s', strtotime('-24 hour'))) //en la 24 hora siguiente no puede acceder
		->orderBy('id','DESC')
		->count();


		if($check > 5) // 5 intentos erróneos =  bloqueo
		{
			header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
		   	//echo 'Ruta no encontrada';
		    die();
		}

		return true;
	}

	//---resto de logs

	public static function generateRequestLog ($req_id, $req_data, $token = false)
	{
		$log = new Log();
		$log->type = 'Request';
		$log->ip = self::getIp();
		$log->connection_data = json_encode(self::getUserData());
		$log->request_id = $req_id;
		$log->data = $req_data;
		if($token)
			$log->token = $token;
		$log->uri = $_SERVER['REQUEST_URI'];
		$log->project = 'front';
		$log->session_info_id = isset($_SESSION['session_info_id']) ? $_SESSION['session_info_id'] : null ;
		$log->save();
	}

	public static function generateResponseLog ($req_id, $response, $token = false)
	{
		$log = new Log();
		$log->type = 'Response';
		$log->ip = self::getIp();
		$log->connection_data = json_encode(self::getUserData());
		$log->request_id = $req_id;
		$log->response = $response;
		if($token)
			$log->token = $token;
		$log->uri = $_SERVER['REQUEST_URI'];
		$log->project = 'front';
		$log->session_info_id = isset($_SESSION['session_info_id']) ? $_SESSION['session_info_id'] : null ;
		$log->save();
	}

	public static function webhookRequestLog ($req_id, $req_data)
	{
		$log = new Log();
		$log->type = 'WebhookRequest';
		$log->ip = self::getIp();
		$log->connection_data = json_encode(self::getUserData());
		$log->request_id = $req_id;
		$log->data = $req_data;
		$log->uri = $_SERVER['REQUEST_URI'];
		$log->project = 'front';
		$log->session_info_id = isset($_SESSION['session_info_id']) ? $_SESSION['session_info_id'] : null ;
		$log->save();
	}

	public static function webhookResponseLog ($req_id, $response)
	{
		$log = new Log();
		$log->type = 'WebhookResponse';
		$log->ip = self::getIp();
		$log->connection_data = json_encode(self::getUserData());
		$log->request_id = $req_id;
		$log->response = $response;
		$log->uri = $_SERVER['REQUEST_URI'];
		$log->project = 'front';
		$log->session_info_id = isset($_SESSION['session_info_id']) ? $_SESSION['session_info_id'] : null ;
		$log->save();
	}

}