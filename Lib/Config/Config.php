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
			'Name' => 'weishop',
			'Port' => '3306',
			'Prefix' => 'ws_',
			'Charset' => 'utf8' 
	);
}
function site_config() {
	return array (
			'UrlType' => '0',
			'Extend' => '.html',
			'DefaultController' => 'index',
			'DefaultFunction' => 'index',
			'Language' => 'en',
			'Url' => '/' 
	);
}

const WEB_DOMAIN = 'www.qframework.com';
const WEB_TITLE = 'Qesy Framework';
const WEB_PREFIX = 'QFrame';
const WEB_KEY = '1234!@#$';
const VERSION = '1.0.0';
?>
