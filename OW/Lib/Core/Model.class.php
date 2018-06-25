<?php
	namespace OW\Lib\Core;
	class Model extends Mysqli_DB
	{
		public function a()
		{
			return self::linkdatabase()->name('article')->select();
		}
	}
?>