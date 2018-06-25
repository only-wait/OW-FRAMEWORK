<?php
	namespace OW\Lib\Core;
	class Application
	{
		public $class = [];
		public $fun = [];
		public function __construct()
		{
			$this->session_start();
			new \OW\Lib\Core\Error();
			new \OW\Lib\Core\Route();
			$this->Controll();
			$this->action();
			// new a;
			// text();
			// $db = \OW\Lib\Core\Mysqli_DB::linkdatabase();
			// $articles = $db->name('article')->select();
			// new Controller();
			// print $a;
			// exit;
			// print_r($db->name('article')->count()->select());
			// $db->name('article')->limit('0,1')->where(array('id'=>'not in',[1]))->select();
			// print_r($db->name('article')->limit('0,1')->select());
			// print_r($db->name('article')->order()->limit(2)->select());
			// print $db->get_last_sql();
			// print count($articles);
			// print '<pre>';
		}

		private function session_start()
		{
			if(SESSION=='start')
			{
				session_start();
			}
		}

		private function Controll()
		{
			$m = ucfirst(input("get.m /s"));
			$c = ucfirst(input("get.c /s"));
			$ctrl = "\\Application\\{$m}\\Controller\\{$c}Controller";
			$this->appController = new $ctrl;
		}

		private function action()
		{
			$a = ucfirst(input("get.a /s"));
			if(method_exists($this->appController,$a))
			{
				if($result = $this->appController->$a())
				{
					print $result;
				}
			}
			else
			{
				throw new Exception("Method does not exist");
				
			}
		}
	}
?>