<?php 

class Input
{
	public function __construct ()
	{
		//
	}

	public static function old ($key, $default_value = null)
	{
		if(!isset($_SESSION['input_old'][$key])) return ($default_value != null ? $default_value : '');

		if($default_value == null)
		{
			return $_SESSION['input_old'][$key];
		}
		else
		{
			if($_SESSION['input_old'][$key] == '')
			{
				return $default_value;
			}
			else
			{
				return $_SESSION['input_old'][$key];
			}
		}
	}

	public static function old_selected($key, $value, $default_value = null)
	{
		if(!isset($_SESSION['input_old'][$key])) 
		{
			if($value == $default_value) return "selected";
			else return "";
		}

		if($_SESSION['input_old'][$key] == $value)
			return "selected";
		else
			return '';
	}


	public static function old_selected_multiple($key, $ar, $default_value)
	{
		if(!$ar)
		{	
			if(isset($_SESSION['input_old'][$key]))
			{
				foreach($_SESSION['input_old'][$key] as $input_old)
				{
					if($input_old == $default_value)
						return "selected";
				}
			}

			return '';
		}

		if(!isset($_SESSION['input_old'][$key])) 
		{
			if(in_array($default_value, $ar)) return "selected";
			else return "";
		}

		foreach($_SESSION['input_old'][$key] as $input_old)
		{
			if($input_old == $default_value)
				return "selected";
		}
		return '';
	}

	public static function slugify($text, string $divider = '-')
	{
	  $text = preg_replace('~[^\pL\d]+~u', $divider, $text);
	  $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
	  $text = preg_replace('~[^-\w]+~', '', $text);
	  $text = trim($text, $divider);
	  $text = preg_replace('~-+~', $divider, $text);
	  $text = strtolower($text);
	  if (empty($text)) 
	  {
	    return 'n-a';
	  }

	  return $text;
	}
}