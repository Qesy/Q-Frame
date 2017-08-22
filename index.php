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
define ( 'SITE_PATH', $_SERVER ['PATH_INFO']);
define ( 'SYS_PATH', DIRNAME . '/system/' );
define ( 'LIB_PATH', DIRNAME . '/lib/' );
define ( 'STATIC_PATH', DIRNAME . '/static/' );
define ( 'EXTEND', '.php' );
define ( 'IMG_PATH', SITE_PATH . 'static/images/' );
define ( 'JS_PATH', SITE_PATH . 'static/scripts/' );
define ( 'CSS_PATH', SITE_PATH . 'static/styles/' );
define ( 'CACHE_PATH', SITE_PATH . 'static/cache/' );
date_default_timezone_set ( 'Asia/Shanghai' );
require LIB_PATH . 'X' . EXTEND;
?>