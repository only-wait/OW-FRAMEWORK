<?php
	namespace OW\Lib\Core;
	class Route
	{
		public function __construct()
		{
			$this->get_parameter();
		}

		private function get_parameter()
		{
			switch(URL_MODE)
			{
				case "pathinfo":
					$this->get_pathinfo_parameter();
					break;
				case "s":
					$this->get_s_parameter();
					break;
				case "-":
					$this->get_line_parameter();
					break;
				default:
					$this->get_default_parameter();
					break;
			}
		}

		private function get_default_parameter()
		{
			$_GET[MODEL] = isset($_GET[MODEL]) && !empty($_GET[MODEL])?$_GET[MODEL]:(isset($_GET[strtoupper(MODEL)]) && !empty($_GET[strtoupper(MODEL)])?$_GET[strtoupper(MODEL)]:(isset($_POST[MODEL]) && !empty($_POST[MODEL]) ? $_POST[MODEL]:(isset($_GET[strtoupper(MODEL)]) && !empty($_GET[strtoupper(MODEL)] ?$_GET[strtoupper(MODEL)]:"index"))));

			$_GET[CONTROLL] = isset($_GET[CONTROLL]) && !empty($_GET[CONTROLL])?$_GET[CONTROLL]:(isset($_GET[strtoupper(CONTROLL)]) && !empty($_GET[strtoupper(CONTROLL)])?$_GET[strtoupper(CONTROLL)]:(isset($_POST[CONTROLL]) && !empty($_POST[CONTROLL]) ? $_POST[CONTROLL]:(isset($_GET[strtoupper(CONTROLL)]) && !empty($_GET[strtoupper(CONTROLL)] ?$_GET[strtoupper(CONTROLL)]:"index"))));

			$_GET[METHOD] = isset($_GET[METHOD]) && !empty($_GET[METHOD])?$_GET[METHOD]:(isset($_GET[strtoupper(METHOD)]) && !empty($_GET[strtoupper(METHOD)])?$_GET[strtoupper(METHOD)]:(isset($_POST[METHOD]) && !empty($_POST[METHOD]) ? $_POST[METHOD]:(isset($_GET[strtoupper(METHOD)]) && !empty($_GET[strtoupper(METHOD)] ?$_GET[strtoupper(METHOD)]:"index"))));
		}

		private function get_line_parameter()
		{
			if(isset($_SERVER["QUERY_STRING"]))
			{
				$get_line = explode("-",$_SERVER["QUERY_STRING"]);
				foreach($get_line as $key => $val)
				{
					if($key == 0)
					{
						$_GET[MODEL] = $val;
					}
					elseif($key == 1)
					{
						$_GET[CONTROLL] = $val;
					}
					elseif($key == 2)
					{
						$_GET[METHOD] = $val;
					}
				}
				if(count($get_line)>=3)
				{
					unset($get_line[0]);
					unset($get_line[1]);
					unset($get_line[2]);
				}
				elseif(count($get_line)>=2)
				{
					unset($get_line[0]);
					unset($get_line[1]);
				}
				elseif(count($get_line)>=1)
				{
					unset($get_line[0]);
				}
				$_GET[MODEL] = !empty($_GET[MODEL])?$_GET[MODEL]:"index";
				$_GET[CONTROLL] = !empty($_GET[CONTROLL])?$_GET[CONTROLL]:"index";
				$_GET[METHOD] = !empty($_GET[METHOD])?$_GET[METHOD]:"index";
				$i = 1;
				while($i<count($get_line))
				{
					if(isset($get_line[$i+2]) && isset($get_line[$i+3]))
					{
						$_GET[$get_line[$i+2]] = $get_line[$i+3];
					}
					$i+=2;
				}
			}
		}

		private function get_s_parameter()
		{
			$s = isset($_GET["s"]) && !empty($_GET["s"])?$_GET["s"]:"index/index/index";
			$get_param = explode("/", $s);
			$_GET[MODEL] = isset($get_param[0]) && !empty($get_param[0])?$get_param[0]:"index";
			$_GET[CONTROLL] = isset($get_param[1]) && !empty($get_param[1])?$get_param[1]:"index";
			$_GET[METHOD] = isset($get_param[2]) && !empty($get_param[2])?$get_param[2]:"index";
			if(isset($get_param[0]))
			{
				unset($get_param[0]);
			}
			if(isset($get_param[1]))
			{
				unset($get_param[1]);
			}
			if(isset($get_param[2]))
			{
				unset($get_param[2]);
			}
			if(empty($get_param))
			{
				return;
			}
			$i = 1;
			while($i<count($get_param))
			{
				if(isset($get_param[$i+2]) && isset($get_param[$i+3]))
				{
					$_GET[$get_param[$i+2]] = $get_param[$i+3];
				}
				$i+=2;
			}
		}

		private function get_pathinfo_parameter()
		{
			if(isset($_SERVER["PATH_INFO"]))
			{
				$_SERVER["PATH_INFO"] = substr($_SERVER["PATH_INFO"],strpos($_SERVER["PATH_INFO"], "/")+1);
				$get_param = explode("/",$_SERVER["PATH_INFO"]);
				$_GET[MODEL] = isset($get_param[0]) && !empty($get_param[0])?$get_param[0]:"index";
				$_GET[CONTROLL] = isset($get_param[1]) && !empty($get_param[1])?$get_param[1]:"index";
				$_GET[METHOD] = isset($get_param[2]) && !empty($get_param[2])?$get_param[2]:"index";
				if(isset($get_param[0]))
				{
					unset($get_param[0]);
				}
				if(isset($get_param[1]))
				{
					unset($get_param[1]);
				}
				if(isset($get_param[2]))
				{
					unset($get_param[2]);
				}
				if(empty($get_param))
				{
					return;
				}
				$i = 1;
				while($i<count($get_param))
				{
					if(isset($get_param[$i+2]) && isset($get_param[$i+3]))
					{
						$_GET[$get_param[$i+2]] = $get_param[$i+3];
					}
					$i+=2;
				}
			}
		}
	}
?>