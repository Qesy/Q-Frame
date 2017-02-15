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
function db_config(){
	return array(
			'qcms' => array(
				'host' => '{host}',
				'username' => '{username}',
				'password' => '{password}',
				'db_name' => '{db_name}',
				'db_driver' => 'mysql',
				'db_port' => '3306',
				'db_prefix' => 'qcms_',
				'charset' => 'utf8')
	);
}

function site_config(){
	return array('suffix' => '.html', 'default_controller' => 'index', 'default_function' => 'index', 'language' => 'en', 'url' => '/');
}
define('WEB_DOMAIN', $_SERVER['HTTP_HOST']);
define('QCMS_TITLE', 'QCMS网站管理系统');
define('QCMS_URL', 'http://www.q-cms.cn');
$webServerArr = strtolower($_SERVER["SERVER_SOFTWARE"]);
$webServer = 'apache';
if(strpos($webServerArr, 'nginx')){
	$webServer = 'nginx';
}elseif(strpos($webServerArr, 'iis')){
	$webServer = 'iis';
}
define('SERVER_SOFT', $webServer);
?>
