<?php 

class Reconocimiento 
{
    /*
    *   identificarDocumento: Aquí deben introducirse manualmente los paises que se vayan añadiendo
    */
    public static  function identificarDocumento ($filas)
    {
        if( isset($filas[0]) && isset($filas[1]) && isset($filas[2]) && strlen($filas[0]) > 20 && strlen($filas[1]) > 20 && strlen($filas[2]) > 20) 
        {
            if (strpos($filas[0], 'IDESP') !== false) return 'ESP_DNI';

            return 'ESP_DNI';
        }
        elseif( isset($filas[0]) && isset($filas[1]) && strlen($filas[0]) > 20 && strlen($filas[1]) > 20 ) 
        {
            return 'PASSPORT';
        }
        return false;
    }

    public static  function filtrarTextoDocumento ($filas)
    {
        $final = array();
        $i = 0;
        $caracteres = 0;
        $encontrado = false;
        foreach ($filas as $s)
        {
            $s = str_replace(' ', '', $s);
            $s = str_replace(PHP_EOL, '', $s);
            $s = preg_replace( "/\r|\n/", "", $s );

            $caracteres = strlen($s) + $caracteres;

            if( strlen($s) > 26 && strlen($s) < 45 && ((strpos($s, '<') !== false && substr_count($s, "<") >= 1) || $encontrado ) )
            {
                $encontrado = true;
                $final[$i] = $s;
                $i++;
            }
        }
        return ['final' => $final, 'num_caracteres' => $caracteres];
    }

    public static  function validarMayorDeEdad($ano, $mes, $dia)
    {
        if(intval($ano) < 30)
        {
            $ano = '20'.$ano;
        }
        else
        {
            $ano = '19'.$ano;
        }
        $fecha = $ano.'/'.$mes.'/'.$dia;
        $birthday = strtotime($fecha);

        if(time() - $birthday < 18 * 31536000)  {
            return false;
        }
        
        return true;
    }

}