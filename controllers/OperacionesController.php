<?php

class OperacionesController extends BaseController
{
    private $config;

    public function __construct()
    {
        global $config;
        $this->config = $config;

        //permisos
        Usuario::checkLogedIn();
    }

    //búsqueda de operaciones
    public function getIndex()
    {
        $operaciones = Operacion::where('estado','!=','auth-init')->orderBy('id','DESC');
        if(!isset($_SESSION['superadmin']) || $_SESSION['superadmin'] != 1)
        {
            $operaciones = $operaciones->where('usuario_id','=',$_SESSION['usuario_id']);
        }
        $operaciones = $operaciones->get();
        $view = ['operaciones' => $operaciones];
        $this->loadView('operaciones.index', $view);
    }

    //operación individual
    public function getRead($id)
    {

        $operacion = Operacion::find($id);

        if(!$operacion) {
            die('mensaje de no encontrado');
        }

        $view = ['operacion' => $operacion];
        $this->loadView('operaciones.read', $view);
    }

    //borrar
    public function getDelete()
    {
        $view = [];
        $this->loadView('operaciones.delete', $view);
    }

    public function postDelete($id)
    {
        $operacion = Operacion::find($id);

        if (!$operacion) {
            die('no encontrado');
        }

        $operacion->delete();

        $this->redirect($this->config->base_url . '/op/list',['Operación eliminado con éxito', 'success','']);
    }
}
