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
class Common {
    
	private static $s_instance;
	public $Ret = array('Code' => 0, 'Data' => array(), 'Msg' => '');
	
	public static function get_instance() {
		if (! isset ( self::$s_instance )) {
			self::$s_instance = new self ();
		}
		return self::$s_instance;
	}
	
	public function ApiErr($Code, $Msg = '参数错误'){
	    $this->Ret['Code'] = $Code;
	    $this->Ret['Msg'] = $Msg;
	    die(json_encode($this->Ret));
	}
	
	public function ApiSuccess($Data = array()){
	    $this->Ret['Data'] = $Data;
	    die(json_encode($this->Ret));
	}
	
	public function CreateSn() { // -- Name : 生成编号 --
	    return WEB_PREFIX . '-' . uniqid ( rand ( 100, 999 ), false );
	}
	
	
	public function  AZ26($n) { //导出excel有用
	    $Letter = range('A', 'Z', 1);
	    $s = '';
	    while ($n > 0) {
	        $m = $n % 26;
	        if ($m == 0)
	            $m = 26;
	        $s = $Letter[$m - 1] . $s;
	        $n = ($n - $m) / 26;
	    }
	    return $s;
	}
	
	public function GetRefer(){ //获取上一页
	    return $_SERVER['HTTP_REFERER'];
	}
	
	public function HttpBuildQueryQ($Arr){
	    $RetArr = array();
	    foreach($Arr as $k => $v) $RetArr[] = $k.'='.$v;
	    return implode('&', $RetArr);
	}
	
	public function ip() { // -- 获取IP --
	    $cip = 0;
	    if (! empty ( $_SERVER ["HTTP_CLIENT_IP"] )) {
	        $cip = $_SERVER ["HTTP_CLIENT_IP"];
	    } elseif (! empty ( $_SERVER ["HTTP_X_FORWARDED_FOR"] )) {
	        $cip = $_SERVER ["HTTP_X_FORWARDED_FOR"];
	    } else if (! empty ( $_SERVER ["REMOTE_ADDR"] )) {
	        $cip = $_SERVER ["REMOTE_ADDR"];
	    }
	    return $cip;
	}
	
	public function thumb($url, $width, $heiht, $noWaterMark = 0) { // -- 缩略图 --
	    $url = str_replace ( 'source', 'thumb', $url );
	    $ext = substr ( $url, - 4 );
	    $path = substr ( $url, 0, - 4 );
	    return empty ( $noWaterMark ) ? $path . '_w' . $width . '_h' . $heiht . $ext : $path . '_w' . $width . '_h' . $heiht . '_' . $noWaterMark . $ext;
	}
	
}