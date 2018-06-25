<?php
	namespace OW\Lib\Core;
	class Templet
	{
		private $_var=[];
		private $cachedir;
		private $templetdir;
		private $leftlabel;
		private $rightlabel;
		private $suffix;
		private $cachestart;
		private $cachetime;
		private $cachefile=[];

		public function __construct($file_suffix=false,$left=false,$right=false,$Templet=false,$Cache=false)
		{
			$this->set_dir($Templet,$Cache);
			$this->set_label($left,$right);
			$this->suffix = isset($file_suffix) && !empty($file_suffix)?$file_suffix:'tpl';
		}

		public function set_cachestart($start)
		{
			$this->cachestart = $start;
		}

		public function set_cachetime($time)
		{
			if($this->cachestart)
			{
				if(!empty($time))
				{
					if((int)$time > 0)
					{
						$this->cachetime = (int)$time;
					}
					else
					{
						throw new \Exception('Cache time must be a number');
					}
				}
				else
				{
					throw new \Exception('Setting cache time cannot be empty');
				}
			}
			else
			{
				throw new \Exception('The cache is not open and the cache time cannot be set');
			}
		}

		public function set_dir($Templet,$Cache)
		{
			$this->templetdir = isset($Templet) && !empty($Templet)?$Templet:FRAMEWORK_TEMPLET;
			$this->cachedir = isset($Cache) && !empty($Cache)?$Cache:FRAMEWORK_CACHE_TEMPLET;
		}

		public function output($variable,$value)
		{
			$this->_var[$variable] = $value;
			return true;
		}

		private function set_label($left,$right)
		{
			$this->leftlabel = isset($left) && !empty($left)?$left:'{';
			$this->rightlabel = isset($right) && !empty($right)?$right:'}';
		}

		public function display($templet)
		{
			$this->filename = $this->Safe_Replace($templet);
			$templet = "{$this->templetdir}/{$this->Safe_Replace($templet)}.{$this->suffix}";
			if(is_file($templet))
			{
				$content = file_get_contents($templet);
			}
			else
			{
				throw new \Exception('Template file does not exist');
			}
			if(!empty($content))
			{
				return $this->set_templet_var($content);
			}
		}

		private function set_templet_var($content)
		{
			$variables = [];
			$values = [];
			if($this->get_cache_file())
			{
				return true;
			}
			foreach($this->_var as $variable => $value)
			{
				if(!in_array($variable, ['foreach','for']))
				{
					$variables[] = "{$this->leftlabel}ow:{$variable}{$this->rightlabel}";
					$values[] = $value;
				}
			}
			$content = str_replace($variables,$values,$content);
			// $content = preg_replace('/\s*/i','',$content);
			// print $content;
			// exit;
			if(!empty($this->_var["foreach"]))
			{
				if(!is_array($this->_var["foreach"]))
				{
					throw new \Exception("The template foreach must pass in an array ");
				}
				if(preg_match('/'.$this->leftlabel.'ow:foreach\s?as\s?([A-Za-z_]+)((=>)([A-Za-z_]+))?'.$this->rightlabel.'((.|\s)*)'.$this->leftlabel.'ow:endforeach'.$this->rightlabel.'/i', $content,$all))
				{
					$keys = [];
					if(preg_match_all('/'.$all[4].'\.([A-Za-z_]+)/i', $all[5],$value))
					{
						$keys = $value[1];
					}
					$con='';
					$keys = array_filter($keys);
					if(!empty($all[2]))
					{
						foreach($this->_var['foreach'] as ${$all[1]}=>${$all[4]})
						{
							if(!empty($keys))
							{
								$k = $v = [];
								foreach($keys as $key)
								{
									$k[] = "{$all[4]}.{$key}";
									$v[] = ${$all[4]}[$key];
								}
								$con .= str_replace($k,$v,$all[5]);
							}
							else
							{
								$con .= str_replace($all[4],${$all[4]}, $all[5]);
							}
						}
					}
					else
					{
						foreach($this->_var['foreach'] as ${$all[1]})
						{
							$con .= str_replace($all[4],${$all[4]}, $all[5]);
						}
					}
					$content = str_replace($all[0], $con, $content);
				}
			}

			
			if(preg_match('/'.$this->leftlabel.'ow:for\s*loop=([A-Za-z_]+|\d+)?\s*(variable=([A-Za-z_]+))?'.$this->rightlabel.'\s*(.+)\s*'.$this->leftlabel.'ow:endfor'.$this->rightlabel.'/i', $content,$all))
			{
				$con='';
				if(intval($all[1])>0)
				{
					for(${$all[3]}=1;${$all[3]}<=intval($all[1]);${$all[3]}++)
					{
						$con .= str_replace($all[3],${$all[3]}, $all[4]);
					}
				}
				else if(isset($this->_var['for']))
				{
					for(${$all[3]}=1;${$all[3]}<=$this->_var['for'];${$all[3]}++)
					{
						$con .= str_replace($all[3],${$all[3]}, $all[4]);
					}
				}
				$content = str_replace($all[0], $con, $content);
			}
			if(!empty($this->cachetime))
			{
				$this->put_cache_file($content);
			}
			else
			{
				print $content;
			}
		}

		private function put_cache_file($content)
		{
			if(!array_key_exists($this->filename, $_SESSION))
			{
				$time = time();
				$_SESSION[$this->filename] = $time;
			}
			$filename = "{$this->cachedir}/{$this->filename}_".md5($_SESSION[$this->filename]).".{$this->suffix}";
			if(!is_file($filename))
			{
				file_put_contents($filename,$content);
			}
			include $filename;
		}

		private function get_cache_file()
		{
			$time = empty($_SESSION[$this->filename])?false:$_SESSION[$this->filename];
			if($time)
			{
				$filename = "{$this->cachedir}/{$this->filename}_".md5($time).".{$this->suffix}";
				if($time <= (time()-$this->cachetime))
				{
					unset($_SESSION[$this->filename]);
					unlink($filename);
					return false;
				}
				if(is_file($filename))
				{
					return require_once($filename);
				}
			}
			else
			{
				return false;
			}
		}

		private function Safe_Replace($name)
		{
			$name = str_replace('.', '',$name);
			return $name;
		}
	}
?>