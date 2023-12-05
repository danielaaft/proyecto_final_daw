<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class Log extends Eloquent 
{
	protected $table = 'logs';
	protected $primaryKey = 'id';
	//public $incrementing = true;
	//protected $dateFormat = 'U';
	//public $timestamps = true;

	public function session_info()
    {
        return $this->belongsTo("SessionInfo");
    }
}