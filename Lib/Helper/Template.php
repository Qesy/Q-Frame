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
class Template {
    
    public $TempArr = array ();
	private static $s_instance;
	public $Ret = array('Code' => 0, 'Data' => array(), 'Msg' => '');
	
	public static function get_instance() {
		if (! isset ( self::$s_instance )) {
			self::$s_instance = new self ();
		}
		return self::$s_instance;
	}
	
	public function LoadView($Temp, $Data = array()) { // -- Name : 加载模版 --
	    if (! is_file ( PATH_SYS . 'View/' . $Temp . EXTEND )) die ( PATH_SYS . 'View/' . $Temp . EXTEND . ' not found !' );
	    	
	    $this->TempArr = empty ( $Data ) ? $this->TempArr : $Data;
	    foreach ( $this->TempArr as $Key => $Val ) $$Key = $Val;
	    require PATH_SYS . 'View/' . $Temp . EXTEND;
	}
	public function LoadCss(array $CssArr) { // -- Name : 加载CSS --
	    foreach ( $CssArr as $Val ) echo "<link href=\"" . URL_CSS . $Val . ".css?v=". VERSION ."\" rel=\"stylesheet\" type=\"text/css\" />\n";
	}
	public function loadScripts(array $jsArr) { // -- Name : 加载JS --
	    foreach ( $jsArr as $key => $val ) echo "<script type=\"text/javascript\" src=\"" . URL_JS . $val .".js?v=" . VERSION . "\" charset=\"utf-8\"></script>";
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