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
abstract class Db {
	protected static $p_db_obj;
	protected $p_dbName;
	protected $p_dbConfig;
	
	/*
	 * Name : 构造函数
	 */
	public function __construct($dbname = 0) {
		$this->p_dbName = $dbname;
		$this->p_dbConfig = db_config ( $this->p_dbName );
		self::_get_db_config ();
	}
	/*
	 * Name : 析构函数
	 */
	public function __destruct() {
		self::$p_db_obj = null;
	}
	/*
	 * Name : 获取配置
	 */
	private function _get_db_config() {
		if (isset ( self::$p_db_obj [$this->p_dbName] )) {
			return self::$p_db_obj [$this->p_dbName];
		}
		try {
			self::$p_db_obj [$this->p_dbName] = new PDO ( 'mysql:dbname=' . $this->p_dbConfig ['Name'] . ';host=' . $this->p_dbConfig ['Host'] . '', $this->p_dbConfig ['Accounts'], $this->p_dbConfig ['Password'], array (
					PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION 
			) );
		} catch ( PDOException $e ) {
			echo 'Connection failed: ' . $e->getMessage ();
			exit ();
		}
		self::$p_db_obj [$this->p_dbName]->exec ( "SET NAMES " . $this->p_dbConfig ['Charset'] );
	}
	
	/*
	 * Name : 查询
	 */
	public function q_select($sql, $fetch_mode = 0) {
		$result = self::$p_db_obj [$this->p_dbName]->query ( $sql );
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
		return self::$p_db_obj [$this->p_dbName]->lastInsertId ();
	}
	/*
	 * Name : 执行
	 */
	public function q_exec($sql) {
		return self::$p_db_obj [$this->p_dbName]->exec ( $sql );
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
			return;
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
}
?>