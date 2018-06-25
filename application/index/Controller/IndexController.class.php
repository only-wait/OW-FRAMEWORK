<?php
	namespace Application\Index\Controller;
	class IndexController extends \OW\Lib\Core\Controller
	{
		protected function __init()
		{
			return 'this is init method';
		}

		public function Index()
		{
			$this->show_error('出错了','no','a');
			return;
		}
	}
?>