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
class S_Cache {
	public $cached_path;
	private $_cache_open = 1;
	function __construct(){
		$this->cached_path = CACHE_PATH;		
	}
	
	public function set($key, $val)	{
		$val = empty($val) ? array() : $val;
		if(empty($this->_cache_open)){
			return $val;
		}
		if(!$result = fopen($this->cached_path.md5($key).'.cache', 'w')){
			return FALSE;
		}		
		$str = @serialize($val);
		if (!fwrite($result, $str)) {
	        return FALSE;
    	}		
		fclose($result);	
		return $val;	
	}
	
	public function get($key, $expires = 500){		
		if(empty($this->_cache_open)){
			return FALSE;
		}
		$filename = $this->cached_path.md5($key).'.cache';
		if(is_file($filename)){
			$cached_modtime = time()-filemtime($filename);
			if($cached_modtime < $expires){
				$date = @unserialize(file_get_contents($filename));
				return $date;
			}
		}
		return FALSE;
	}
	
	public function flush(){
		if (function_exists('exec')) {			
			if (strpos(strtoupper(PHP_OS),'WIN') !== false) {
				echo 'win';
				$cmd = 'del /s '.str_replace('/','\\',$this->cached_path).'*.cache';
			} else {				
				$cmd = 'rm -rf '.$this->cached_path.'/*.cache';
				echo '<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" /> ';
				echo '<h1 style="color:#f00">Notice : Flush OK !</h1>';
			}
			exec($cmd);
		} else {
			$d = dir($this->cached_path);
			while ($entry = $d->read()) {
				$modtime = date(time()-filemtime($this->cached_path.$entry));
				if (($entry != ".") && ($entry != "..") && ($entry != '.svn')) {
					chmod($this->cached_path.$entry,0777);
					unlink($this->cached_path.$entry);					
				}
			}
			echo '<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" /> ';
			echo '<h1 style="color:#f00">Notice : Flush OK !</h1>';
			$d->close();
		}
		return;
	}
} 
?>