<?php

class Paginacion
{
	public static function render($total, $current_page, $limit, $url)
	{
		$paginas = ceil(intval($total)/intval($limit));
		$paginas_a_mostrar = array();
		$min = $current_page - 4;
       	$max = $current_page + 4;
       	$i = $min;
        $j = 0;

		while ($i < $current_page)
		{
			if($i > 0)
			{
				$paginas_a_mostrar[$j] = $i; $j++;
			}
			$i++;
		}
		$paginas_a_mostrar[$j] = $current_page; $j++;
		$i = $current_page + 1;
		while ($i < $max)
		{
			if($i > 0)
			{
			$paginas_a_mostrar[$j] = $i; $j++;
			}
			$i++;
		}

		$html = '<div class="frame-wrap"><nav aria-label="..."><ul class="pagination">';
		$last = 1;
		if($current_page >= 6)
		{
			$html .= '<li class="page-item ';
            $html .= ' ';
            $html .= '"><a class="page-link" href="'.$url;
            $html .= 1;
            $html .= '">'.'1'.'</a></li>';
		}
		foreach($paginas_a_mostrar as $p)
        {
            if($p <= $paginas)
            {
                $html .= '<li class="page-item ';
                if($current_page == $p) $html .= 'active ';
                $html .= '"><a class="page-link" href="'.$url;
                $html .= $p;
                $html .= '">'.$p.'</a></li>';
            }
            $last = $p;
        }
        if($paginas > $last)
        {
        	$html .= '<li class="page-item ';
	        $html .= '';
	        $html .= '"><a class="page-link" href="'.$url;
	        $html .= $paginas;
	        $html .= '">'.$paginas.'</a></li>';
        }
        $html .= '</nav></ul></div>';

        return $html;
	}

	public static function sort_arrows ($actual_key, $key, $order, $page, $url)
	{
		$html = '';
		if($actual_key != $key)
		{
			return '<a href="'.$url.$actual_key.'/asc/'.$page.'"><i class="fa fa-arrow-up color-tecalis"></i></a> <a href="'.$url.$actual_key.'/desc/'.$page.'"><i class="fa fa-arrow-down color-tecalis"></i></a>';
		}
		else
		{
			if($order == strtolower('asc'))
			{
				$html .= '<a href="'.$url.$actual_key.'/asc/'.$page.'"><i class="fa fa-arrow-up fw-800 color-tecalis"></i></a> ';
				$html .= '<a href="'.$url.$actual_key.'/desc/'.$page.'"><i class="fa fa-arrow-down color-tecalis"></i></a> ';
			}
			else
			{
				$html .= '<a href="'.$url.$actual_key.'/asc/'.$page.'"><i class="fa fa-arrow-up color-tecalis"></i></a> ';
				$html .= '<a href="'.$url.$actual_key.'/desc/'.$page.'"><i class="fa fa-arrow-down fw-800 color-tecalis"></i></a> ';
			}
		}
		return $html;
	}
}