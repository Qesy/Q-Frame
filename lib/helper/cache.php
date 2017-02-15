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
class cache {
	public $cached_time = 10800;
	function cache() {
		$query_str = preg_replace('/(&submit\.[x|y]=[0-9]+)+$/','',$_SERVER['REQUEST_URI']);
		$this->cached_file = md5($query_str).'.cache';
		$this->cached_path = CACHE_PATH;
		if (is_dir($this->cached_path)===false) {
			mkdir($this->cached_path,0777);
		}
		if (file_exists($this->cached_path.$this->cached_file)) {
			$this->cached_modtime = time()-filemtime($this->cached_path.$this->cached_file);
		}
	}
	//缓存开始
	function start() {
		global $HTTP_GET_VARS;
		if ( ($HTTP_GET_VARS['update']!="") || (!file_exists($this->cached_path.$this->cached_file)) || ($this->cached_modtime > $this->cached_time) ) {
			ob_start();
		} else {
			readfile($this->cached_path.$this->cached_file);
			exit();
		}
	}
	//缓存结束
	function end() {
		global $HTTP_GET_VARS;
		if ( ($HTTP_GET_VARS['update']!="") || (!file_exists($this->cached_path.$this->cached_file)) || ($this->cached_modtime > $this->cached_time) ) {
			$contents = ob_get_contents();
			ob_end_clean();
			$HTTP_GET_VARS['update']!="" ? chmod($this->cached_path.$this->cached_file,0777) : '';
			$fp = fopen($this->cached_path.$this->cached_file,'w');
			fputs($fp,$contents);
			fclose($fp);
			echo $contents;
		}
	}
	//清除缓存
	function cache_flush() {
		if (function_exists('exec')) {			
			if (strpos(strtoupper(PHP_OS),'WIN') !== false) {
				echo 'win';
				$cmd = 'del /s '.str_replace('/','\\',$this->cached_path).'*.cache';
			} else {				
				$cmd = 'rm -rf '.$this->cached_path.'/*.cache';
				echo '<font color=\'#0000FF\'><B>QCMS notice</B> : flush ok!</font>';
			}
			exec($cmd);
		} else {
			$d = dir($this->cached_path);
			while ($entry = $d->read()) {
				$modtime = date(time()-filemtime($this->cached_path.$entry));
				if (($entry != ".") && ($entry != "..")) {
					chmod($this->cached_path.$entry,0777);
					unlink($this->cached_path.$entry);
				}
			}
			$d->close();
		}
		return;
	}

} 
?>