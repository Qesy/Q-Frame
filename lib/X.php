<?php
defined ( 'SYS_PATH' ) || exit ( 'No direct script access allowed' );

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
require LIB_PATH . 'config/common' . EXTEND;
require LIB_PATH . 'config/Base' . EXTEND;
require LIB_PATH . 'config/Controllers' . EXTEND;
require LIB_PATH . 'config/Db' . EXTEND;
require LIB_PATH . 'config/Db_pdo' . EXTEND;
require LIB_PATH . 'config/config' . EXTEND;
require LIB_PATH . 'config/Router' . EXTEND;
session_set_cookie_params ( 24 * 3600 );
session_start ();
! version_compare ( "5.4", PHP_VERSION, ">" ) || die ( "PHP 5.4 or greater is required!!!" );
Router::get_instance ();
?>