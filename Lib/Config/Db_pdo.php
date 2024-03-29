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
class Db_pdo extends Db {
    public $TableName;
    public $PrimaryKey;
	public static $_instance = array ();
	
	public static function get_instance() {
		$classFullName = get_called_class();
		if (! isset ( self::$_instance [$classFullName] ) ) {
			$instance = self::$_instance [$classFullName] = new static();
			return $instance;
		}
		return self::$_instance [$classFullName];
	}
	
	public function SetCond($Cond = array()) {
		$this->sqlSetArr ['Cond'] = $Cond;
		return $this;
	}
	public function SetUpdate($Update){
	    $this->sqlSetArr ['Update'] = $Update;
	    return $this;
	}
	public function SetField($Field) {
		$this->sqlSetArr ['Field'] = $Field;
		return $this;
	}
	public function SetTable(){
	    $this->sqlSetArr ['TbName'] = $this->TableName;
	    return $this;
	}	
	public function SetTbName($TbName) {
		$this->sqlSetArr ['TbName'] = $TbName;
		return $this;
	}
	public function SetIndex($Index) {
		$this->sqlSetArr ['Index'] = $Index;
		return $this;
	}
	public function SetLimit($Limit) {
		$this->sqlSetArr ['Limit'] = $Limit;
		return $this;
	}
	public function SetSort($Sort) {
		$this->sqlSetArr ['Sort'] = $Sort;
		return $this;
	}
	public function SetFetch($Fetch) {
		$this->sqlSetArr ['Fetch'] = $Fetch;
		return $this;
	}
	public function SetInsert($Insert){
		$this->sqlSetArr ['Insert'] = $Insert;
		return $this;
	}
	public function SetIsDebug($IsDebug) {
		$this->sqlSetArr ['IsDebug'] = $IsDebug;
		return $this;
	}
	public function ExecSelectOne() {
		return self::selectOne ( $this->sqlSetArr ['Cond'], $this->sqlSetArr ['Field'], $this->sqlSetArr ['TbName'], $this->sqlSetArr ['Index'], $this->sqlSetArr ['Limit'], $this->sqlSetArr ['Sort'], 1, $this->sqlSetArr ['IsDebug'] );
	}
	public function ExecSelectAll(&$count = 0) {
		return self::selectAll ( $this->sqlSetArr ['Cond'], $this->sqlSetArr ['Field'], $this->sqlSetArr ['TbName'], $this->sqlSetArr ['Index'], $this->sqlSetArr ['Limit'], $this->sqlSetArr ['Sort'], $this->sqlSetArr ['IsDebug'], $count );
	}
	public function ExecSelect() {
		return self::select ( $this->sqlSetArr ['Cond'], $this->sqlSetArr ['Field'], $this->sqlSetArr ['TbName'], $this->sqlSetArr ['Index'], $this->sqlSetArr ['Limit'], $this->sqlSetArr ['Sort'], 0, $this->sqlSetArr ['IsDebug'] );
	}
	public function ExecInsert() {
		return self::insert ( $this->sqlSetArr ['Insert'], $this->sqlSetArr ['TbName'], $this->sqlSetArr ['IsDebug'] );
	}
	public function ExecReplace() {
		return self::replace ( $this->sqlSetArr ['Insert'], $this->sqlSetArr ['TbName'], $this->sqlSetArr ['IsDebug'] );
	}
	public function ExecUpdate() {
		return self::update ( $this->sqlSetArr ['Update'], $this->sqlSetArr ['Cond'], $this->sqlSetArr ['TbName'], $this->sqlSetArr ['IsDebug'] );
	}
	public function ExecDelete() {
		return self::delete ( $this->sqlSetArr ['Cond'], $this->sqlSetArr ['TbName'], $this->sqlSetArr ['IsDebug'] );
	}
	public function selectOne($cond_arr = array(), $field = '*', $tb_name = '', $index = 0, $limit = '', $sort = '', $fetch = 0, $isDebug = 0) {
		return self::select ( $cond_arr, $field, $tb_name, $index, $limit, $sort, 1, $isDebug );
	}
    public function GetListByPage($Limit, $CondArr, $Sort, &$count){
	    return $this->SetTable()->SetCond($CondArr)->SetLimit($Limit)->SetSort($Sort)->ExecSelectAll($count);
	}
	public function select($cond_arr = array(), $field = '*', $tb_name = '', $index = 0, $limit = '', $sort = '', $fetch = 0, $isDebug = 0) {
	    $tb_name = empty ( $tb_name ) ? $this->TableName : $tb_name;
		$limit_str = ! is_array ( $limit ) ? $limit : ' limit ' . $limit [0] . ',' . $limit [1] . '';
		$sort_str = $this->sort ( $sort );
		if(is_array($cond_arr)){
		    foreach($cond_arr as $v){
		        if(is_array($v) && empty($v)) {
		            self::p_clean();
		            return array(); //空数组返回为空数组
		        }
		    }
		}
		$sql = "SELECT " . $field . " FROM " . $this->p_dbConfig ['Prefix'] . $tb_name . $this->get_sql_cond ( $cond_arr ) . $sort_str . $limit_str . "";
		$getArr = $this->get_execute_arr($cond_arr);		
		if($isDebug){
		    var_dump ( $sql );
		    var_dump($getArr);
		}
		if ($fetch == 1) {
			return $this->query ( $sql, $getArr, 1 );
		}
		if (empty ( $index )) {
			return $this->query ( $sql , $getArr);
		} else {
			return $this->set_index ( $this->query ( $sql , $getArr), $index );
		}
	}
	public function selectAll($cond_arr = '', $field = '*', $tb_name = '', $index = 0, $limit = '', $sort = '', $isDebug = 0, &$count) {
		$countRs = self::selectOne ( $cond_arr, 'COUNT(*) AS count', $tb_name, 0, '', '', 0, $isDebug );
		$count = $countRs ['count'];
		return self::select ( $cond_arr, $field, $tb_name, $index, $limit, $sort, 0, $isDebug );
	}
	public function insert($insert_arr = array(), $tb_name = 0, $isDebug = 0) {
		$tb_name = empty ( $tb_name ) ? $this->TableName : $tb_name;
		$value_str = parent::get_sql_insert ( $insert_arr );
		$sql = "INSERT INTO " . $this->p_dbConfig ['Prefix'] . $tb_name . $value_str . "";
		$getArr = $this->get_execute_arr($insert_arr);
		! $isDebug || var_dump ( $sql );
		return $this->exec ( $sql , $getArr);
	}
	public function insertBatch($insert_arr = array(), $tb_name = 0, $isDebug = 0){
		$tb_name = empty ( $tb_name ) ? $this->TableName : $tb_name;
		$keyStr =  implode(', ', array_keys($insert_arr[0]));
		$valStrArr = array();
		foreach($insert_arr as $k => $v){
			$valStrArr[] = "('".implode("', '", array_values($v))."')" ;
		}
		$sql = "INSERT INTO " . $tb_name . ' ('.$keyStr .') VALUES '. implode(',', $valStrArr);
		! $isDebug || var_dump ( $sql );
		return $this->exec ( $sql );
	}
	public function replace($insert_arr = array(), $tb_name = 0, $isDebug = 0) {
		$tb_name = empty ( $tb_name ) ? $this->TableName : $tb_name;
		$value_str = parent::get_sql_insert ( $insert_arr );
		$sql = "REPLACE INTO " . $this->p_dbConfig ['Prefix'] . $tb_name . $value_str . "";
		$getArr = $this->get_execute_arr($insert_arr);
		! $isDebug || var_dump ( $sql );
		return $this->exec ( $sql , $getArr);
	}
	public function update($update_arr = array(), $cond_arr = array(), $tb_name = 0, $isDebug = 0) {
	    if(is_array($cond_arr)){
	        foreach($cond_arr as $v){
	            if(is_array($v) && empty($v)) {
	                self::p_clean();
	                return 0; //空数组返回为空数组
	            }
	        }
	    }
		$tb_name = empty ( $tb_name ) ? $this->TableName : $tb_name;
		$update_str = parent::get_sql_update ( $update_arr );
		$cond_str = parent::get_sql_cond ( $cond_arr );
		$sql = "UPDATE " . $this->p_dbConfig ['Prefix'] . $tb_name . " SET " . $update_str . $cond_str . "";
		! $isDebug || var_dump ( $sql );
		$getUpdateArr = self::get_execute_arr($update_arr);
		$getCondArr = self::get_execute_arr($cond_arr);
		$getArr = array_merge($getUpdateArr, $getCondArr);
		return $this->exec($sql, $getArr);
	}
	public function delete($cond_arr = array(), $tb_name = 0, $isDebug = 0) {
	    if(is_array($cond_arr)){
	        foreach($cond_arr as $v){
	            if(is_array($v) && empty($v)) {
	                self::p_clean();
	                return 0; //空数组返回为空数组
	            }
	        }
	    }
	    $tb_name = empty ( $tb_name ) ? $this->TableName : $tb_name;
		$sql = "DELETE FROM " . $this->p_dbConfig ['Prefix'] . $tb_name . parent::get_sql_cond ( $cond_arr ) . "";
		! $isDebug || var_dump ( $sql );
		$getArr = $this->get_execute_arr($cond_arr);
		return $this->exec ( $sql, $getArr );
	}
}
?>