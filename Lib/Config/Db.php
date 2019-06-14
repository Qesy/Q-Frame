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
abstract class Db {
	public static $s_db_obj;
	protected $p_dbConfig;
	public $sqlSetArr = array (
			'Cond' => array (),
			'Insert' => array (),
			'Update' => array (),
			'Field' => '*',
			'TbName' => 0,
			'Index' => '',
			'Limit' => '',
			'Sort' => array (),
			'IsDebug' => 0 
	);
	
	/*
	 * Name : 构造函数
	 */
	public function __construct() {
		$this->p_dbConfig = db_config ();
		self::_get_db_config ();
	}
	/*
	 * Name : 析构函数
	 */
	public function __destruct() {
		self::$s_db_obj = null;
	}
	/*
	 * Name : 获取配置
	 */
	private function _get_db_config() {
		if (isset ( self::$s_db_obj )) {
			return self::$s_db_obj;
		}
		try {
			self::$s_db_obj  = new PDO ( 'mysql:dbname=' . $this->p_dbConfig ['Name'] . ';host=' . $this->p_dbConfig ['Host'] . '', $this->p_dbConfig ['Accounts'], $this->p_dbConfig ['Password'], array (
					PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION 
			) );
		} catch ( PDOException $e ) {
			echo 'Connection failed: ' . $e->getMessage ();
			exit ();
		}
		self::$s_db_obj ->exec ( "SET NAMES " . $this->p_dbConfig ['Charset'] );
	}
	
	/*
	 * Name : 查询
	 */
	public function query($sql, $fetch_mode = 0) {
		self::_clean ();
		$result = self::$s_db_obj ->query ( $sql );
		if ($result) {
			if (empty ( $fetch_mode )) {
				$rs = $result->fetchAll ( PDO::FETCH_ASSOC );
			} else {
				$rs = $result->fetch ( PDO::FETCH_ASSOC );
			}
		} else {
			$rs = array ();
		}
		return $rs;
	}
	/*
	 * Name : 获取插入ID
	 */
	public function last_insert_id() {
		return self::$s_db_obj->lastInsertId ();
	}
	/*
	 * Name : 执行
	 */
	public function exec($sql) {
		self::_clean ();
		return self::$s_db_obj ->exec ( $sql );
	}
	/*
	 * Name : 插入帮助
	 */
	public function get_sql_insert($insert_arr = array()) {
		$insert_arr_t = array ();
		$value_arr_t = array ();
		if (is_array ( $insert_arr )) {
			foreach ( $insert_arr as $key => $val ) {
				$insert_arr_t [] = $key;
				if (! get_magic_quotes_gpc ()) {
					$value_arr_t [] = '\'' . addslashes ( $val ) . '\'';
				} else {
					$value_arr_t [] = '\'' . $val . '\'';
				}
			}
			return " (" . implode ( ',', $insert_arr_t ) . ") values (" . implode ( ',', $value_arr_t ) . ")";
		}
	}
	/*
	 * Name : 条件帮助
	 */
	public function get_sql_cond($cond_arr = '') {
		if (! is_array ( $cond_arr )) {
			return $cond_arr;
		}
		$cond_arr_t = array ();
		foreach ( $cond_arr as $key => $val ) {
			if (is_array ( $val ) && empty ( $val )) {
				continue;
			}
			if (is_array ( $val )) {
				$cond_arr_t [] = $key . " in (" . self::get_sql_cond_by_in ( $val ) . ")";
			} else {
				if (! get_magic_quotes_gpc ()) {
					$cond_arr_t [] = $key . "='" . addslashes ( $val ) . "'";
				} else {
					$cond_arr_t [] = $key . "='" . $val . "'";
				}
			}
		}
		return empty ( $cond_arr_t ) ? '' : ' WHERE ' . implode ( ' && ', $cond_arr_t );
	}
	/*
	 * Name : IN辅助
	 */
	public function get_sql_cond_by_in($cond_arr) {
		$cond_arr_t = array ();
		foreach ( $cond_arr as $key => $val ) {
			if (! get_magic_quotes_gpc ()) {
				$cond_arr_t [] = '\'' . addslashes ( $val ) . '\'';
			} else {
				$cond_arr_t [] = '\'' . $val . '\'';
			}
		}
		return implode ( ',', $cond_arr_t );
	}
	/*
	 * Name : 修改帮助
	 */
	public function get_sql_update($update_arr = array()) {
		$update_arr_t = '';
		if (! is_array ( $update_arr )) {
			return $update_arr;
		}
		foreach ( $update_arr as $key => $val ) {
			if (! get_magic_quotes_gpc ()) {
				$update_arr_t [] = $key . " = '" . addslashes ( $val ) . "'";
			} else {
				$update_arr_t [] = $key . " = '" . $val . "'";
			}
		}
		return implode ( ',', $update_arr_t );
	}
	/*
	 * Name : 设置主键
	 */
	public static function set_index($arr, $key) {
		if (empty ( $arr ))
			return $arr;
		$temp = array ();
		foreach ( $arr as $val ) {
			if (! isset ( $val [$key] )) {
				return $arr;
			}
			$temp [$val [$key]] = $val;
		}
		return $temp;
	}
	/*
	 * Name : 排序帮助
	 */
	public static function sort($sort) {
		$sort_arr = '';
		if (empty ( $sort ))
			return '';
		if (is_array ( $sort )) {
			foreach ( $sort as $key => $val ) {
				$sort_arr [] = $key . ' ' . $val;
			}
			return ' ORDER BY ' . implode ( ',', $sort_arr );
		} else {
			return $sort;
		}
	}
	private function _clean() {
		$this->sqlSetArr = array (
				'Cond' => array (),
				'Insert' => array (),
				'Update' => array (),
				'Field' => '*',
				'TbName' => 0,
				'Index' => '',
				'Limit' => '',
				'Sort' => array (),
				'IsDebug' => 0 
		);
	}
}
?>