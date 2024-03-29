<?php
use Helper\Code;
use Helper\Build;
use Helper\Cookie;
use Helper\CurlQ;
use Helper\Veri;
use Helper\Common;
use Helper\Upload;
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
	public $UploadObj;
	
	public $PageNum = 20;
	public $TempArr = array();
	function __construct() {
	    $this->CodeObj = Code::get_instance();
	    $this->BuildObj = Build::get_instance();
		$this->CookieObj = Cookie::get_instance();		
		$this->CurlObj = CurlQ::get_instance();
		$this->VeriObj = Veri::get_instance();
		$this->CommonObj = Common::get_instance();		
		$this->UploadObj = Upload::get_instance();
	}
	
	public function LoadView($Temp, $Data = array()) { // -- Name : 加载模版 --
	    if (! is_file ( PATH_SYS . 'View/' . $Temp . EXTEND )) die ( PATH_SYS . 'View/' . $Temp . EXTEND . ' not found !' );
	    $this->TempArr = empty ( $Data ) ? $this->TempArr : array_merge($this->TempArr, $Data);
	    foreach ( $this->TempArr as $Key => $Val ) $$Key = $Val;
	    require PATH_SYS . 'View/' . $Temp . EXTEND;
	}
	
	public static function InsertFuncArray(array $ControllerArr) { // -- Name : 回调函数 --
		$ParaArr = isset ( $ControllerArr ['ParaArr'] ) ? $ControllerArr ['ParaArr'] : array ();
		$Class = new $ControllerArr ['Name'] ();
		call_user_func_array ( array (& $Class, $ControllerArr ['Method'] . '_Action'), $ParaArr );
	}
}
?>