<?php
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

error_reporting ( E_ALL ^ E_NOTICE );
define ( 'DIRNAME', pathinfo ( __FILE__, PATHINFO_DIRNAME ) );
define ( 'SYS_PATH', DIRNAME . '/System/' );
define ( 'LIB_PATH', DIRNAME . '/Lib/' );
define ( 'STATIC_PATH', DIRNAME . '/Static/' );
define ( 'EXTEND', '.php' );
define ( 'SITE_PATH', explode('?', $_SERVER ['REQUEST_URI'])[0] ); 
define ( 'IMG_PATH', SITE_PATH . 'Static/images/' );
define ( 'JS_PATH', SITE_PATH . 'Static/scripts/' );
define ( 'CSS_PATH', SITE_PATH . 'Static/styles/' );
define ( 'CACHE_PATH', SITE_PATH . 'Static/cache/' );
require LIB_PATH . 'X' . EXTEND;  
?>