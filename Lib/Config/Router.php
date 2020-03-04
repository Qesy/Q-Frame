<?php
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
class Router {
	private $_SiteConfig;
	private $_UrlConfig;
	private static $s_instance;
	public static $s_Controller;
	public static $s_Method;
	function __construct() {
		$this->_SiteConfig = SiteConfig ();
		$this->_UrlConfig = UrlConfig();
		self::ViewController ();
	}
	public static function get_instance() {
		if (! isset ( self::$s_instance )) self::$s_instance = new self ();
		return self::$s_instance;
	}
	private function _FetchUrl($Url) {
		$RouterArr = array ();	
		if (php_sapi_name () == 'cli') {
			$Url = implode ( '/', array_slice ( $_SERVER ['argv'], 1 ) );
		}
		if (strpos ( $Url, 'poweredByQesy' ) !== false) {
			echo "powered By Qesy <br>\n";
			echo "Email : 762264@qq.com <br>\n";
			echo "Version : QFrame v 1.0.0 <br>\n";
			echo "Your Ip : " . $_SERVER['REMOTE_ADDR'] . "<br>\n";
			echo "Date : " . date ( 'Y-m-d H:i:s' ) . "<br>\n";
			echo "UserAgent : " . $_SERVER ['HTTP_USER_AGENT'] . "<br>\n";
			exit ();
		}
		if ($Url == false) {			
			$RouterArr ['Name'] = $this->_SiteConfig ['DefaultController'];
			$RouterArr ['Url'] = PATH_SYS . 'Controller/' . $this->_SiteConfig ['DefaultController'] . EXTEND;
			$RouterArr ['Method'] = $this->_SiteConfig ['DefaultFunction'];
		} else {
			$UrlArr = explode ( $this->_SiteConfig ['Url'], $Url );
			$UrlTmp = '';
			foreach ( $UrlArr as $key => $val ) {
				$File = $UrlTmp . $val;
				$UrlTmp .= $val . $this->_SiteConfig ['Url'];
				if (file_exists ( PATH_SYS . 'Controller/' . $File . EXTEND )) {
					$RouterArr ['Name'] = $val;
					$RouterArr ['Url'] = PATH_SYS . 'Controller/' . $File . EXTEND;
					$FunUrl = substr ( $Url, strlen ( $File ) + 1 );
					$FunArr = explode ( $this->_SiteConfig ['Url'], $FunUrl );
					$RouterArr ['Method'] = empty ( $FunArr [0] ) ? 'index' : $FunArr [0];
					$RouterArr ['ParaArr'] = array_splice ( $FunArr, 1 );
					break;
				}
			}
		}
		return empty ( $RouterArr ) ? array (
				'Name' => 'home',
				'Url' => PATH_SYS . 'Controller/home.php',
				'Method' => 'err',
				'ParaArr' => array () 
		) : $RouterArr;
	}
	private function ViewController() {
		$Url = URL_CURRENT;
		$Extend = stripos($Url, $this->_SiteConfig['Extend']);
		if ($Extend) $Url = substr($Url, 0, $Extend);
		if($this->_SiteConfig['UrlType'] == 1) $Url = self::_UrlConvent($Url);
		$RouterArr = self::_FetchUrl ($Url);
		require $RouterArr ['Url'];
		if (! method_exists ( $RouterArr ['Name'], $RouterArr ['Method'] . '_Action' )) {
			$RouterArr = array (
					'Name' => 'home',
					'Url' => PATH_SYS . 'Controller/home.php',
					'Method' => 'err',
					'ParaArr' => array () 
			);
			require_once $RouterArr ['Url'];
		}
		self::$s_Controller = $RouterArr ['Name'];
		self::$s_Method = $RouterArr ['Method'];
		Base::InsertFuncArray ( $RouterArr );
	}
	
	private function _UrlConvent($url){
	    foreach($this->_UrlConfig as $v){
	        $v['search'] = '/^'.str_replace('/', '\/', $v['search']).'$/';
	        if(preg_match($v['search'],$url, $matches)){
	            $search = $replace = array();
	            foreach($matches as $sk => $sv){
	                if($sk == 0) continue;
	                $search[] = '{$'.$sk.'}';
	                $replace[] = $sv;
	            }
	            return str_replace($search, $replace, $v['action']);
	        }
	    }
	    return $url;
	}
}
?>