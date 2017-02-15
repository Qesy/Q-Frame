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
class TANG_User extends Db_pdo
{
	protected $p_table_name = array('user');	
	private static $s_instance;
	
	public static function get_instance($dbname = 0){
		if (!isset(self::$s_instance[$dbname]) || self::$s_instance['key'] != $dbname)	{
			self::$s_instance[$dbname] = new self($dbname);
			self::$s_instance['key'] = $dbname;
		}
		return self::$s_instance[$dbname];
	}
	
	public function get_insert_id()	{
		return $this->last_insert_id();
	}
	
	public function select($cond_arr='', $field='*', $tb_name = 0,  $index = 0, $limit = '', $sort = '', $fetch = 0){
		return $this->exec_select($cond_arr, $field, $tb_name,  $index, $limit, $sort, $fetch);
	}
	
	public function selectOne($cond_arr='', $field='*', $tb_name = 0,  $index = 0, $limit = '', $sort = '', $fetch = 1){
		return $this->exec_select($cond_arr, $field, $tb_name,  $index, $limit, $sort, $fetch);
	}
	
	public function insert($insert_arr = array(), $tb_name = 0){
		return $this->exec_insert($insert_arr, $tb_name);
	}
	
	public function update($update_arr = array(), $cond_arr = array(), $tb_name = 0){
		return $this->exec_update($update_arr, $cond_arr, $tb_name);
	}
	
	public function delete($cond_arr = array(), $tb_name = 0){
		return $this->exec_del($cond_arr, $tb_name);
	}
	
	public function selectAll($limit = '', &$count, $cond_arr='', $sort = ''){
		$count = $this->exec_select($cond_arr, 'COUNT(*) AS count', 0,  0, '', '', 0);
		return $this->exec_select($cond_arr, '*', 0,  0, $limit, $sort, 0);
	}
}
?>