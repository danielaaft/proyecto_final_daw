<?php

use Ramsey\Uuid\Uuid;


class UsuariosController extends BaseController
{
    private $config;

    public function __construct()
    {
        global $config;
        $this->config = $config;

        //permisos
        Usuario::checkLogedIn();

        Usuario::checkSuperAdmin();
    }

    //
    public function getIndex()
    {
        //obtener todos los usuarios de la bbdd
        $usuarios = Usuario::all();

        $view = [
            'usuarios' => $usuarios
        ];
        $this->loadView('usuarios.index', $view);

        
    }

    //
    public function getRead()
    {
        $view = [];
        $this->loadView('usuarios.read', $view);
    }

     //
     public function getUpdate($id)
     {
        $usuario = Usuario::find($id);

        $view = [
            'usuario' => $usuario
        ];
        
        $this->loadView('usuarios.update', $view);
     }

     public function postUpdate($id)
     {
        $usuario = Usuario::find($id);

        if (!$usuario) {
            //redireccionar
        }

        $usuario->nombre = $_POST['nombre'];
        $usuario->apellidos = $_POST['apellidos'];
        $usuario->email = $_POST['email'];
        $usuario->empresa = $_POST['empresa'];
        if (!empty($_POST['password'])) {
            $usuario->password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        }

        $usuario->save();

        $this->redirect($this->config->base_url . '/list',['Usuario editado con éxito', 'success','']);
     }
     
     public function getCreate()
     {

         $view = [
			'url_form_post' => $this->config->base_url . '/create'
		];

		$this->loadView('usuarios.create', $view);
     }

     public function postCreate()
    {
        $this->sanitizeRequestData();

     $nombre = $this->request->nombre;
     $apellidos = $this->request->apellidos;
     $email = $this->request->email;
     $empresa = $this->request->empresa;
     $password = $this->request->password;

     $usuarioExistente = Usuario::where('email', $email)->first();

     if($usuarioExistente) {
        {
            //si el usuario existe, se redirecciona
    		$this->redirect($this->config->base_url . '/create',['Email ya registrado', 'danger','']);
    	}
     }

     if ($nombre && $apellidos && $email && $empresa && $password) {
        //guardar en bbdd
        $nuevoUsuario = new Usuario();
        
        $nuevoUsuario->nombre = $nombre;
        $nuevoUsuario->apellidos = $apellidos;
        $nuevoUsuario->email = $email;
        $nuevoUsuario->empresa = $empresa;
        $nuevoUsuario->password = password_hash($password, PASSWORD_BCRYPT);
        $nuevoUsuario->apiKey= rand(0,1000) . Uuid::uuid4()->toString();

        $nuevoUsuario->save();
        $this->redirect($this->config->base_url . '/create',['Usuario guardado con éxito', 'success','']);
     }
     
        //$this->redirect($this->config->base_url . '/usuarios');
     }

    
    public function getDelete($id)
    {
        $usuario = Usuario::find($id);

        $view = [
            'usuario' => $usuario
        ];
        
        $this->loadView('usuarios.delete', $view);
    }

    public function postDelete($id)
    {
        $usuario = Usuario::find($id);

        if ($usuario) {
            $usuario->delete();
            $this->redirect($this->config->base_url . '/list',['Usuario eliminado', 'success','']);
        }
        
        
    }
}
