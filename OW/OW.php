<?php
	namespace OW;
	class ow
	{
		static $res=false;
		private $Conf;
		private $Lib=[];

		public function __construct()
		{
			$this->Conf = $this->load_Conf("Site");
			$this->Set_Define($this->Conf);
			$this->load_function("Load");
			$this->load_function("Global");
			new Lib\Core\Application();
		}

		static function start()
		{
			if(self::$res)
			{
				return self::$res;
			}
			self::$res = new ow();
			return self::$res;
		}

		static function name_check($name)
		{
			if(preg_match('/([A-Za-z_]+)/i',$name))
			{
				return true;
			}
			return false;
		}

		private function Set_Define($define)
		{
			if(is_array($define))
			{
				foreach ($define as $key => $value) {
					if(is_array($value))
					{
						$this->Set_Define($value);
					}
					else
					{
						$this->Set_Define("{$key},{$value}");
					}
				}
			}
			else
			{
				$define = explode(",", $define);
				define(strtoupper($define[0]),$define[1]);
			}
		}

		private function load_function($fun_name)
		{
			if(!self::name_check($fun_name))
			{
				return false;
			}
			$fun_file = FRAMEWORK_FUN."/{$fun_name}.fun.php";
			if(in_array($fun_name, $this->Lib))
			{
				return;
			}
			else
			{
				array_push($this->Lib,$fun_name);
			}
			if(is_file($fun_file))
			{
				require_once($fun_file);
				return true;
			}
			else
			{
				throw new \Exception("function file does not exist");
			}
		}

		private function load_Conf($conf_name)
		{
			if(!self::name_check($conf_name))
			{
				return false;
			}
			$Conf_name = FRAMEWORK_ROOT."/Lib/Conf/{$conf_name}.conf.php";
			if(in_array($conf_name, $this->Lib))
			{
				return;
			}
			else
			{
				array_push($this->Lib,$conf_name);
			}
			if(is_file($Conf_name))
			{
				return require_once($Conf_name);
			}
			else
			{
				throw new \Exception("Configuration file does not exist");
			}
		}
	}
?>