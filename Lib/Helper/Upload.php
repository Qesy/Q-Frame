<?php

namespace Helper;

defined ( 'PATH_SYS' ) || exit ( 'No direct script access allowed' );
class Upload {
	private static $s_instance;
	private $_type = array (
			'jpg',
			'jpeg',
			'gif',
			'png',
			'webp',
			'bmp',
	);
	private $_size = 2; // -- m --
	private $_dir;
	private $_name;
	function __construct() {
		$this->_name = uniqid ( rand ( 100, 999 ) ) . rand ( 1, 9 );
		$this->_dir = 'Static/upload/source/' . date ( 'Ymd' ) . '/';
	}
	public static function get_instance() {
		if (! isset ( self::$s_instance )) {
			self::$s_instance = new self ();
		}
		return self::$s_instance;
	}
	public function upload_file($fileRs) {
		$ext = substr ( strrchr ( $fileRs ['name'], '.' ), 1 );

		if (! is_uploaded_file ( $fileRs ['tmp_name'] ) || ! in_array ( $ext, $this->_type )) {
			return - 1;
		}
		if ($fileRs ['size'] > ($this->_size * 1024 * 1024)) {
			return - 2;
		}
		return self::_move_file ( $fileRs ['tmp_name'], $ext );
	}
	private function _move_file($file, $ext) {
		$url = $this->_dir . $this->_name . '.' . $ext;
		if (! is_dir ( $this->_dir )) {
			mkdir ( $this->_dir, 0777 );
		}
		if (! move_uploaded_file ( $file, $url )) {
			return - 3;
		}
		return URL_ROOT . $url;
	}
}
?>