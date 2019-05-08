<?php
use Helper as help;
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
	public $pageNum = 20;
	public $temp_arr = array ();
	public $cookieObj;
	function __construct() {
		$this->cookieObj =  help\Cookie::get_instance();
	}
	public function veriPara(array $request , array $paraArr){
	    foreach($paraArr as $v){
	        if(empty($request[$v])) return false;
	    }
	    return true;
	}
	public function createSn() { // -- Name : 生成编号 --
		return WEB_PREFIX . '-' . uniqid ( rand ( 100, 999 ), false );
	}
	
	public function ApiErr($Code, $Msg = '参数错误'){
	    $this->Ret['Code'] = $Code;
	    $this->Ret['Msg'] = $Msg;
	    die(json_encode($this->Ret));
	}
	
	public function ApiSuccess($Data){
	    $this->Ret['Data'] = $Data;
	    die(json_encode($this->Ret));
	}
	public function  AZ26($n) { //导出excel有用
	    $letter = range('A', 'Z', 1);
	    $s = '';
	    while ($n > 0) {
	        $m = $n % 26;
	        if ($m == 0)
	            $m = 26;
	        $s = $letter[$m - 1] . $s;
	        $n = ($n - $m) / 26;
	    }
	    return $s;
	}
	
	public function getRefer(){ //获取上一页
	    return $_SERVER['HTTP_REFERER'];
	}
	
	
	public function loadView($temp, $data = array()) { // -- Name : 加载模版 --
		if (! is_file ( PATH_SYS . 'View/' . $temp . EXTEND ))
			die ( PATH_SYS . 'View/' . $temp . EXTEND . ' not found !' );
		$this->temp_arr = empty ( $data ) ? $this->temp_arr : $data;
		foreach ( $this->temp_arr as $key => $val ) {
			$$key = $val;
		}
		require PATH_SYS . 'View/' . $temp . EXTEND;
	}
	public function loadCss(array $cssArr) { // -- Name : 加载CSS --
		foreach ( $cssArr as $key => $val ) {
			echo '<link href="' . URL_CSS . $val . '.css?v=' . VERSION . '" rel="stylesheet" type="text/css" />';
		}
	}
	public function loadScripts(array $jsArr) { // -- Name : 加载JS --
		foreach ( $jsArr as $key => $val ) {
			echo '<script type="text/javascript" src="' . URL_JS . $val . '.js?v=' . VERSION . '" charset="utf-8"></script>';
		}
	}
	
    public function page_bar($Count, $Size) { // -- 分页 --
	    $Num = 9;
	    $PageNum = isset($_GET['P']) ? intval($_GET['P']) : 1;	    
	    $Url = URL_ROOT.URL_CURRENT;
		if ($Count <= 0) return '';
		$Toall = ceil ( $Count / $Size );
		($PageNum <= $Toall) || $PageNum = $Toall;
		$PreGet = $NextGet = $PageListGet = $_GET;
		$PreGet['P'] = ($PageNum <= 1) ? 1 : $_GET['P']-1;
		$PreUrl = $Url.'?'.http_build_query($PreGet);
		$PreStr = '<li class="page-item"><a href="' . $PreUrl . '" class="page-link">上一页</a></li>';
		$NextGet['P'] = ($PageNum >= $Toall) ? 1 : $_GET['P']+1;
		$NextUrl = $Url.'?'.http_build_query($NextGet);
		$NextStr = '<li class="page-item"><a href="' . $NextUrl . '" class="page-link">下一页</a></li>';
		$Start = $End = 1;
		$ToallStr = $Str = '';		
		if ($Toall <= $Num) {
			$Start = 1;
			$End = $Toall;
		} elseif (($Toall - $PageNum) > ceil ( $Num / 2 ) && $PageNum < ceil ( $Num / 2 )) {
			$Start = 1;
			$End = $Num;
		} elseif (($Toall - $PageNum) < ceil ( $Num / 2 )) {
			$Start = ($Toall - $Num + 1);
			$End = $Toall;
		} else {
			$Start = ($PageNum - floor ( $Num / 2 ));
			$End = ($PageNum + floor ( $Num / 2 ));
		}
		for($i = $Start; $i <= $End; $i ++) {
		    $PageListGet['P'] = $i;
			$Str .= ($PageNum == $i) ? '<li class="page-item active"><a class="page-link">' . $i . '</a></li>' : '<li class="page-item"><a href="' . $Url.'?'.http_build_query($PageListGet). '" class="page-link">' . $i . '</a></li>';
		}
		return '<ul class="pagination justify-content-center">' . $PreStr . $Str . $NextStr . $ToallStr . '</ul>';
	}
	
	public static function insert_func_array(array $controllerArr) { // -- Name : 回调函数 --
		$fun_arr = isset ( $controllerArr ['funArr'] ) ? $controllerArr ['funArr'] : array ();
		$clss = new $controllerArr ['name'] ();
		call_user_func_array ( array (& $clss, $controllerArr ['method'] . '_Action'), $fun_arr );
	}
}
?>