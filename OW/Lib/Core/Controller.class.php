<?php
	namespace OW\Lib\Core;
	class Controller
	{
		protected $templet;
		public function __construct()
		{
			$this->templet = new \OW\Lib\Core\Templet();
			$this->set_templet_dir();
			if(method_exists($this, "__init"))
			{
				if($result = $this->__init())
				{
					print $result;
				}
			}
		}

		protected function show_error($title='',$location='',$message='')
		{
			$this->templet->set_dir('','');
			$this->templet->output('title',$title);
			$this->templet->output('message',$message);
			if(isset(parse_url($location)["host"]) && parse_url($location)["host"] == $_SERVER["HTTP_HOST"])
			{
				$location = "window.location.href='{$location}'";
			}
			elseif(!isset(parse_url($location)["host"]))
			{
				$location = "window.location.href='{$location}'";
			}
			else
			{
				$location = "window.history.back(-1)";
			}
			$this->templet->output('location',$location);
			$this->templet->display('show_error');
			$this->set_templet_dir();
			return;
		}

		private function set_templet_dir()
		{
			$appdir = explode('/',str_replace('\\','/',get_class($this)));
			unset($appdir[2]);
			unset($appdir[3]);
			$appdir = implode('/',$appdir);
			$templet = ROOT_PATH."/{$appdir}/Tpl/Templet";
			$templet_cache = ROOT_PATH."/{$appdir}/Tpl/Caches";
			$this->templet->set_dir($templet,$templet_cache);
		}
	}
?>