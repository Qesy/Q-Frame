<?php
defined ( 'PATH_SYS' ) || exit ( 'No direct script access allowed' );
class Index extends Controllers {

	public function index_Action() {
		echo '<br><br><br><h1><center>QFrame PHP Version 1.0.0 </center></h1><center><h2>Author : Qesy, Email : 762264@qq.com</h2><p>Your IP : ' . $this->CommonObj->ip () . '</p></center>';
	}
	
	public function test_Action(){
	    var_dump($this->CommonObj->GetQuery());
	    $this->CommonObj->SetQuery('aa', 'bb');
	    var_dump($this->CommonObj->GetQuery());
		echo 'test';
	}
}
