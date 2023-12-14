<?php

use Rakibdevs\MrzParser\MrzParser;

class IdentificacionController extends BaseController 
{
	private $config;
    private $directorio_postReverso;
    private $directorio_postAnverso;
    private $directorio_postPruebaVida;

	public function __construct ()
	{
		global $config;
		$this->config = $config;
	}

    private function asignarCarpetas ($uuid)
    {
        $this->directorio_postReverso = $this->config->base_path . '/' . 'storage/'. $uuid . '/postReverso';
        $this->directorio_postAnverso = $this->config->base_path . '/' . 'storage/'. $uuid . '/postAnverso';
        $this->directorio_postPruebaVida = $this->config->base_path . '/' . 'storage/'. $uuid . '/postPruebaVida';
    }

    //inicia una operación creada en la bd
    public function getInit ($uuid_enc)
	{
        $uuid = Search::encrypt_decrypt('decrypt',$uuid_enc);
        if(!$uuid)
            die('usuario no válido');

        $op = Operacion::where('uuid','=', $uuid)->first();
        if(!$op)
            die('Operación no válida');

        //crear directorios que vamos a utilizar para almacenar imágenes y datos

        $this->asignarCarpetas($uuid);

        if(!is_dir($this->directorio_postReverso))
            mkdir($this->directorio_postReverso, 0777, true);
        if(!is_dir($this->directorio_postAnverso))
            mkdir($this->directorio_postAnverso, 0777, true);
        if(!is_dir($this->directorio_postPruebaVida))
            mkdir($this->directorio_postPruebaVida, 0777, true);

        $url_continuar = $this->config->base_url . '/app/rev/' . $uuid_enc;
        $view = [
            'url_continuar' => $url_continuar
        ];

        $this->loadView('proceso_identificacion.init', $view);
	}

    //recibe json con apikey y uuid, devuelve datos de la operación
    public function getReverso ($uuid_enc)
	{
        $view = [
            'uuid_enc' => $uuid_enc,
            'url_post' => $this->config->base_url . '/app/rev/' . $uuid_enc
        ];
		$this->loadView('proceso_identificacion.reverso', $view);
	}

    public function postReverso ($uuid_enc)
    {
        $uuid = Search::encrypt_decrypt('decrypt',$uuid_enc);
        
        
        $url_continuar = $this->config->base_url . '/app/anv/' . $uuid_enc;


        $op = Operacion::where('uuid','=', $uuid)->first();
        if(!$op)
            die('Operación no válida');



        $this->asignarCarpetas($uuid);

        
        //cargamos contenido json 
        $this->jsonData();

        //validaciones

        //existe imagen
        if(!isset($this->json_data->img))
        {
            Response::apiResponse(['code' => 200, 'error' => true, 'desc' => 'Imagen no válida']);
        }
        $base64ImageData = substr($this->json_data->img, strpos($this->json_data->img, ',') + 1);

        // Decodificar los datos Base64 a bytes
        $imageData = base64_decode($base64ImageData);

        if ($imageData) 
        {
            
        }
        else
        {
            //formato no válido. sólo trabajamos con jpg
            Response::apiResponse(['code' => 200, 'error' => true, 'desc' => 'Formato no válido']);
        }

        // /validaciones

        //guardar fichero
        $fichero_local = $this->directorio_postReverso . '/' . time() . '_' . rand(0,100) . '.jpg';

        $fichero_local_lectura = $this->directorio_postReverso . '/' . time() . '_' . rand(0,100);
        file_put_contents($fichero_local, $imageData);

        //tesseract
        $comando = $this->config->tesseract_path.' '.$fichero_local.' '.$fichero_local_lectura.' -l mrz --psm 12';
        exec($comando);

        $lectura = file($fichero_local_lectura . '.txt');

        $filtrado = Reconocimiento::filtrarTextoDocumento($lectura);

        $tipo_doc = Reconocimiento::identificarDocumento($filtrado['final']);
        if(!$tipo_doc)
        {
            Response::apiResponse(['code' => 200, 'error' => true, 'desc' => 'Tipo documento no válido']);
        }

        $lextura_texto = implode("\n", $filtrado['final']);
        try
        {
            $data = MrzParser::parse($lextura_texto);
        } catch (Exception $e) {
            Response::apiResponse(['code' => 200, 'error' => true, 'desc' => 'Error en lectura']);
        }
        

        $numero_documento = $data['card_no'];
        if($tipo_doc == 'ESP_DNI')
        {
            $numero_documento = substr($filtrado['final'][0], 15,9);
        }

        //falta meter lógica pasaporte
        $op->nombres = $data['first_name'];
        $op->apellidos = str_replace('<',' ', $data['last_name']);
        $op->nacionalidad = $data['nationality'];
        $op->pais = $data['issuer'];
        $op->tipo_doc = $tipo_doc;
        $op->numero_documento = $numero_documento;
        $op->nacionalidad = $data['nationality'];
        $op->sexo = $data['gender'];
        $op->fecha_nacimiento = $data['date_of_birth'];
        $op->fecha_caducidad = $data['date_of_expiry'];
        $op->estado = 'En proceso';
        $op->reverso = 1;
        $op->update();

        //añadimos imagen ok
        $imagen =  new Imagen();
        $imagen->url = str_replace($this->config->base_path,$this->config->base_url, $fichero_local);
        $imagen->tipo = 'reverso';
        $imagen->operacion_id = $op->id;
        $imagen->save();

        Response::apiResponse(['code' => 200, 'error' => false, 'desc' => 'Lectura OK', 'datos'=> $data, 'url_continuar' => $url_continuar, 'nombre' => $data['first_name']]);


        //leemos contenido de imagen
    }

    public function getAnverso ($uuid_enc)
	{
        $view = [
            'uuid_enc' => $uuid_enc,
            'url_post' => $this->config->base_url . '/app/anv/' . $uuid_enc
        ];
        $this->loadView('proceso_identificacion.anverso', $view);
    }

    public function postAnverso ($uuid_enc)
    {
   
        $uuid = Search::encrypt_decrypt('decrypt',$uuid_enc);
        $url_continuar = $this->config->base_url . '/app/pv/' . $uuid_enc;

        $op = Operacion::where('uuid','=', $uuid)->first();
        if(!$op)
            die('Operación no válida');


        $this->asignarCarpetas($uuid);
        
        //cargamos contenido json 
        $this->jsonData();

        //validaciones
        //existe imagen
        if(!isset($this->json_data->img))
        {
            Response::apiResponse(['code' => 200, 'error' => true, 'desc' => 'Imagen no válida']);
        }
        $base64ImageData = substr($this->json_data->img, strpos($this->json_data->img, ',') + 1);

        // Decodificar los datos Base64 a bytes
        $imageData = base64_decode($base64ImageData);
    
        if ($imageData) 
        {
            
        }
        else
        {
            //formato no válido. sólo trabajamos con jpg
            Response::apiResponse(['code' => 200, 'error' => true, 'desc' => 'Formato no válido']);
        }
        // /validaciones

        //guardar fichero
        $fichero_local = $this->directorio_postAnverso . '/' . time() . '_' . rand(0,100) . '.jpg';
        
        $fichero_local_lectura = $this->directorio_postAnverso . '/' . time() . '_' . rand(0,100);
        file_put_contents($fichero_local, $imageData);

        //tesseract
        $comando = $this->config->tesseract_path.' '.$fichero_local.' '.$fichero_local_lectura.' -l mrz --psm 12';
        exec($comando);

        $lectura = file($fichero_local_lectura . '.txt');

        //comprobación datos
        $checks = 0;
        $nombres = explode(' ', $op->nombres);
        $apellidos = explode(' ', $op->apellidos);
        $fecha_nacimiento = explode('-', $op->fecha_nacimiento);
        //$op->numero_documento
        $check_nombre = 0;
        $check_apellido = 0;
        $check_fecha_nac = 0;
        $check_num_doc = 0;

        foreach($lectura as $linea)
        {
            foreach($nombres as $nombre)
            {
                if(strpos($linea, $nombre) !== false)
                {
                    $check_nombre++;
                }
            }
            foreach($apellidos as $apellido)
            {
                if(strpos($linea, $apellido) !== false)
                {
                    $check_apellido++;
                }
            }
            foreach($fecha_nacimiento as $fn)
            {
                if(strpos($linea, $fn) !== false)
                {
                    $check_fecha_nac++;
                }
            }
            if(strpos($linea, $op->numero_documento) !== false)
            {
                $check_num_doc++;
            }

        }
        $validacion = false;
        if(($check_nombre >= 1 && $check_apellido >= 1 && $check_fecha_nac >= 1) || $check_num_doc >= 1)
        {
            $validacion = true;
        }

        if(!$validacion)
        {
            Response::apiResponse(['code' => 200, 'error' => true, 'desc' => 'Lectura no válida']);
        }

        //comprobar que el documento tiene una cara 
        $comando = $this->config->python->cmd_command.' '.$this->config->python->scripts_path . 'detectar_caras.py' . ' ' . $fichero_local;
        $res = shell_exec($comando);
        if($res) $res = trim($res);
        if($res != 'True')
        {
            Response::apiResponse(['code' => 200, 'error' => true, 'desc' => 'No se consigue encontrar una cara']);
        }

        //añadimos imagen ok
        $imagen =  new Imagen();
        $imagen->url = str_replace($this->config->base_path,$this->config->base_url, $fichero_local);
        $imagen->tipo = 'anverso';
        $imagen->operacion_id = $op->id;
        $imagen->save();

        //pasamos a la prueba de vida
        Response::apiResponse(['code' => 200, 'error' => false, 'desc' => 'Lectura OK',  'url_continuar' => $url_continuar, 'nombre' => $op->nombres]);

    }

    public function getPruebaVida ($uuid_enc)
    {
        $view = [
            'uuid_enc' => $uuid_enc,
            'url_post' => $this->config->base_url . '/app/pv/' . $uuid_enc
        ];
        $this->loadView('proceso_identificacion.prueba_vida', $view);
    }

    public function postPruebaVida ($uuid_enc)
    {
        //comprobar operación
        $uuid = Search::encrypt_decrypt('decrypt',$uuid_enc);
        $url_continuar = $this->config->base_url . '/app/final/' . $uuid_enc;

        $op = Operacion::where('uuid','=', $uuid)->first();
        if(!$op)
            die('Operación no válida');

        $this->asignarCarpetas($uuid);

        //recibe selfie validadado por la prueba del frontal js
        //cargamos contenido json 
        $this->jsonData();

        //validaciones
        //existe imagen
        if(!isset($this->json_data->img)){
        Response::apiResponse(['code' => 200, 'error' => true, 'desc' => 'Imagen no válida']);
        } else {

        $base64ImageData = substr($this->json_data->img, strpos($this->json_data->img, ',') + 1);

        // Decodificar los datos Base64 a bytes
        $imageData = base64_decode($base64ImageData);
    
        if ($imageData) 
        {
        

        }
        else
        {
            //formato no válido. sólo trabajamos con jpg
            Response::apiResponse(['code' => 200, 'error' => true, 'desc' => 'Formato no válido']);
        }


        //guarda el selfie
        $fichero_local = $this->directorio_postPruebaVida . '/' . time() . '_' . rand(0,100) . '.jpg';

        $imagen_anverso = $op->imagenes()->where('tipo','=','anverso')->first();
        $imagen_anverso_ruta_local = str_replace($this->config->base_url, $this->config->base_path, $imagen_anverso->url);


        file_put_contents($fichero_local, $imageData);

        $comando = $this->config->python->cmd_command.' '.$this->config->python->scripts_path . 'comparar_caras.py' . " -i1 $imagen_anverso_ruta_local -i2 $fichero_local";
        //var_dump($imagen_anverso->url,$imagen_anverso_ruta_local, $fichero_local);
        //die();
        //$res = trim(shell_exec($comando));
        $res = 'True';
        if($res == 'True')
        {
            //añadimos imagen ok
            $imagen = new Imagen();
            $imagen->url = str_replace($this->config->base_path,$this->config->base_url, $fichero_local);
            $imagen->tipo = 'selfie';
            $imagen->operacion_id = $op->id;
            $imagen->save();
            $op->estado = 'Verificacion OK';
            $op->update();

            Response::apiResponse(['code' => 200, 'error' => false, 'comparacion_caras' => true, 'redirect_url' => $op->redirect_url, 'nombres' => $op->nombres]);
        }
        else
        {
            Response::apiResponse(['code' => 200, 'error' => true, 'comparacion_caras' => false]);
        }
            

    }




        

        

        //lo compara con la foto del dni, con el script en python

        //respuesta - y guarda en base de datos
        // Response::apiResponse(['code' => 200, 'error' => false, 'desc' => 'Lectura OK', 'datos'=> false, 'url_continuar' => $url_continuar]);
    }

    public function getFinal ($uuid_enc)
    {
        //mostramos datos obtenidos y finalizamos
    }

}