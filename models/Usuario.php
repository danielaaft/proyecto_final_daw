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

            if(!isset($usuario->password) || $usuario->tipo != 'admin') @Redirect::redirect('login',['Invalid credentials','danger','Error']);

            $pedir_cambio = false;
            
            if(self::passEncriptado($usuario->password))
            {
                $check = password_verify ($password, $usuario->password); 
            }
            else
            {
                $check = ($usuario->password === $password);
                $pedir_cambio = true;
            }
            

            if(!$check) return false;
            
            $info = new SessionInfo();
            $info->remote_addr = $_SERVER['REMOTE_ADDR'];
            $info->remote_port = $_SERVER['REMOTE_PORT'];
            $info->client_ip = @$_SERVER['HTTP_CLIENT_IP'];
            $info->forwarded_for = @$_SERVER['HTTP_X_FORWARDED_FOR'];
            $info->browser = $_SERVER['HTTP_USER_AGENT'];
            //$info->os = php_uname();	
            $info->usuario_id = $usuario->id;
            $info->save();

            //aprobar inicio de sesion_correo
            /*if(isset($config->enable_device_verification) && $config->enable_device_verification === true)
            {
                //$ip_data = $info->remote_addr.'|'.$info->remote_port.'|'.$info->client_ip.'|'.$info->forwarded_for;
                $ip_data = $info->remote_addr.'|'.$info->client_ip.'|'.$info->forwarded_for;
                $check = AccesoPermitido::where('usuario_id','=',$usuario->id)
                        ->where('ip','=',$ip_data)
                        ->where('browser','=',$info->browser)
                        //->where('status','=','ok')
                        ->orderBy('id','DESC')
                        ->first();
                if($check)
                {
                    if($check->status == 'ko')
                    {
                        die('EA01');
                    }
                    $_SESSION['inicio_sesion_correo_estado'] = $check->status;
                    $_SESSION['inicio_sesion_correo_envios'] = 0;
                    $_SESSION['inicio_sesion_correo_id'] = $check->id;
                }
                else
                {
                    $acceso = new AccesoPermitido();
                    $acceso->usuario_id = $usuario->id;
                    $acceso->ip = $ip_data;
                    $acceso->browser = $info->browser;
                    $acceso->status = 'pendiente';
                    $acceso->save();

                    $_SESSION['inicio_sesion_correo_estado'] = $acceso->status;
                    $_SESSION['inicio_sesion_correo_envios'] = 0;
                    $_SESSION['inicio_sesion_correo_id'] = $acceso->id;
                }
                   
            }*/
            // /aprobar inicio de sesion_correo

        	$_SESSION['usuario_id'] = $usuario->id;
        	$_SESSION['email'] = $usuario->email;
        	$_SESSION['nombre'] = $usuario->name;
        	$_SESSION['empresa_id'] = isset($usuario->empresa()->id) ? $usuario->empresa()->id : '';
        	$_SESSION['session_info_id'] = $info->id;

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

}