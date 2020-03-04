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
function db_config() {
	return array (
			'Host' => '127.0.0.1',  
			'Accounts' => 'root',
			'Password' => 'root',
			'Name' => 'qcms',
			'Port' => '3306',
			'Prefix' => 'qcms_',
			'Charset' => 'utf8' 
	);
}
function SiteConfig() {
	return array (
			'UrlType' => '0',
			'Extend' => '.html',
			'DefaultController' => 'index',
			'DefaultFunction' => 'index',
			'Language' => 'en',
			'Url' => '/' 
	);
}

function __autoload($classname) { // -- 自动加载类 --
    $filename = PATH_LIB . $classname . '.php';
    $filename = str_replace('\\', '/', $filename);
    if (file_exists ( $filename ))
        require $filename;
}

const WEB_DOMAIN = 'www.qframework.com';
const WEB_TITLE = 'Qesy Framework';
const WEB_PREFIX = 'QFrame';
const WEB_KEY = '1234!@#$';
const VERSION = '1.0.0';
?>
