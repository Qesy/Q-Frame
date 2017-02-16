<?php
defined ( 'SYS_PATH' ) || exit ( 'No direct script access allowed' );
class Index extends Controllers {
	function __construct() {
		parent::__construct ();
	}
	public function index_Action() {
		$sgUserObj = SG_User::get_instance ();
		$goUserObj = GO_User::get_instance ( 1 );
		/*
		 * $a = $sgUserObj->selectOne ();
		 * $b = $goUserObj->selectOne ();
		 * $c = $sgUserObj->selectOne ();
		 * $d = $goUserObj->selectOne ();
		 */
		
		$a = $sgUserObj->ExecSelectOne ();
		$b = $goUserObj->ExecSelectOne ();
		$c = $sgUserObj->ExecSelectOne ();
		$d = $goUserObj->ExecSelectOne ();
		var_dump ( $a, $b, $c, $d );
	}
}
