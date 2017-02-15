<?php
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
error_reporting(E_ALL ^ E_NOTICE);

define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME)); //-- fileName --
define('PATH', pathinfo(__FILE__, PATHINFO_DIRNAME )); //-- PhysicalPath --
define('SITEPATH', str_replace(SELF, '', $_SERVER["PHP_SELF"])); //-- relativePath --
define('BASEPATH', PATH.'/system/'); //-- PhysicalPath --
define('EXT','.php');
define('LIB', PATH.'/lib/');
require_once LIB.'X'.EXT;
?>