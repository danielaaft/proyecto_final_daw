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
    public function getIndex ($search_data, $limit, $key, $order, $pagina)
    {
        if(!$search_data) $search_data = 'default';
		if(!$pagina) $pagina = 1;
		if(!$limit) $limit = 10;
		if(!$key) $key_order = 'id';
        else $key_order = $key;
		if(!$order) $order = 'DESC';

        $operaciones = Operacion::where('estado','!=','auth-init')->orderBy('id','DESC');
        if(!isset($_SESSION['superadmin']) || $_SESSION['superadmin'] != 1)
        {
            $operaciones = $operaciones->where('usuario_id','=',$_SESSION['usuario_id']);
        }

        if($search_data != false && $search_data != 'default')
        {
        	$search_data_decripted = json_decode(Search::encrypt_decrypt('decrypt', $search_data));
        	foreach($search_data_decripted as $key => $value)
	        {
	        	if($key == 'fecha_desde')
	        	{
	        		$operaciones = $operaciones->where('created_at','>=',$value . ' 00:00:00');
	        	}
	        	if($key == 'fecha_hasta')
	        	{
	        		$operaciones = $operaciones->where('created_at','<=',$value . ' 23:59:59');
	        	}
                if($key == 'estado')
                {
                    $operaciones = $operaciones->where('estado','=',$value);
                }
                if($key == 'nombres')
                {
                    $operaciones = $operaciones->where('nombres','LIKE','%'.$value.'%');
                }
                if($key == 'apellidos')
                {
                    $operaciones = $operaciones->where('apellidos','LIKE','%'.$value.'%');
                }
                if($key == 'numero_documento')
                {
                    $operaciones = $operaciones->where('numero_documento','LIKE','%'.$value.'%');
                }
	        }   
        }

        $skip = ($pagina *  intval($limit)) - intval($limit);

        $total = $operaciones->count();

        $resultado = $operaciones->orderBy($key_order,$order)->skip($skip)->take($limit)->get();
        $view = [
			'pagina' => $pagina,
			'limit' => $limit,
			'key' => $key_order,
			'order' => strtolower($order),
			'total' => $total,
			'operaciones' => $resultado,
			'search_data' => $search_data,
			'estados'=> [
                'En proceso','Verificacion OK', 'Verificacion KO'
            ]
		];
        $this->loadView('operaciones.index', $view);
    }

    public function postIndex ()
    {
		$this->sanitizeRequestData();

		$busqueda = [];

		foreach($this->request as $key => $value)
		{
			if($value == '') continue;
			$busqueda[$key] = $value;
		}

		$search_data = Search::encrypt_decrypt('encrypt', json_encode($busqueda));

		if($this->request->resultados_pagina == '') $this->request->resultados_pagina = 10;

		$this->redirect('operaciones/'.$search_data.'/'.$this->request->resultados_pagina.'/id/DESC/1');
    }

    //operación individual
    public function getRead($id)
    {
        $operacion = Operacion::where('id','=',$id);
        if(!isset($_SESSION['superadmin']) || $_SESSION['superadmin'] != 1)
        {
            $operacion = $operacion->where('usuario_id','=',$_SESSION['usuario_id']);
        }
        $operacion = $operacion->first();

        if(!$operacion) {
            $this->redirect($this->config->base_url . '/operaciones',['Operación no válida','danger','']);
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
