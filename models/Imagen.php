<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class Imagen extends Eloquent 
{
	protected $table = 'imagenes';
	protected $primaryKey = 'id';

	
	public function __construct ()
	{
		//
	}

}