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
require PATH_LIB . 'Config/Common' . EXTEND;
require PATH_LIB . 'Config/Base' . EXTEND;
require PATH_LIB . 'Config/Controllers' . EXTEND;
require PATH_LIB . 'Config/Db' . EXTEND;
require PATH_LIB . 'Config/Db_pdo' . EXTEND;
require PATH_LIB . 'Config/Config' . EXTEND;
require PATH_LIB . 'Config/Router' . EXTEND;
date_default_timezone_set ( 'Asia/Shanghai' );
header("Server: QCMS", true);
session_set_cookie_params ( 24 * 3600 );
session_start ();
!version_compare ( "5.4", PHP_VERSION, ">" ) || die ( "PHP 5.4 or greater is required!!!" );
Router::get_instance ();
?>