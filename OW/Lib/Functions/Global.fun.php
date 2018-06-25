<?php
	function Safe_Replace($string)
	{
		$string = str_replace("'", '', $string);
		$string = str_replace('"', '', $string);
		$string = str_replace(',', '', $string);
		return $string;
	}
	//接收外部传递的参数
	function input($input,$value='')
	{
		$result=$method=$key=$rex=false;
		if(count(explode(".",$input))==2)
		{
			list($method,$key) = explode(".",$input);
		}
		else
		{
			$key = $input;
		}
		list($key,$rex) = explode(" ", $key);
		if(!$rex)
		{
			$rex = "/w";
		}
		if(!$method)
		{
			$method = "get";
		}
		$filter = function($value,$rex)
		{
			$result=false;
			switch($rex)
			{
				case "/d":
					$result = preg_replace("/[^\d]+/i", "", $value);
					break;
				case "/s":
					$result = preg_replace("/[^A-Za-z_]+/i", "", $value);
					break;
				case "/c":
					$result = preg_replace("/([^\x{4e00}-\x{9fa5}\w'\"])/ui", '', $value);
					break;
				case "/w":
					$result = preg_replace("/[^\w]+/i", "", $value);
				default:
					$result = preg_replace("/[^\w]+/i", "", $value);
					break;
			}
			return $result;
		};
		$by_value = function($method,$key,$value,$rex) use ($filter)
		{
			if(!array_key_exists($key,$method))
			{
				$method[$key] = $filter($value,$rex);
			}
			else
			{
				$method[$key] = $filter($method[$key],$rex);
			}
			return $method[$key];
		};
		switch($method)
		{
			case "get":
				$result = $by_value($_GET,$key,$value,$rex);
				break;
			case "post":
				$result = $by_value($_POST,$key,$value,$rex);
				break;
			case "cookie":
				$result = $by_value($_COOKIE,$key,$value,$rex);
				break;
		}
		return $result;
	}

	function dump($var)
	{
		$html = ob_get_contents();
		ob_clean();
		var_dump($var);
		$var = ob_get_contents();
		ob_clean();
		$var = htmlspecialchars($var);
		print $html;
		print '<pre style="padding:2%;background: 	#696969;border-radius:10px;color:white;">';
		print $var;
		print '</pre>';
	}
?>