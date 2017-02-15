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
function db_config($key = null){
    $dbConfArr = array(
			'testq' => array(
				'host' => '127.0.0.1',
				'username' => 'root',
				'password' => '1234!@#$',
				'db_name' => 'jz',
				'db_driver' => 'mysql',
				'db_port' => '3306',
				'db_prefix' => 'jz_',
				'charset' => 'utf8')
	);
    return !isset($dbConfArr[$key]) ? false : $dbConfArr[$key];
}

function site_config(){
	return array('suffix' => '.html', 'default_controller' => 'index', 'default_function' => 'index', 'language' => 'en', 'url' => '/');
}
define('WEB_DOMAIN', 'www.qframework.com');
define('WEB_TITLE', 'Qesy Framework');
define('WEB_PREFIX', 'QCMS'); //-- 网站前缀，一般用户订单，可以显示给用户看的 --
define('WEB_KEY', '1234!@#$'); //-- 网站密钥，一般用于加密，不能想让用户知道 --
?>
