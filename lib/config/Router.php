<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
 * Name   : Collection
 * Date	  : 20120107 
 * Author : Qesy 
 * QQ	  : 762264
 * Mail   : 762264@qq.com
 *
 *(̅_̅_̅(̲̅(̅_̅_̅_̅_̅_̅_̅_̅()ڪے 
 *
*/ 
class Router
{
	private $_default;
	private static $s_instance;	
	public static $s_controller;
	public static $s_method;
	
	function __construct(){
		$this->_default = site_config();
		self::view_controller();
	}

	public static function get_instance(){
		if (!isset(self::$s_instance)){
			self::$s_instance = new self();
		}
		return self::$s_instance;
	}
	
	private function _fetch_url(){
		$url = '';
		$controller_arr = array();
		$requestUrl = empty($_SERVER['REQUEST_URI']) ? '/'.implode('/', array_slice($_SERVER['argv'], 1)) : $_SERVER['REQUEST_URI'];
		$url_arr = explode('.', $requestUrl);	
		$uri = ($url_arr[0] == '/') ? '/' : substr($url_arr[0], 1);
		if($uri == '/'){				
			$controller_arr['name'] = $this->_default['default_controller'];
			$controller_arr['url'] = BASEPATH.'controller/'.$this->_default['default_controller'].EXT;
			$controller_arr['method'] = $this->_default['default_function'];
		}else{			
			$uri_arr = explode($this->_default['url'], $uri);
			foreach($uri_arr as $key => $val){	
				if(empty($val))continue;		 
				$file = $url.$val;		
				$url .= $val.'/';
				if(file_exists(BASEPATH.'controller/'.$file.EXT)){			
					$controller_arr['name'] = $val;
					$controller_arr['url'] = BASEPATH.'controller/'.$file.EXT;
					$fun_url = substr($uri, strlen($file)+1);	
					$fun_arr = explode($this->_default['url'], $fun_url);		
					$controller_arr['method'] = empty($fun_arr[0]) ? 'index' : $fun_arr[0];
					$controller_arr['fun_arr'] = array_splice($fun_arr, 1); 				
					break;
				}		
			}
		}
		return $controller_arr;
	}
	
	private function view_controller(){
		$controller_arr = self::_fetch_url();
		if(empty($controller_arr))self::err();
		require $controller_arr['url'];
		if(method_exists($controller_arr['name'], $controller_arr['method'].'_Action')){
			self::$s_controller = $controller_arr['name'];
			self::$s_method = $controller_arr['method'];
			Base::insert_func_array($controller_arr);
		}else{
			self::err();
		}
	}
	
	private function err(){
		$class = array('name' => 'home', 'url' => '/system/controller/home.php', 'method' => 'err', 'fun_arr' => array());
		require PATH.$class['url'];
		Base::insert_func_array($class);exit;
	}
}
?>