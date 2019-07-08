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
	public $PrimaryKey = 'Id';
	public $IsAdd = true;
	public $IsEdit = true;
	public $IsDel = true;
	public $IsSubmit = true;
	public $LinkEdit;
	public $LinkDel;
	public $NameEdit;
	public $NameDel;
	public $UploadUrl;
	public $UploadEditUrl;
	public static function get_instance() {
		if (! isset ( self::$s_instance )) {
			self::$s_instance = new self ();
		}
		return self::$s_instance;
	}
	
	function __construct(){
	    $this->UploadUrl = url(array('backend', 'index', 'ajaxUpload'));
	    $this->UploadEditUrl = url(array('backend', 'index', 'uploadEditor'));
	   $this->NameEdit = '修改';
	   $this->NameDel = '删除';
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
	        $v['Placeholder'] = !isset($v['Placeholder']) ? '请输入'.$v['Desc'] : $v['Placeholder'];
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
                    $this->Html .= '<div class="form-group form-group row"><div class="col-sm-1">'.$v['Desc'].'</div><div class="col-sm-11">';
                    foreach($v['Data'] as $sk => $sv){
                        $sName = isset($v['DataKey']) ? $v['DataKey'][$sk] : $sk;
                        $Checked = ($sv == 1) ? 'checked="checked"' : '';
                        $this->Html .= '<div class="form-check form-check-inline mr-4"><label class="checkbox-inline "><input type="checkbox" name="'.$v['Name'].'['.$sk.']"  value="1" '.$Checked.' > '.$sName.'</label></div>';
                    }
                    $this->Html .= '</div></div>';
                    break;
	               case 'select':
	                   $this->Html .= '<div class="form-group"><label for="Input_'.$v['Name'].'">'.$v['Desc'].'</label><select class="form-control" name="'.$v['Name'].'" id="Input_'.$v['Name'].'" '.$Disabled.'>';
                          foreach($v['Data'] as $sk => $sv){
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
                            <textarea class="form-control" name="'.$v['Name'].'" rows="16" placeholder="'.$v['Placeholder'].'">'.$v['Value'].'</textarea>
                       </div>';
	                   break;
	                   case 'editor':
	                       $this->Html .= '<div class="form-group">
                                    <label for="Input_'.$v['Name'].'">'.$v['Desc'].'</label>
                                    <textarea class="form-control" name="'.$v['Name'].'" rows="16" placeholder="'.$v['Placeholder'].'">'.$v['Value'].'</textarea>
                                  </div>';
        	                       $this->Js .= 'var editor;
                                    KindEditor.ready(function(K) {
                                      editor = K.create(\'textarea[name="Content"]\', {
                                        allowFileManager : true,
                                        themeType : "simple",
                                        urlType : "absolute",
                                        uploadJson : "'.$this->UploadEditUrl.'",
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
	                       case 'date':
	                           $this->Html .= '<div class="form-group">
                                        <label for="Input_'.$v['Name'].'">'.$v['Desc'].'</label>
                                        <input type="date" class="form-control" name="'.$v['Name'].'" id="Input_'.$v['Name'].'" placeholder="'.$v['Placeholder'].'" value="'.$v['Value'].'">
                                   </div>';
	                           break;
	                       case 'password':
	                           $this->Html .= '<div class="form-group">
                                        <label for="Input_'.$v['Name'].'">'.$v['Desc'].'</label>
                                        <input type="password" '.$Disabled.' class="form-control" name="'.$v['Name'].'" id="Input_'.$v['Name'].'" placeholder="'.$v['Placeholder'].'" value="'.$v['Value'].'">
                                   </div>';
	                           break;
	               default:
	                   $this->Html .= '<div class="form-group">
                                        <label for="Input_'.$v['Name'].'">'.$v['Desc'].'</label>
                                        <input type="text" '.$Disabled.' class="form-control" name="'.$v['Name'].'" id="Input_'.$v['Name'].'" placeholder="'.$v['Placeholder'].'" value="'.$v['Value'].'">
                                   </div>';
	                   break;
	           }
	       }
	       if($this->IsSubmit) $this->Html .= '<button type="submit" class="btn btn-success">提交</button></form>';	       
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
	public function Table(array $arr, $keyArr, $Page = ''){
	    $this->LinkEdit = !empty($this->LinkEdit) ? $this->LinkEdit : url(array($this->Module, \Router::$s_controller, 'edit'));
	    $this->LinkDel = !empty($this->LinkDel) ? $this->LinkDel  :  url(array($this->Module, \Router::$s_controller, 'del'));
	    $str = '<table class="table"><thead><tr>';
	    foreach($keyArr as $k => $v){
	        $str .= '<th  scope="col">'.$v['Name'].'</th>';
	    }
	    if($this->IsEdit || $this->IsDel){
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
	        if($this->IsEdit || $this->IsDel){
	            $ActArr = array();
	            $_GET[$this->PrimaryKey] = $v[$this->PrimaryKey];
	            if($this->IsEdit) $ActArr[] = '<a href="'.$this->LinkEdit.'?'.http_build_query($_GET).'">'.$this->NameEdit.'</a>';
	            if($this->IsDel) $ActArr[] = '<a href="'.$this->LinkDel.'?'.http_build_query($_GET).'" onclick="return confirm(\'是否删除?\')">'.$this->NameDel.'</a>';
	            $str .= '<td>'.implode(' ', $ActArr).'</td>';
	            unset($_GET[$this->PrimaryKey]);
	        }
	        $str .= '</tr>';
	    }
	    $num = count($keyArr);
	    if($this->IsEdit || $this->IsDel) $num++;
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