<?php

Use eftec\bladeone\BladeOne;

class BaseController
{
	private $method;
	public $request;
	public $json_data;
	public $blade;

	public function __construct ()
	{		

	}

	public function handleRequestData ()
	{
		foreach($_REQUEST as $key => $value)
		{
			$_SESSION['input_old'][$key] = $value;
		}
	}

	public function errors2alert ($errores)
	{
		$string_errores = '';
		foreach($errores as $error)
		{
			foreach($error as $key => $value)
			{
				$string_errores .= $value.'<br/>';
			}
		}
		return substr($string_errores, 0, -5);
	}
	
	public function getViewContent($view, $vars) {
        header("Content-Type: text/html; charset=utf-8");
        global $config;
        $vars['title'] = @$config->title;
        $vars['description'] = @$config->description;
        $vars['url'] = $config->base_url;
        $vars['url_admin'] = $config->admin_url;
        $vars['session'] = $_SESSION;

        $this->blade = new BladeOne($config->base_path . '/views', $config->base_path . '/cache', ($config->entorno == 'produccion') ? BladeOne::MODE_AUTO : BladeOne::MODE_DEBUG);
        return $this->blade->run($view, $vars);
    }

	public function loadView ($view, $vars, $pdf = false)
	{
		global $config;
		$vars['url'] = $config->base_url;

        //configuración plantilla
		$vars['html_locale'] = $config->html_locale;
        $vars['html_type'] = $config->html_type;
        $vars['html_title'] = $config->html_title;
        $vars['html_url'] = $config->html_url;
        //$vars['html_sitemap'] = $config->html_sitemap;
        $vars['html_locale'] = $config->html_locale;
        $vars['html_canonical'] = $config->html_canonical;
        $vars['html_shortcuticon'] = $config->html_shortcuticon;
        $vars['html_keywords'] = $config->html_keywords;

		$this->blade = new BladeOne($config->base_path . '/views', $config->base_path . '/cache', ($config->entorno == 'produccion') ? BladeOne::MODE_AUTO : BladeOne::MODE_DEBUG);

		global $request_id;
		Security::generateResponseLog($request_id, 'HTML loaded.'); 

		if(!$pdf) echo $this->blade->run($view, $vars); 
		else return $this->blade->run($view, $vars);
		unset($_SESSION['input_old']);
		if(!$pdf) die();
	}

	public function jsonData ()
	{
		$this->json_data = json_decode(file_get_contents("php://input"));
	}

	public function sanitizeRequestData()
	{
		$this->handleRequestData();
		$this->request = new stdClass();
		$i = 0;
		foreach ($_REQUEST as $key => $value) {
			$this->request->$key = strip_tags($value);
		}
	}

	public function baseUrl()
	{
		global $config;

		return $config->base_url;
	}

	/*
	*	Helper functions
	*/
	public function apiResponse($data)
	{
		if(isset($data['code'])) http_response_code($data['code']);
		header('Content-Type: application/json');
		echo json_encode($data);
		die();
	}

	public function curl($url, $data, $headers, $method = 'POST', $auth_basic = false, $timeout = 30, $status = false)
	{
		global $config;
/*
		if (is_array($headers) && isset($this->request->jwt) && !empty($this->request->jwt)) {
		    $bearer = null;
		    foreach ($headers as $header) {
		        if (stripos($header, 'Authorization:Bearer') !== false) {
		            $bearer = $header;
                }
            }
            if ($bearer !== null) {
                $this->logData($this->request->jwt, 'Next Request Bearer', $header);
            }
        }
*/

		$ch = curl_init();
		//curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout); 
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, $timeout * 1000);
		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);

		curl_setopt($ch, CURLOPT_URL, $url);
		if ($method == 'POST') 
		{
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		}
		if ($method == 'GET') curl_setopt($ch, CURLOPT_RETURNTRANSFER , 1);
		
		// Receive server response ...

		@curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		if ($auth_basic) curl_setopt($ch, CURLOPT_USERPWD, $auth_basic['username'] . ":" . $auth_basic['password']);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0); 
		curl_setopt($ch, CURLOPT_TIMEOUT, 30000); //timeout in seconds
		$output = curl_exec($ch);
		$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		curl_close($ch);

		if($status)
		{
			return ['output' => $output, 'status' => $http_status];
		}
		else
			return $output;
	}

	public function logData ($jwt, $titulo, $datos)
	{
		global $config;
		$data = PHP_EOL . '--------------' . PHP_EOL . $titulo . ' ('.date('d-m-Y H:i:s').') :' . PHP_EOL;
		$data .= $datos . PHP_EOL . '--------------';

		file_put_contents($config->base_path . '/jazztel/logs/'. ((strpos($jwt, 'error_') !== false) ? 'error_'.substr($jwt, -70) : substr($jwt, -70)). '.txt', $data, FILE_APPEND);

		return true;
	}

	public function redirect ($url, $alert = false)
	{
		global $config;
		if(is_array($alert)) self::setAlert($alert[0], $alert[1], $alert[2]);
		if (strpos($url, 'http') !== false || strpos($url, 'https') !== false)
		{
			//redirección sin añadir nada
		}
		else
		{
			$url = $config->base_url . '/' . $url;
		}

		header('Location: '.$url);
		die();
	}

	public static function setAlert ($texto, $tipo, $titulo)
	{
		if(isset($_SESSION['alert']) && is_array($_SESSION['alert']))
		{
			$i = @count($_SESSION['alert']);
		}
		else
		{
			$i = 0;
		}
		
		$_SESSION['alert'][$i]['text'] = $texto;
		$_SESSION['alert'][$i]['type'] = $tipo; //succcess, warning, danger, primary, info, secondary
		$_SESSION['alert'][$i]['title'] = $titulo;
		return true;
	}

	public function analizarRespuestaApi ($res)
	{
		if($res == '')
		{
			return $this->apiResponse(['error' => 1, 'msg' => 'Error en CRM', 'errorCode' => '500']);
		}
		if(isset($res->errorCode))
		{
			return $this->apiResponse(['error' => 1, 'msg' => $res->msg, 'errorCode' => $res->errorCode]);
		}
		return true;
	}

	public static function debugTextArea ($data)
	{
		echo "<hr>";
		echo "<textarea>";
		print_r($data);
		echo "</textarea>";
		echo "<hr>";
		//die();
	}

	public function jsonValido($string) {
		 json_decode($string);
		 return (json_last_error() == JSON_ERROR_NONE);
	}

}
