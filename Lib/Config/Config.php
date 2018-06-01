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
function db_config($key) {
	$dbConfArr = array (
			array (
					'Host' => '127.0.0.1',  
					'Accounts' => 'root',
					'Password' => 'root',
					'Name' => 'weishop',
					'Port' => '3306',
					'Prefix' => 'ws_',
					'Charset' => 'utf8' 
			)
	);
	isset ( $dbConfArr [$key] ) || die ( 'No Db Config .' );
	return $dbConfArr [$key];
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

function url_config(){
	return array(
			array('search' =>'/admin/news' , 'action' => '/index/index'),			
	);
}
const WEB_DOMAIN = 'www.qframework.com';
const WEB_TITLE = 'Qesy Framework';
const WEB_PREFIX = 'QFrame';
const WEB_KEY = '1234!@#$';
const VERSION = '1.0.0';
?>
