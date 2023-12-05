<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class SessionInfo extends Eloquent 
{
	protected $table = 'session_info';
	protected $primaryKey = 'id';

	
	public function __construct ()
	{
		//
	}

	public function logs ()
	{
		$this->hasMany('Log');
	}
}