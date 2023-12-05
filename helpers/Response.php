<?php

class Response 
{

	public function __construct ()
	{

	}

	public static function apiResponse($data)
	{
		if(isset($data['code'])) http_response_code($data['code']);
		header('Content-Type: application/json');
		echo json_encode($data);
		die();
	}

}