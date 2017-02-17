<?php
if (! defined ( 'SYS_PATH' ))
	exit ( 'No direct script access allowed' );
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
function exec_script($str) { // -- 运行JS --
	echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><script>' . $str . '</script>';
}
function go_fun(array $urlArr) { // -- JS跳转 --
	exec_script ( 'window.location.href="' . url ( $urlArr ) . '"' );
	exit ();
}
function url(array $urlArr = array ('index')) { // -- 路径函数 --
	$url = array ();
	foreach ( $urlArr as $key => $val ) {
		$url [] = $val;
	}
	return SITE_PATH . implode ( '/', $url ) . '.html';
}
function ip() { // -- 获取IP --
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
function thumb($url, $width, $heiht, $noWaterMark = 0) { // -- 缩略图 --
	$url = str_replace ( 'source', 'thumb', $url );
	$ext = substr ( $url, - 4 );
	$path = substr ( $url, 0, - 4 );
	return empty ( $noWaterMark ) ? $path . '_w' . $width . '_h' . $heiht . $ext : $path . '_w' . $width . '_h' . $heiht . '_' . $noWaterMark . $ext;
}
function api(array $pathArr, array $paraArr, $url) { // -- CURL封装 --
	$url = AD_URL . implode ( '/', $pathArr );
	$ch = curl_init ( $url );
	curl_setopt ( $ch, CURLOPT_POST, true );
	curl_setopt ( $ch, CURLOPT_POSTFIELDS, $paraArr );
	curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
	$result = curl_exec ( $ch );
	$info = curl_getinfo ( $ch );
	curl_close ( $ch );
	return $result;
}
function utf8Substr($str, $from, $len) { // -- 切utf8字符串 --
	return preg_replace ( '#^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,' . $from . '}' . '((?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,' . $len . '}).*#s', '$1', $str );
}
function __autoload($classname) { // -- 自动加载类 --
	$filename = LIB_PATH . $classname . '.php';
	if (file_exists ( $filename ))
		require $filename;
}
?>