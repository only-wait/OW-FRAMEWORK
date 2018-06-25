<?php
	namespace OW\Lib\Core;
	class Error
	{
		private $log_file=FRAMEWORK_LOG;
		public function __construct(){
			//处理Warning和notice级别错误
			set_error_handler(array($this,'appError'));
			//捕捉致命错误,脚本终止 就触发
			register_shutdown_function(array($this,'appFatalError'));
			//自动补货异常
			set_exception_handler(array($this,'appException'));
			}
			//错误处理回掉函数
		public function appError($errno,$errstr,$errfile,$errline){
			$error = array();
			$error['type'] = $errno;
			$error['message'] = $errstr;
			$error['file'] = $errfile;
			$error['line'] = $errline;
			$this->display($error);

		}


		//致命错误处理
		public function appFatalError(){
			if($e = error_get_last()){
				ob_end_clean(); //清除输出缓冲
				$this->display($e);
			}

		}
			//自动捕获异常
		public function appException(PDOException $e){
			$error = array();
			$error['type'] = $e->getCode();
			$error['message'] = $e->getMessage();
			$error['file'] = $e->getFile();
			$error['line'] = $e->getLine();
			$this->display($error);
		} 

		//输出自己的错误
		private function display($error){
			header('HTTP/1.0 404 NOT FONUND');
			$templet = new \OW\Lib\Core\Templet();
			if(DEBUG)
			{
				$templet->output('title','出错啦');
				$templet->output('message',$error['message']);
				$templet->output('file',$error['file']);
				$templet->output('line',$error['line']);
				$templet->display('error');
			}
			else
			{
				$this->log_write($error);
				$templet->output('title','出错啦');
				$templet->output('message','');
				$templet->output('file',$error['file']);
				$templet->output('line',$error['line']);
				$templet->display('error');
			}
			exit(0);
		}

		private function log_write($error)
		{
			$time = date('Y-m-d h:i:sa');
			$url = urlencode("{$_SERVER['REQUEST_SCHEME']}/{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}");
			$message = "{$time} url:{$url} {$error['message']} in {$error['file']} line {$error['line']}\n\r";
			file_put_contents($this->log_file, $message,FILE_APPEND);
		}
	}
?>