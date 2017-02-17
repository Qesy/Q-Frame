<?php

namespace Helper;

defined ( 'SYS_PATH' ) || exit ( 'No direct script access allowed' );
class upload {
	private static $s_instance;
	private $_type = array (
			'image/pjpeg',
			'image/jpeg',
			'image/gif',
			'image/png',
			'image/x-png',
			'image/bmp',
			'application/x-shockwave-flash',
			'application/octet-stream',
			'image/vnd.adobe.photoshop' 
	);
	private $_size = 2; // -- m --
	private $_dir;
	private $_name;
	function __construct() {
		$this->_name = uniqid ( rand ( 100, 999 ) ) . rand ( 1, 9 );
		$this->_dir = 'static/upload/source/' . date ( 'Ymd' ) . '/';
	}
	public static function get_instance() {
		if (! isset ( self::$s_instance )) {
			self::$s_instance = new self ();
		}
		return self::$s_instance;
	}
	public function upload_file($file_arr) {
		$ext = substr ( strrchr ( $file_arr ['name'], '.' ), 1 );
		if (! is_uploaded_file ( $file_arr ['tmp_name'] ) || ! in_array ( $file_arr ['type'], $this->_type )) {
			return - 1;
		}
		if ($file_arr ['size'] > ($this->_size * 1024 * 1024)) {
			return - 2;
		}
		return self::_move_file ( $file_arr ['tmp_name'], $ext );
	}
	private function _move_file($file, $ext) {
		$url = $this->_dir . $this->_name . '.' . $ext;
		if (! is_dir ( $this->_dir )) {
			mkdir ( $this->_dir, 0777 );
		}
		if (! move_uploaded_file ( $file, $url )) {
			return - 3;
		}
		return '/' . $url;
	}
}
?>