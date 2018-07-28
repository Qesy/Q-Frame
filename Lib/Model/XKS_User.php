<?php

namespace Model;

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
class XKS_User extends \Db_pdo {
	const TABLE_NAME = 'user';
	const PRIMARY_KEY = 'UserId';
	public $UserId;	

	
	public function GetList($IdArr){
		return $this->SetTbName(self::TABLE_NAME)->SetCond(array(self::PRIMARY_KEY => $IdArr))->ExecSelect();
	}
	
	public function GetListByKey($IdArr){
		return $this->SetTbName(self::TABLE_NAME)->SetCond(array(self::PRIMARY_KEY => $IdArr))->SetIndex(self::PRIMARY_KEY)->ExecSelect();
	}
	
	public function GetListByPage($Limit, $CondArr, $Sort, &$count){
		return $this->SetTbName(self::TABLE_NAME)->SetCond($CondArr)->SetLimit($Limit)->SetSort($Sort)->ExecSelectAll($count);
	}
	
	public function Get(){
		$rs = $this->SetTbName(self::TABLE_NAME)->SetCond(array(self::PRIMARY_KEY => $this->UserId))->ExecSelectOne();
		if(empty($rs)) return false;
		$this->UserId = $rs['UserId'];	
		return true;		
	}	
	
	public function Add(){
		return $this->SetTbName(self::TABLE_NAME)->SetIsDebug(0) ->SetInsert(array('NickName' => $this->NickName)->ExecInsert();
	}
	
	public function Edit(){
		return $this->SetTbName(self::TABLE_NAME)->SetCond(array(self::PRIMARY_KEY => $this->UserId))->SetUpdate(array('NickName' => $this->NickName))->ExecUpdate();
	}
}