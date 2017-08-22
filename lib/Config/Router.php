<?php
defined ( 'SYS_PATH' ) || exit ( 'No direct script access allowed' );
/*
 * Name : Collection
 * Date : 20120107
 * Author : Qesy
 * QQ : 762264
 * Mail : 762264@qq.com
 *
 * (̅_̅_̅(̲̅(̅_̅_̅_̅_̅_̅_̅_̅()ڪے
 *
 */
class Router {
	private $_default;
	private static $s_instance;
	public static $s_controller;
	public static $s_method;
	function __construct() {
		$this->_default = site_config ();
		self::view_controller ();
	}
	public static function get_instance() {
		if (! isset ( self::$s_instance )) {
			self::$s_instance = new self ();
		}
		return self::$s_instance;
	}
	private function _fetch_url() {
		$controller_arr = array ();
		if (php_sapi_name () == 'cli') {
			$uri = implode ( '/', array_slice ( $_SERVER ['argv'], 1 ) );
		} else {
			$uri = ! strpos ( SITE_URL, $this->_default ['Extend'] ) ? SITE_URL : substr ( SITE_URL, 0, - strlen ( $this->_default ['Extend'] ) );
		}
		if (strpos ( $uri, 'poweredByQesy' ) !== false) {
			echo "powered By Qesy <br>\n";
			echo "Email : 762264@qq.com <br>\n";
			echo "Version : QFrame v 1.0.0 <br>\n";
			echo "Your Ip : " . ip () . "<br>\n";
			echo "Date : " . date ( 'Y-m-d H:i:s' ) . "<br>\n";
			echo "UserAgent : " . $_SERVER ['HTTP_USER_AGENT'] . "<br>\n";
			exit ();
		}
		if (! $uri) {
			
			$controller_arr ['name'] = $this->_default ['DefaultController'];
			$controller_arr ['url'] = SYS_PATH . 'controller/' . $this->_default ['DefaultController'] . EXTEND;
			$controller_arr ['method'] = $this->_default ['DefaultFunction'];
		} else {
			$uri_arr = explode ( $this->_default ['Url'], $uri );
			$url = '';
			foreach ( $uri_arr as $key => $val ) {
				$file = $url . $val;
				$url .= $val . $this->_default ['Url'];
				if (file_exists ( SYS_PATH . 'controller/' . $file . EXTEND )) {
					$controller_arr ['name'] = $val;
					$controller_arr ['url'] = SYS_PATH . 'controller/' . $file . EXTEND;
					$fun_url = substr ( $uri, strlen ( $file ) + 1 );
					$fun_arr = explode ( $this->_default ['Url'], $fun_url );
					$controller_arr ['method'] = empty ( $fun_arr [0] ) ? 'index' : $fun_arr [0];
					$controller_arr ['fun_arr'] = array_splice ( $fun_arr, 1 );
					break;
				}
			}
		}
		return empty ( $controller_arr ) ? array (
				'name' => 'home',
				'url' => SYS_PATH . 'controller/home.php',
				'method' => 'err',
				'fun_arr' => array () 
		) : $controller_arr;
	}
	private function view_controller() {
		$controller_arr = self::_fetch_url ();
		require $controller_arr ['url'];
		if (! method_exists ( $controller_arr ['name'], $controller_arr ['method'] . '_Action' )) {
			$controller_arr = array (
					'name' => 'home',
					'url' => SYS_PATH . 'controller/home.php',
					'method' => 'err',
					'fun_arr' => array () 
			);
			require_once $controller_arr ['url'];
		}
		self::$s_controller = $controller_arr ['name'];
		self::$s_method = $controller_arr ['method'];
		Base::insert_func_array ( $controller_arr );
	}
}
?>