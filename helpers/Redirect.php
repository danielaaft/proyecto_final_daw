<?php

class Redirect 
{
    public static function redirect ($url, $alert = false)
	{
		global $config;
		if(is_array($alert)) BaseController::setAlert($alert[0], $alert[1], $alert[2]);
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
}