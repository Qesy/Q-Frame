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
class Controllers extends Base{	
	public $userArr = array(
			'id' 		=> 	0, 
			'username' 	=> 	'', 
			'ad_id' 	=> 	0, 
			'email' 	=> 	'',
			'status'	=> 	0,
			'secret'	=>	'',
			'isold'		=> 	0,
			'inviteId' =>  0
	);
	protected $uploadObj;
	private $fileObj;
	function __construct(){
		parent::__construct();
		$this->userArr = array(
			'id' 		=> 	$this->cookieObj->get('userid'), 
			'username' 	=> 	$this->cookieObj->get('username'), 
			'nickname'	=>  $this->cookieObj->get('nickname'), 
			'email' 	=> 	$this->cookieObj->get('email'),
			'status'	=> 	$this->cookieObj->get('static'),
			'secret'	=>	$this->cookieObj->get('secret'),
			'history'	=>	$this->cookieObj->get('history'),
			'isold'		=>	$this->cookieObj->get('isold'),
			'inviteId'		=>	$this->cookieObj->get('inviteId'),
		);
	}
	
	public function isLogin(){
		if(empty($this->userArr['id'])){
			exec_script('window.location.href="'.url(array('index', 'login')).'"');exit;
		}
		if($this->userArr['secret'] != md5(SITE_KEY.$this->userArr['username'].$this->userArr['email'].date('Ym'))){
			exec_script('window.location.href="'.url(array('index', 'login')).'"');exit;
		}
	}
	
	public function adminLogin($username, $password){
		$adminObj = $this->load_model('QCMS_Admin');
		$rs = $adminObj->selectOne(array('admin' => $username, 'pwd' => md5(md5($password).SITE_KEY)));
		if(empty($rs)) return false;
		$this->cookieObj->set(array('admin_id' => $rs['id'], 'admin_level' => $rs['level'], 'admin_name' => $rs['admin'], 'admin_secret' => md5($rs['id'].$rs['admin'].date('Y-m').SITE_KEY)));

		return true;
	}
	

	
}


class ControllersAdmin extends Base{
	public $id;
	public $name;
	public $secret;
	public $level;
	public $key;
	
	function __construct(){
		parent::__construct();
		$this->id 			= 	$this->cookieObj->get('admin_id');
		$this->name 	= 	$this->cookieObj->get('admin_name');
		$this->level 		= 	$this->cookieObj->get('admin_level');
		$this->secret		= 	$this->cookieObj->get('admin_secret');
		$this->uploadObj = $this->load_class('upload');
		$this->key = base64_encode($this->id.'|'.$this->secret.'|'.WEB_DOMAIN);
		if(empty($this->id) || ($this->secret != md5($this->id.$this->name.date('Y-m').SITE_KEY))){
			exec_script('window.location.href="'.url(array('admin')).'"');exit;
		}
	}
	
	public function cateHtml($cateArr = array(), $html = "&nbsp;&nbsp;├ &nbsp;&nbsp;", $id = 0){
		foreach($cateArr as $k => $v){
			$selected = ($v['id'] == $id) ? 'selected' : '';
			echo '<option value="'.$v['id'].'" '.$selected.'>'.$html.$v['name'].'</option>';
			if(!empty($v['sub'])){
				$subHtml = '&nbsp;&nbsp;&nbsp;&nbsp;'.$html;
				self::cateHtml($v['sub'], $subHtml, $id);
			}
		}
	}
	
	public function locationData($newsId = 0, $locatPost = array(), $module_id = 1){
		$locatObj = $this->load_model('QCMS_Location');
		$temp['locatRs'] = $locatObj->select(array('module_id' => $module_id));
		foreach ($temp['locatRs'] as $k => $v){
			if(in_array($v['id'], $locatPost)){
				$locatResult = $locatObj->selectOne(array('location_id' => $v['id'], 'news_id' => $newsId, 'module_id' => $module_id), '*', 1);
				if(empty($locatResult)){
					$locatObj->insert(array('location_id' => $v['id'], 'news_id' => $newsId, 'module_id' => $module_id), 1);
				}
			}else{
				$locatResult = $locatObj->selectOne(array('location_id' => $v['id'], 'news_id' => $newsId, 'module_id' => $module_id), '*', 1);
				if(!empty($locatResult)){
					$locatObj->delete(array('location_id' => $v['id'], 'news_id' => $newsId, 'module_id' => $module_id), 1);
				}
			}
				
		}
	}
	
	public function upload($file_arr = array()){
		$this->_files = $this->load_model('QCMS_Files');
		$uploadObj = $this->load_class('upload');
		$pic = file_get_contents($file_arr['tmp_name']);
		$hash = hash('sha1', $pic);
		$rs = $this->_files->selectOne(array('hash' => $hash));
		if(!empty($rs)){
			$result = $rs['path'];
		}else{
			$result = $uploadObj->upload_file($file_arr);
			if($result < 0){
			}else{
				$this->_files->insert(array(
						'filename' 	=> 	$file_arr['name'],
						'path'		=>	$result,
						'mimetype' 	=> 	$file_arr['type'],
						'ext' 		=> 	pathinfo($file_arr['name'], PATHINFO_EXTENSION),
						'size'		=> 	$file_arr['size'],
						'user_id'	=>	$this->id,
						'addtime'	=>	time(),
						'hash'		=>	$hash,
				));
			}
		}
		return $result;
	}
}
?>