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
// error_reporting ( E_ALL ^ E_NOTICE );
define ( 'DIRNAME', pathinfo ( __FILE__, PATHINFO_DIRNAME ) );
define ( 'SYS_PATH', DIRNAME . '/system/' );
define ( 'LIB_PATH', DIRNAME . '/lib/' );
define ( 'STATIC_PATH', DIRNAME . '/static/' );
define ( 'SITE_PATH', substr ( $_SERVER ['SCRIPT_NAME'], 0, - 9 ) );
define ( 'SITE_URL', $_SERVER ['REDIRECT_URL'] );
define ( 'EXTEND', '.php' );
define ( 'IMG_PATH', SITE_PATH . '/static/images/' );
define ( 'JS_PATH', SITE_PATH . '/static/scripts/' );
define ( 'CSS_PATH', SITE_PATH . '/static/styles/' );
define ( 'CACHE_PATH', SITE_PATH . 'static/cache/' );
date_default_timezone_set ( 'Asia/Shanghai' );
require LIB_PATH . 'X' . EXTEND;
?>