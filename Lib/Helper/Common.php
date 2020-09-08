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
	public $TempArr = array ();
	public $Ret = array('Code' => 0, 'Data' => array(), 'Msg' => '');
	
	public static function get_instance() {
		if (! isset ( self::$s_instance )) {
			self::$s_instance = new self ();
		}
		return self::$s_instance;
	}
	
	public function GetQuery(){
	    return empty($_SERVER['QUERY_STRING']) ? array() : explode('=', $_SERVER['QUERY_STRING']);
	}
	
	public function SetQuery($Key, $Val){
	    $Query = self::GetQuery();
	    $Query[$Key] = $Val;
	    return $Query;
	}
	
	public function Err($Str){
	    self::ExecScript('alert("'.$Str.'");window.history.go(-1);');
	}
	
	public function Success($Url, $Str = ''){
	    if(!empty($Str)){
	        self::ExecScript('alert("'.$Str.'"); window.location.href="'.$Url.'?'.http_build_query($_GET).'"');
	    }else{
	        self::ExecScript('window.location.href="'.$Url.'?'.http_build_query($_GET).'"');
	    }
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
	    return htmlentities($_SERVER['HTTP_REFERER']);
	}
	
	public function GetUa(){ //获取UA
	    return htmlentities($_SERVER['HTTP_USER_AGENT']);
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
	    return htmlentities($cip);
	}
	
	public function thumb($url, $width, $heiht, $noWaterMark = 0) { // -- 缩略图 --
	    $url = str_replace ( 'source', 'thumb', $url );
	    $ext = substr ( $url, - 4 );
	    $path = substr ( $url, 0, - 4 );
	    return empty ( $noWaterMark ) ? $path . '_w' . $width . '_h' . $heiht . $ext : $path . '_w' . $width . '_h' . $heiht . '_' . $noWaterMark . $ext;
	}
	
	/* public function LoadView($Temp, $Data = array()) { // -- Name : 加载模版 --
	    if (! is_file ( PATH_SYS . 'View/' . $Temp . EXTEND )) die ( PATH_SYS . 'View/' . $Temp . EXTEND . ' not found !' );
	
	    $this->TempArr = empty ( $Data ) ? $this->TempArr : $Data;
	    foreach ( $this->TempArr as $Key => $Val ) $$Key = $Val;
	    require PATH_SYS . 'View/' . $Temp . EXTEND;
	} */
    public function LoadCss(array $CssArr, $IsBoot = false) { // -- Name : 加载CSS --
	    $Path = $IsBoot ? URL_BOOT.'css/' : URL_CSS;
	    foreach ( $CssArr as $Val ) echo "<link href=\"" . $Path . $Val . ".css?v=". VERSION ."\" rel=\"stylesheet\" type=\"text/css\" />\n";
	}
	public function loadScripts(array $jsArr, $IsBoot = false) { // -- Name : 加载JS --
	    $Path = $IsBoot ? URL_BOOT.'js/' : URL_JS;
	    foreach ( $jsArr as $key => $val ) echo "<script type=\"text/javascript\" src=\"" . $Path . $val .".js?v=" . VERSION . "\" charset=\"utf-8\"></script>";
	}
	
	public function ExecScript($Str) { // -- 运行JS --
	    echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><script>' . $Str . '</script>';
	    exit ();
	}
	public function GoUrl(array $UrlArr) { // -- JS跳转 --
	    self::exec_script ( 'window.location.href="' . self::Url ( $UrlArr ) . '"' );
	    exit ();
	}
	public function Url(array $UrlArr = array ('index')) { // -- 路径函数 --
	    $Url = array ();
	    foreach ( $UrlArr as $key => $val ) {
	        $Url [] = $val;
	    }
	    return URL_ROOT . implode ( '/', $Url ) . '.html';
	}
	
	public function PageBar($Count, $Size) { // -- 分页 --
	    $Num = 9;
	    $PageNum = !empty($_GET['P']) ? intval($_GET['P']) : 1;
	    $Url = URL_ROOT.URL_CURRENT;
	    if ($Count <= 0) return '';
	    $Toall = ceil ( $Count / $Size );
	    ($PageNum <= $Toall) || $PageNum = $Toall;
	    $JumpGet = $PreGet = $NextGet = $PageListGet = $_GET;
	    $PreGet['P'] = ($PageNum <= 1) ? 1 : $PageNum-1;
	    $PreUrl = $Url.'?'.http_build_query($PreGet);
	    $PreStr = '<li class="page-item '.(($PageNum == 1) ? 'disabled' : '').'"><a href="' . $PreUrl . '" class="page-link">上一页</a></li>';
	    $NextGet['P'] = ($PageNum >= $Toall) ? 1 : $PageNum+1;
	    $NextUrl = $Url.'?'.http_build_query($NextGet);
	    $NextStr = '<li class="page-item '.(($PageNum == $Toall) ? 'disabled' : '').'"><a href="' . $NextUrl . '" class="page-link">下一页</a></li>';
	    $PageListGet['P'] = 1;
	    $FirstPage = '<li class="page-item '.(($PageNum == 1) ? 'disabled' : '').'"><a href="'.$Url.'?'.http_build_query($PageListGet).'" class="page-link">首页</a></li>';
	    $PageListGet['P'] = $Toall;
	    $LastPage = '<li class="page-item '.(($PageNum == $Toall) ? 'disabled' : '').'"><a href="'.$Url.'?'.http_build_query($PageListGet).'" class="page-link">尾页</a></li>';
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
	    unset($JumpGet['P']);
	    $Jump = "
		    <div class='input-group input-group-sm mb-3 p-1' style='width:80px'>
                <input type='text' class='form-control'  id='QFramePageNum' value='".$PageNum."'>
	                <div class='input-group-append'>
	                <button class='btn btn-primary' type='button' onclick='QFramePageJump()'>GO!</button>
	                </div>
	                </div>
	                <script>
	                function QFramePageJump(){
	                window.location.href='{$Url}?".http_build_query($JumpGet)."&P='+document.getElementById('QFramePageNum').value+'';
            }
		    </script>";
	    return '<ul class="pagination justify-content-center">'.$FirstPage . $PreStr . $Str . $NextStr . $ToallStr . $LastPage.'<li class="page-item  disabled mr-3"><a  class="page-link">总'.$Count.'条</a></li>'.$Jump.'</ul>';
	}
	
}