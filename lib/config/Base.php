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
abstract class Base{	
	private $_fileV = 5;
	public $pageNum = 20;
	public $temp_arr = array();
	public $cookieObj;
	function __construct(){
		$this->cookieObj = cookie::get_instance();
	}
	
	/*
	 * Name : 生成编号
	*/
	public function create_sn(){
		$order_sn = 'QCMS-'.uniqid(rand(100,999), false);
		return $order_sn;
	}
	/*
	 * Name : 加载模版
	 */
	public function load_view($temp, $data = array()){
		if(!is_file(BASEPATH.'view/'.$temp.EXT)) die(BASEPATH.'view/'.$temp.EXT.' not found !');
			$this->temp_arr = empty($data) ? $this->temp_arr : $data;
			foreach($this->temp_arr as $key => $val){
				$$key = $val;
			}
		require BASEPATH.'view/'.$temp.EXT;
	}
	
	/*
	 * name:前台封装模版调用方法
	 */
	public function view($temp){
		self::load_view('template/'.$this->web['tempname'].'/'.$temp);
	}
	/*
	 * Name : 加载CSS
	 */
	public function load_css($str){
		if(is_array($str)){
			foreach($str as $key=>$val){				
				echo '<link href="'.CSS_PATH.$val.'.css?v='.$this->_fileV.'" rel="stylesheet" type="text/css" />';
			}
		}else{			
			echo '<link href="'.CSS_PATH.$str.'.css?v='.$this->_fileV.'" rel="stylesheet" type="text/css" />';
		}
	}
	/*
	 * Name : 加载JS
	 */
	public function load_js($str){
		if(is_array($str)){
			foreach($str as $key=>$val){
				echo '<script type="text/javascript" src="'.JS_PATH.$val.'.js?v='.$this->_fileV.'" charset="utf-8"></script>';
			}
		}else{
			echo '<script type="text/javascript" src="'.JS_PATH.$str.'.js?v='.$this->_fileV.'" charset="utf-8"></script>';
		}
	}
	
	//-- 分页 --
	public function page_bar($count, $size, $url = '', $num = 9, $pageNum = '1'){
		$pageNum = empty($pageNum) ? 1 : $pageNum;
		$toall = ceil($count/$size);
		$toall_str = '';
		if($pageNum > $toall)$pageNum = $toall;
		$str = '';
		$pre = ($pageNum <= 1) ? '<li><a href="'.str_replace('{page}', 1, $url).'">上一页</a></li>' : '<li><a href="'.str_replace('{page}', $pageNum-1, $url).'">上一页</a></li>';
		$next = ($pageNum >= $toall) ? '<li><a href="'.str_replace('{page}', $toall, $url).'">下一页</a></li>' : '<li><a href="'.str_replace('{page}', $pageNum+1, $url).'">下一页</a></li>';
		if($toall <= $num){
			for($i=1;$i<=$toall;$i++){
				if($pageNum == $i){
					$str .= '<li class="active"><a>'.$i.'</a></li>';
				}else{
					$str .= '<li><a href="'.str_replace('{page}', $i, $url).'">'.$i.'</a></li>';
				}
			}
			if($toall<=1){
				return;
			}else{
				return '<ul class="pagination">'.$pre.$str.$next.$toall_str.'</ul>';
			}
		}
		if(($toall - $pageNum) > ceil($num/2) && $pageNum < ceil($num/2)){
			for($i=1;$i<=$num;$i++){
				if($pageNum == $i){
					$str .= '<li class="active"><a>'.$i.'</a></li>';
				}else{
					$str .= '<li><a href="'.str_replace('{page}', $i, $url).'">'.$i.'</a></li>';
				}
			}
	
			if($toall<=1){
				return;
			}else{
				return '<ul class="pagination">'.$pre.$str.$next.$toall_str.'</ul>';
			}
		}
		if(($toall - $pageNum) < ceil($num/2)){
			for($i = ($toall - $num + 1);$i <= $toall;$i++){
				if($pageNum == $i){
					$str .= '<li class="active"><a >'.$i.'</a></li>';
				}else{
					$str .= '<li><a href="'.str_replace('{page}', $i, $url).'" >'.$i.'</a></li>';
				}
			}
			return '<ul class="pagination">'.$pre.$str.$next.$toall_str.'</ul>';
		}
		for($i = ($pageNum -  floor($num/2));$i <= ($pageNum + floor($num/2));$i++){
			if($pageNum == $i){
				$str .= '<li class="active"><a>'.$i.'</a></li>';
			}else{
				$str .= '<li><a href="'.str_replace('{page}', $i, $url).'">'.$i.'</a></li>';
			}
		}
		if($toall<=1){
			return;
		}else{
			return '<ul class="pagination">'.$pre.$str.$next.$toall_str.'</ul>';
		}
	}
	
	/*
	 * Name : 回调函数
	 */
	public static function insert_func_array($controller_arr){
		$fun_arr = isset($controller_arr['fun_arr']) ? $controller_arr['fun_arr'] : array();
		$clss = new $controller_arr['name']();
		call_user_func_array(array(& $clss, $controller_arr['method'].'_Action'), $fun_arr);
	}
	
	function __autoload($class_name, $type = 'helper/', $dbname = 0) {
		if(file_exists(LIB.$type.$class_name.EXT)){
			require_once LIB.$type.$class_name.EXT;
			$objects_m[$class_name] = ($type == 'Model/') ? $class_name::get_instance($dbname) : new $class_name();//new $class_name();//
			//$objects_m[$class_name] = ($type == 'Model/') ? new $class_name($dbname) : new $class_name();//new $class_name();
			return $objects_m[$class_name];
		}else{
			return FALSE;
		}
	}
	
	
	public function s_send($address, $name, $title, $content, $replace_arr = array(), $type = 'default'){
		$temp = file_get_contents('static/mail/'.$type.'.html');
		$mailObj = $this->load_model('QCMS_Mail');
		$rs = $mailObj->selectOne();
		$mail = new PHPMailer();
		$mail->IsSMTP();					// 启用SMTP
		$mail->Host = $rs['smtp'];			//SMTP服务器
		$mail->SMTPAuth = true;				//开启SMTP认证
		$mail->Username = $rs['account'];		// SMTP用户名
		$mail->Password =  $rs['password'];			// SMTP密码
		$mail->From =  $rs['send'];			//发件人地址
		$mail->FromName = $rs['username'];		//发件人
		$mail->AddAddress($address, $name);
		$mail->AddReplyTo($mail->Username, $mail->FromName);	//回复地址
		$mail->WordWrap = 50;							//设置每行字符长度
		$mail->IsHTML(true);					// 是否HTML格式邮件
		$mail->CharSet = "utf-8";				// 这里指定字符集！
		$mail->Encoding = "base64";
	
		$search_sys = array('{title}', '{content}', '{date}', '{time}', '{webname}', '{host}', '{username}');
		$replace_sys = array($title, $content, date('Y-m-d'), date('Y-m-d H:i:s'), $this->web['webname'], 'http://'.WEB_DOMAIN, $name);
		foreach($replace_arr as $key => $val){
			$search_sys[] = '{'.$key.'}';
			$replace_sys[] = $val;
		}
		$str = str_replace($search_sys, $replace_sys, $temp);
		$mail->Subject = $title;			//邮件主题
		$mail->Body    = $str;				//邮件内容
		$mail->AltBody = $str;				//邮件正文不支持HTML的备用显示
		if(!$mail->Send()){
			return False;
		}
			return TRUE;

	}
}
?>