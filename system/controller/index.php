<?php
defined ( 'SYS_PATH' ) || exit ( 'No direct script access allowed' );
class Index extends Controllers {
	function __construct() {
		parent::__construct ();
	}
	public function index_Action() {
		$sgUserObj = SG_User::get_instance ();
		$goUserObj = GO_User::get_instance ( 1 );
		$a = $sgUserObj->exec_selectOne ();
		$b = $goUserObj->exec_selectOne ();
		$c = $sgUserObj->exec_selectOne ();
		var_dump ( $a, $b, $c );
	}
}
