<?php
namespace Helper; 
defined ( 'PATH_SYS' ) || exit ( 'No direct script access allowed' );
/*
 * Name : Collection
 * Date : 20120107
 * Author : Qesy
 * QQ : 762264
 * Mail : 762264@qq.com
 *
 * (̅_̅_̅(̲̅(̅_̅_̅_̅_̅_̅_̅_̅()ڪے
 *
 */
class Build {
	private static $s_instance;
	public $Arr;
	public $Html;
	public $Js;
	public $Module = 'admin';
	public $UploadUrl;
	public static function get_instance() {
		if (! isset ( self::$s_instance )) {
			self::$s_instance = new self ();
		}
		return self::$s_instance;
	}
	
	function __construct(){
	    $this->UploadUrl = url(array('backend', 'index', 'ajaxUpload'));
	}
	
	/*
	 * arr = array(
	 *     array('Name' =>'Name', 'Desc' => '用户名',  'Type' => 'input', 'Value' =>'Qesy', 'Placeholder' => '你网站的名称'),
	 *     array('Name' =>'Name', 'Desc' => '请选择用户', 'Type' => 'select', 'Value' =>'Qesy', 'Data' => 'a:1|b:2|c:3'),
	 *     array('Name' =>'Name', 'Desc' => '请选择用户', 'Type' => 'checkbox', 'Value' =>'Qesy', 'Data' => 'a:1|b:2|c:3'),
	 *     array('Name' =>'Name', 'Desc' => '请选择用户', 'Type' => 'upload', 'Value' =>'Qesy', 'Id' => 'Img'),
	 * );
	 */
	function Form(){
	    if(!is_array($this->Arr)) return;
	    self::_Clean();
	    $this->Html = '<form method="post">';
	    foreach($this->Arr as $k => $v){
	        $Disabled = ($v['Disabled'] == 1) ? 'disabled="disabled"' : '';
	           switch ($v['Type']){
	               case 'radio':
	                   $this->Html .= '<div class="checkbox"><span style="font-weight: 700; margin-right: 20px">'.$v['Desc'].'</span>';
	                   $dataArr = explode('|', $v['Data']);
	                   foreach($dataArr as $sk => $sv){
	                       $kv = explode(':', $sv);
	                       $this->Html .= '<label class="radio-inline "><input type="radio" name="'.$v['Name'].'"  value="'.$kv[0].'"> '.$kv[1].'" </label>';
	                   }
                    $this->Html .= '</div>';
                    break;
                    case 'checkbox':
                        $this->Html = '<div class="checkbox"><span style="font-weight: 700; margin-right: 20px">'.$v['Desc'].'</span>';
                        $dataArr = explode('|', $v['Data']);
                        foreach($dataArr as $sk => $sv){
                            $kv = explode(':', $sv);
                            $this->Html .= '<label class="checkbox-inline "><input type="checkbox" name="'.$v['Name'].'[]"  value="'.$kv[0].'"> '.$kv[1].'</label>';
                        }
                        $this->Html .= '</div>';
                        break;
	               case 'select':
	                   $this->Html .= '<div class="form-group"><label for="Input_'.$v['Name'].'">'.$v['Desc'].'</label><select class="form-control" name="'.$v['Name'].'" '.$Disabled.'>.';
                        //$dataArr = explode('|', $v['Data']);
                          foreach($v['Data'] as $sk => $sv){
                            //$kv = explode(':', $sv);
                            $selected = ($sk == $v['Value']) ? 'selected' : '';
                            $this->Html .= '<option value="'.$sk.'" '.$selected.'>'.$sv.'</option>';
                          }
                        $this->Html .= '</select></div>';
	                   break;
	               case 'upload':
	                   $this->Html .= '<div class="form-group">
                                    <label for="Input_'.$v['Name'].'">'.$v['Desc'].'</label>
                                    <div class="input-group">
                                      <input type="text" class="form-control" placeholder="'.$v['Placeholder'].'" name="'.$v['Name'].'" Id="Img_'.$v['Name'].'" value="'.$v['Value'].'">
                                      <span class="input-group-btn">
                                        <button class="btn btn-success" id="uploadImg_'.$v['Name'].'" type="button">上传图片</button>
                                      </span>
                                    </div>
                                  </div> ';
	                   $this->Js .= 'UploadBtn["'.$v['Name'].'"] = $("#uploadImg_'.$v['Name'].'");      
                            new AjaxUpload(UploadBtn["'.$v['Name'].'"], {
                              action: "'.$this->UploadUrl.'", 
                              name: "filedata",
                              onSubmit : function(file, ext){
                                this.disable();     
                              },      
                              onComplete: function(file, response){ 
                                var jsonArr = JSON.parse(response);
                                console.log(jsonArr.code)
                                if(jsonArr.code != 0){
                                  this.enable();
                                  alert(jsonArr.msg);return;
                                }
                                window.clearInterval(interval);
                                this.enable();      
                                $("#Img_'.$v['Name'].'").val(jsonArr.data.url)
                              }
                          });
	                       ';
	                   break;
	               case 'Slide':
	                   //var_dump($v['Value']);exit;
	                   foreach($v['Value'] as $sk => $sv){
	                       $this->Html .= '<div class="form-group">
                                    <label for="Input_'.$v['Name'].'">'.$v['Desc'].'</label>
                                    <div class="input-group">
                                      <input type="text" class="form-control" placeholder="'.$v['Placeholder'].'" name="'.$v['Name'].'[]" Id="Img_'.$v['Name'].'_'.$sk.'" value="'.$sv.'">
                                      <span class="input-group-btn">
                                        <button class="btn btn-success" id="uploadImg_'.$v['Name'].'_'.$sk.'" type="button">上传图片</button>
                                      </span>
                                    </div>
                                  </div> ';
	                       $this->Js .= 'UploadBtn["'.$v['Name'].'_'.$sk.'"] = $("#uploadImg_'.$v['Name'].'_'.$sk.'");
                            new AjaxUpload(UploadBtn["'.$v['Name'].'_'.$sk.'"], {
                              action: "'.$this->UploadUrl.'",
                              name: "filedata",
                              onSubmit : function(file, ext){
                                this.disable();
                              },
                              onComplete: function(file, response){
                                var jsonArr = JSON.parse(response);
                                console.log(jsonArr.code)
                                if(jsonArr.code != 0){
                                  this.enable();
                                  alert(jsonArr.msg);return;
                                }
                                window.clearInterval(interval);
                                this.enable();
                                $("#Img_'.$v['Name'].'_'.$sk.'").val(jsonArr.data.url)
                              }
                          });
	                       ';
	                   }
	                   break;
	               case 'textarea':
	                   $this->Html .= '<div class="form-group">
                                    <label for="Input_'.$v['Name'].'">'.$v['Desc'].'</label>
                                    <textarea class="form-control" name="Content" rows="16" placeholder="'.$v['Placeholder'].'">'.$v['Value'].'</textarea>
                                  </div>';
	                   break;
	                   case 'editor':
	                       $this->Html .= '<div class="form-group">
                                    <label for="Input_'.$v['Name'].'">'.$v['Desc'].'</label>
                                    <textarea class="form-control" name="Content" rows="16" placeholder="'.$v['Placeholder'].'">'.$v['Value'].'</textarea>
                                  </div>';
	                       $this->Js .= 'var editor;
    KindEditor.ready(function(K) {
      editor = K.create(\'textarea[name="Content"]\', {
        allowFileManager : true,
        themeType : "simple",
        urlType : "absolute",
        uploadJson : "'.$this->UploadUrl.'",
        items : ["source","code","fontname", "fontsize", "|", "forecolor", "hilitecolor", "bold", "italic", "underline",
"removeformat", "|", "justifyleft", "justifycenter", "justifyright", "insertorderedlist",
"insertunorderedlist", "|", "image", "flash", "media","insertfile","link","unlink","|","table","fullscreen"]

      })
    })';
	                       break;
	               case 'disabled':
	                   $this->Html .= '<div class="form-group">
                                        <label for="Input_'.$v['Name'].'">'.$v['Desc'].'</label>
                                        <input disabled="disabled" type="text" class="form-control" name="'.$v['Name'].'" id="Input_'.$v['Name'].'" placeholder="'.$v['Placeholder'].'" value="'.$v['Value'].'">
                                   </div>';
	                   break;
	               case 'money':
	                   case 'disabled':
	                       $this->Html .= '<div class="form-group">
                                        <label for="Input_'.$v['Name'].'">'.$v['Desc'].'</label>
                                            <div class="input-group mb-3">
  <div class="input-group-prepend">
    <span class="input-group-text">&yen;</span>
  </div>
  <input type="text" class="form-control" name="'.$v['Name'].'" id="Input_'.$v['Name'].'" placeholder="'.$v['Placeholder'].'" value="'.$v['Value'].'">
  <div class="input-group-append">
    <span class="input-group-text">.00</span>
  </div>
</div>
                                        
                                   </div>';
	                       break;
	               default:
	                   $this->Html .= '<div class="form-group">
                                        <label for="Input_'.$v['Name'].'">'.$v['Desc'].'</label>
                                        <input type="text" class="form-control" name="'.$v['Name'].'" id="Input_'.$v['Name'].'" placeholder="'.$v['Placeholder'].'" value="'.$v['Value'].'">
                                   </div>';
	                   break;
	           }
	       }
	       $this->Html .= '<button type="submit" class="btn btn-success">提交</button></form>';
	       if(!empty($this->Js)){
	           $this->Js =  '
	               var URL_ROOT = "'.URL_ROOT.'";
                   var UploadBtn = {}, interval;'.$this->Js;
	       }
	       $this->Arr = array();
	}
	

	
	/*
	 *  $keyArr = array('name' => ''标题'');
	 */
	public function Table(array $arr, $keyArr, $Page = '', $IsEdit = true, $IsDel = true){
	    $str = '<table class="table"><thead><tr>';
	    foreach($keyArr as $k => $v){
	        $str .= '<th  scope="col">'.$v['Name'].'</th>';
	    }
	    if($IsEdit || $IsDel){
	        $str .= '<th  scope="col">操作</th>';
	    }
	    $str .= '</tr></thead><tbody>';
	    foreach($arr as $k => $v){
	        $str .= '<tr>';
	        foreach($keyArr as $sk => $sv){
	            switch ($sv['Type']){
	                case 'Date':
	                    $str .= '<td>'.date('Y-m-d', $v[$sk]).'</td>';break;
                    case 'Time':
                        $str .= '<td>'.date('Y-m-d H:i:s', $v[$sk]).'</td>';break;
                    case 'True':
                        $IsTrue = ($v[$sk]) ? 'success' : 'danger';
                        $Text = ($v[$sk]) ? '是' : '否';
                        $str .= '<td><span class="text-'.$IsTrue.'">'.$Text.'</span></td>';break;
                    case 'Key':
                        $str .= '<td>'.$keyArr[$sk]['Data'][$v[$sk]].'</td>';break;
                        break;
	                default:
	                    $str .= '<td>'.$v[$sk].'</td>';break;
	            }
	            
	        }
	        if($IsEdit || $IsDel){
	            $ActArr = array();
	            if($IsEdit) $ActArr[] = '<a href="'.url(array($this->Module, \Router::$s_controller, 'edit')).'?Id='.$v['Id'].'">修改</a>';
	            if($IsDel) $ActArr[] = '<a href="'.url(array($this->Module, \Router::$s_controller, 'del')).'?Id='.$v['Id'].'">删除</a>';
	            $str .= '<td>'.implode(' ', $ActArr).'</td>';
	        }
	        $str .= '</tr>';
	    }
	    $num = count($keyArr);
	    if($IsEdit || $IsDel) $num++;
	    if(!empty($Page)){
	       $str .= '</tbody><tfoot><tr><td colspan="'.$num.'" class="page">'.$Page.'</td></tr></tfoot>';
	    }
	    $str .= '</table>';
	    return $str;
	}
	
	private function _Clean(){
	    $this->Html = '';
	    $this->Js = '';
	}
}