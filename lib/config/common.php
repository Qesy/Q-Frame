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
/*
 * Name : 运行JS
 */
function exec_script($str){	
	echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><script>'.$str.'</script>';
}

function go_fun($urlArr, $str = '', $type = 0){
	if(!$type){
		exec_script('alert("'.$str.'");history.back();');exit;
	}else{
		exec_script('window.location.href="'.url($urlArr).'"');exit;
	}
}
/*
 * Name : 路径函数
 */
function url($url_arr = array('index')){
	$url = array();
	foreach ($url_arr as $key => $val){
		$url[] = $val;
	}
	return SITEPATH.implode('/', $url).'.html';
}
/*
 * Name : 信息函数
 */
function msg($str){
	echo '<font color="#ff0000"><b>'.ucfirst($str).'</b></font>';
	return;
}
/*
 * Name : 获取IP
 */
function ip(){
		if(!empty($_SERVER["HTTP_CLIENT_IP"])){
			$cip = $_SERVER["HTTP_CLIENT_IP"];
		}elseif(!empty($_SERVER["HTTP_X_FORWARDED_FOR"])){
			$cip = $_SERVER["HTTP_X_FORWARDED_FOR"];
		}else if(!empty($_SERVER["REMOTE_ADDR"])){
			$cip = $_SERVER["REMOTE_ADDR"];
		}else{
			$cip = 0;
		}	   
		return $cip;
}

function thumb($url, $width, $heiht, $noWaterMark = 0){
	$url = str_replace('source', 'thumb', $url);
	$ext = substr($url, -4);
	$path = substr($url, 0, -4);
	return empty($noWaterMark) ? $path.'_w'.$width.'_h'.$heiht.$ext : $path.'_w'.$width.'_h'.$heiht.'_'.$noWaterMark.$ext;
}

function api($pathArr, $paraArr){
	return;
	$token = 'young123';
	$secret = "8aa2a6ae0b2a0e6c9390b8e3f6626b47";
	$url = AD_URL.implode('/', $pathArr);
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Token:'.$token, 'Secret:'.$secret));
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $paraArr);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$result = curl_exec($ch);
	$info = curl_getinfo($ch);
	curl_close($ch);
	return json_decode($result, true);
}

function utf8Substr($str, $from, $len){
	return preg_replace('#^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$from.'}'.
			'((?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$len.'}).*#s',
			'$1',$str);
}

function __autoload($classname){ //-- 自动加载类 --
    $filename = LIB.'Model/'.$classname.'.php';
    if (file_exists($filename ))require_once $filename ;
    $filename = LIB.'helper/'.$classname.'.php';
    if (file_exists($filename ))require_once $filename ;
}
?>