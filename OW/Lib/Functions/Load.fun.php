<?php
	function __autoload($class_name)
	{
		$class_file = FRAMEWORK_ROOT.str_replace(['\\','OW'], ['/',''], $class_name).".class.php";
		$ctrl_file = ROOT_PATH.'/'.str_replace('\\','/', $class_name).".class.php";
		if(is_file($class_file))
		{
			require_once($class_file);
			return;
		}
		elseif(is_file($ctrl_file))
		{
			require_once($ctrl_file);
			return;
		}
		else
		{
			throw new Exception("{$class_name} non existent");
		}
	}
	function load_class($class_name,$object)
	{
		if(!preg_match("/([A-Za-z]+)/i", $class_name))
		{
			return false;
		}
		$class_file = FRAMEWORK_CORE."/{$class_name}.class.php";
		if(is_file($class_file))
		{
			if(in_array($class_name, $object->class))
			{
				return;
			}
			else
			{
				array_push($object->class,$class_name);
				require_once($class_file);
			}
		}
		else
		{
			throw new Exception("{$class_name} non existent");
			
		}
	}

	function load_function($fun_name,$object)
	{
		if(!preg_match("/([A-Za-z]+)/i", $fun_name))
		{
			return false;
		}
		$fun_file = FRAMEWORK_FUN."/{$fun_name}.fun.php";
		if(is_file($fun_file))
		{
			if(in_array($fun_name, $object->fun))
			{
				return;
			}
			else
			{
				array_push($object->fun,$fun_name);
				require_once($fun_file);
			}
		}
		else
		{
			throw new Exception("{$fun_name} non existent");
			
		}
	}
?>