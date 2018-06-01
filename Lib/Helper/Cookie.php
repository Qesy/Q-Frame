<?php
namespace Helper; 
defined ( 'PATH_SYS' ) || exit ( 'No direct script access allowed' );
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
class Cookie {
	private static $s_instance;
	public $expire = 72; // -- hour --
	public static function get_instance() {
		if (! isset ( self::$s_instance )) {
			self::$s_instance = new self ();
		}
		return self::$s_instance;
	}
	public function set($arr, $hour = 0) {
		$time = empty ( $hour ) ? $this->expire * 60 * 60 : $hour * 60 * 60;
		foreach ( $arr as $k => $v ) {
			setcookie ( $k, $v, time () + $time, '/', '' );
		}
	}
	public function get($k) {
		return empty ( $_COOKIE [$k] ) ? 0 : $_COOKIE [$k];
	}
	public function del($arr) {
		foreach ( $arr as $k => $v ) {
			setcookie ( $k, '', time () - ($this->expire * 60 * 60), '/', '' );
		}
	}
}