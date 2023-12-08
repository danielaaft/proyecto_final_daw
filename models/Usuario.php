<?php

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes as SoftDeletes;

class Usuario extends Eloquent 
{
	protected $table = 'usuarios';
	protected $primaryKey = 'id';
	use SoftDeletes;    

	protected $fillable = [
		'password', 'email','name'
   	];

	protected $hidden = [
       'password'
   ];

    public function empresa ()
	{
		return $this->belongsTo('Empresa')->first();
	}
	
	public function __construct ()
	{
		parent::__construct();
	}

	public static function verificacionUsuario ($username, $password)
	{
        global $config;

		if((isset($username) && $username != '') && isset($password) && $password != '')
        {
            $usuario = self::where('email', $username)->first();

            if(!isset($usuario->password)) @Redirect::redirect('login',['Invalid credentials','danger','Error']);
            
            $check = password_verify ($password, $usuario->password); 


            if(!$check) return false;
            
        	$_SESSION['usuario_id'] = $usuario->id;
        	$_SESSION['email'] = $usuario->email;
        	$_SESSION['nombre'] = $usuario->name;
            $_SESSION['superadmin'] = $usuario->superadmin;

            return $usuario;
        }

        @Redirect::redirect('login',['Access data not valid','danger','Error']);
    }

	public static function checkLogedIn ()
	{
        global $config;

        if(!isset($_SESSION['usuario_id']) || $_SESSION['usuario_id'] <= 0)
        {
            @Redirect::redirect('login');
        }

		return true;
	}

	public static function checkPermisoAcceso ($usuario_id)
	{
		//rehacer con más detalles
	}

    public static function passEncriptado($pass)
    {
        return (strlen($pass) > 15 && strpos($pass, '$2y')  !== false);
    }

    public static function checkSuperAdmin ()
    {
        if(!isset($_SESSION['superadmin']) || $_SESSION['superadmin'] != 1)
        {
            @Redirect::redirect('');
        }

		return true;
    }
}