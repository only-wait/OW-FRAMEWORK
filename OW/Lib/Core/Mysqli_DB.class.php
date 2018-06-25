<?php
	namespace OW\Lib\Core;
	class Mysqli_DB
	{
		static $instacn=false;
		private $order='';
		private $limit='';
		public function __construct()
		{
			$this->link = new \Mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
			if (mysqli_connect_error()) {
				die('Connect Error (' . mysqli_connect_errno() . ') '. mysqli_connect_error());
			}
			$this->link->set_charset(DB_CHAR);
		}

		static function linkdatabase()
		{
			if(self::$instacn)
			{
				return self::$instacn;
			}
			self::$instacn = new Mysqli_DB;
			return self::$instacn;
		}

		public function name($table_name)
		{
			$this->table_name = TABLE_SUFFIX.$table_name;
			return $this;
		}

		public function select()
		{
			if(!isset($this->sql))
			{
				$this->sql = "SELECT * FROM {$this->table_name} {$this->order} {$this->limit}";
			}
			return $this->fetch();
		}

		public function count($column=false)
		{
			if(!$column)
			{
				$this->column = "count(*)";
				$this->sql = "SELECT {$this->column} FROM {$this->table_name}";
			}
			else
			{
				$this->column = "count(".preg_replace('/([^A-Za-z]+)/i', '', $column).")";
				$this->sql = "SELECT {$this->column} FROM {$this->table_name}";
			}
			return $this;
		}

		public function limit($limit=false)
		{
			if(strstr($limit,','))
			{
				$limit = explode(',', $limit);
				$limit = array_map(function($int){
					return (int)$int;
				},$limit);
				$limit = implode(',', $limit);
				$this->limit = "LIMIT {$limit}";
			}
			else
			{
				$limit = (int)$limit;
				$this->limit = "LIMIT {$limit}";
			}
			return $this;
		}

		public function order($order="order by id asc")
		{
			preg_match('/((order\s?by\s?[A-Za-z]+\s?(asc|desc)?,?)+|([A-Za-z]+,?)+)/i',$order,$orderArr);
			if(preg_match('/(order\s?by)/i', $orderArr[0]))
			{
				if(strstr($orderArr[0],','))
				{
					$order = explode(',',$orderArr[0]);
					$order = array_filter(array_map(function($ord){
						if(!empty($ord))
						{
							$ord = str_replace(['order','by','desc','asc'],['ORDER','BY','DESC','ASC'] , $ord);
							if(preg_match('/(asc|desc)/i',$ord))
							{
								return $ord;
							}
							return "{$ord} ASC";
						}
					},$order));
					$this->order = implode(',', $order);
				}
				else
				{
					$order = str_replace(['order','by','desc','asc'],['ORDER','BY','DESC','ASC'] , $orderArr[0]);
					if(!preg_match('/(asc|desc)/i',$order))
					{
						$order = "{$order} ASC";
					}
					$this->order = $order;
				}
			}
			else
			{
				if(strstr($orderArr[0],','))
				{
					$order = explode(',',$orderArr[0]);
					$order = array_filter(array_map(function($ord){
						if(!empty($ord))
						{
							return "ORDER BY {$ord} ASC";
						}
					},$order));
					$this->order = implode(',', $order);
				}
				else
				{
					$this->order = "ORDER BY {$orderArr[0]} ASC";
				}
			}
			return $this;
		}

		public function where($where)//[$column=>'in or not in',[1,2]]
		{
			$args = func_get_args();
			if(isset($args[1]))
			{
				unset($args[0]);
				$arg = [];
				foreach($args as $key=>$val)
				{
					$arg[] = "\$args[{$key}]";
				}
				$arg = implode(',',$arg);
				assert("\$where = sprintf(\$where,{$arg});");
				$this->sql = "SELECT * FROM `{$this->table_name}` WHERE {$where} {$this->limit}";
			}
			elseif(is_array($where))
			{
				$column = '';
				$in = '';
				$value = '';
				foreach($where as $col => $val)
				{
					if(is_string($col))
					{
						$column = $col;
						if(strtoupper($val) == 'IN' || strtoupper($val) == 'NOT IN')
						{
							$in = strtoupper($val);
						}
						else
						{
							$in = 'IN';
						}
					}
					if(is_array($val))
					{
						$val = array_filter(array_map(function($v){
							if(!empty($v))
							{
								return "'{$v}'";
							}
						},$val));
						$value = implode(',', $val);
					}
				}
				$this->sql = "SELECT * FROM `{$this->table_name}` WHERE {$column} {$in}({$value}) {$this->limit}";
			}
			else
			{
				$this->sql = "SELECT * FROM `{$this->table_name}` WHERE {$where} {$this->limit}";
			}
			return $this;
		}

		private function fetch()
		{
			$result = $this->link->query($this->sql);
			$this->SQL = $this->sql;
			unset($this->sql);
			unset($this->order);
			unset($this->limit);
			if(isset($this->column))
			{
				$count = $result->fetch_all(MYSQLI_ASSOC)[0][$this->column];
				unset($this->column);
				return $count;
			}
			return $result->fetch_all(MYSQLI_ASSOC);
		}

		public function get_last_sql()
		{
			if(!isset($this->SQL))
			{
				return false;
			}
			return $this->SQL;
		}

		public function __destruct()
		{
			$this->link->close();
		}
	}
?>