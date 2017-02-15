<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Home extends Controllers{
	public function err_Action(){
		header("HTTP/1.1 404 Not Found");
		echo '404 error !';
	}
	
	public function err(){
		header("HTTP/1.1 404 Not Found");
		echo '404 error !!';
	}
}