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
date_default_timezone_set('Asia/Shanghai');
define('IMG_PATH', '/static/images/');
define('JS_PATH', '/static/scripts/');
define('CSS_PATH', '/static/styles/');
define('CACHE_PATH', 'static/cache/');
require LIB.'config/common'.EXT;

require LIB.'config/Base'.EXT;
require LIB.'config/Controllers'.EXT;
require LIB.'config/Db'.EXT;
require LIB.'config/Db_pdo'.EXT;
require LIB.'config/config'.EXT;
require LIB.'config/Router'.EXT;
require LIB.'helper/PHPMailer'.EXT;
$lifeTime = 24 * 3600;
//ini_set('session.save_handler','redis'); //-- 启用redis --
//ini_set('session.save_path','tcp://127.0.0.1:6379');
session_set_cookie_params($lifeTime);
session_start();
Router::get_instance();
?>