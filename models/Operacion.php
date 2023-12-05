<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class Operacion extends Eloquent 
{
	protected $table = 'operaciones';
	protected $primaryKey = 'id';

	
	public function __construct ()
	{
		//
	}

	public function imagenes ()
	{
		return $this->hasMany('Imagen');
	}

}