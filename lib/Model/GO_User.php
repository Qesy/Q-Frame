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
class GO_User extends Db_pdo {
	protected $p_table_name = array (
			'user' 
	);
	private static $s_instance;
	public static function get_instance($dbname = 0) {
		if (! isset ( self::$s_instance [$dbname] ) || self::$s_instance ['key'] != $dbname) {
			self::$s_instance [$dbname] = new self ( $dbname );
			self::$s_instance ['key'] = $dbname;
		}
		return self::$s_instance [$dbname];
	}
}
?>