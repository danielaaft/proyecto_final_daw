<?php

class OperacionController extends BaseController
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
        $view = [];
        $this->loadView('operaciones.index', $view);
    }

    //operación individual
    public function getRead()
    {
        $view = [];
        $this->loadView('operaciones.read', $view);
    }

    //borrar
    public function getDelete()
    {
        $view = [];
        $this->loadView('operaciones.delete', $view);
    }

    public function postDelete()
    {
        $view = [];
        $this->redirect($this->config->base_url . '/operaciones');
    }
}
