<?php
use Helper\Code;
use Helper\Build;
use Helper\Cookie;
use Helper\CurlQ;
use Helper\Veri;
use Helper\Common;
use Helper\Template;
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
abstract class Base {
	public $CommonObj;	
	public $CookieObj;
	public $CodeObj;
	public $BuildObj;
	public $CurlObj;
	public $VeriObj;
	public $TempObj;
	
	function __construct() {
	    $this->CodeObj = Code::get_instance();
	    $this->BuildObj = Build::get_instance();
		$this->CookieObj = Cookie::get_instance();		
		$this->CurlObj = CurlQ::get_instance();
		$this->VeriObj = Veri::get_instance();
		$this->CommonObj = Common::get_instance();
		$this->TempObj = Template::get_instance();		
	}
	
	public static function InsertFuncArray(array $ControllerArr) { // -- Name : 回调函数 --
		$ParaArr = isset ( $ControllerArr ['ParaArr'] ) ? $ControllerArr ['ParaArr'] : array ();
		$Class = new $ControllerArr ['Name'] ();
		call_user_func_array ( array (& $Class, $ControllerArr ['Method'] . '_Action'), $ParaArr );
	}
}
?>