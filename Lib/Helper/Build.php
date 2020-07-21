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
	public $Arr = array();
	public $Html;
	public $Js;
	public $Module = 'admin';
	public $PrimaryKey = 'Id';
	public $IsAdd = true;
	public $IsEdit = true;
	public $IsDel = true;
	public $IsSubmit = true;
	public $LinkIndex;
	public $LinkExport;
	public $LinkAdd;
	public $LinkEdit;
	public $LinkDel;
	public $NameEdit = '修改';
	public $NameDel = '删除';
	public $UploadUrl;
	public $UploadEditUrl;
	public $CommonObj;
	public static function get_instance() {
		if (! isset ( self::$s_instance )) {
			self::$s_instance = new self ();
		}
		return self::$s_instance;
	}
	
	function __construct(){
	    $this->CommonObj = Common::get_instance();
	}
	
	public function Form($Method = 'POST', $Class = '', $ExtHtml = ''){
	    if(!is_array($this->Arr)) return;
	    $this->UploadUrl = !empty($this->UploadUrl) ? $this->UploadUrl : $this->CommonObj->Url(array('backend', 'index', 'ajaxUpload'));
	    $this->UploadEditUrl = !empty($this->UploadEditUrl) ? $this->UploadEditUrl : $this->CommonObj->Url(array('backend', 'index', 'uploadEditor'));
	    $this->LinkIndex = !empty($this->LinkIndex) ? $this->LinkIndex : $this->CommonObj->Url(array($this->Module, \Router::$s_controller, 'index'));
	    $this->LinkExport = !empty($this->LinkExport) ? $this->LinkExport : $this->CommonObj->Url(array($this->Module, \Router::$s_controller, 'export'));
	    self::_Clean();
	    $this->Html = '<form method="'.$Method.'" class="BuildForm '.$Class.'">';
	    foreach($this->Arr as $k => $v){
	           if(empty($v['Col']) && $Class != 'form-inline') $v['Col'] = 12;
	           switch ($v['Type']){
	               case 'formgroup':
	                   $this->Html .= self::_FromGroup($v['Col'], $v['Desc']); break;
	               case 'radio':
	                   $this->Html .= self::_FormRadio($v['Name'], $v['Desc'], $v['Value'], $v['Data'], $v['Col'], $v['Disabled']); break;
                    case 'checkbox':
                        $this->Html .= self::_FormCheckbox($v['Name'], $v['Desc'], $v['Value'], $v['Data'], $v['Col'], $v['Disabled']); break;
	               case 'select':
	                   $this->Html .= self::_FromSelect($v['Name'], $v['Desc'], $v['Value'], $v['Col'], $v['Data'], $v['Disabled']); break;
	               case 'upload':
	                   list($StrHtml, $StrJs) = self::_FormUpload($v['Name'], $v['Desc'], $v['Value'], $v['Col'], $v['Disabled'], $v['Placeholder']); 
	                   $this->Html .= $StrHtml;
	                   $this->Js .= $StrJs;
	                   break;
	               case 'slide':
	                   list($StrHtml, $StrJs) = self::_FormSlide($v['Name'], $v['Desc'], $v['Value'], $v['Col'], $v['Disabled'], $v['Placeholder']); 
	                   $this->Html .= $StrHtml;
	                   $this->Js .= $StrJs;
	                   break;
	               case 'textarea':
	                   $this->Html .= self::_FormTextarea($v['Name'], $v['Desc'], $v['Value'], $v['Col'], $v['Disabled'], $v['Placeholder']); break;
                   case 'editor':
                       list($StrHtml, $StrJs) = self::_FormEditor($v['Name'], $v['Desc'], $v['Value'], $v['Col'], $v['Disabled'], $v['Placeholder']); 
                       $this->Html .= $StrHtml;
                       $this->Js .= $StrJs;
                       break;
	               case 'money':
	                       $this->Html .= self::_FromMoney($v['Name'], $v['Desc'], $v['Value'], $v['Col'], $v['Disabled'], $v['Placeholder']); break;	                       
                   case 'date':
                       $this->Html .= self::_FromInput($v['Name'], $v['Desc'], $v['Value'], $v['Col'], 'date', $v['Disabled'], $v['Placeholder']); break;
                   case 'time':
                       $this->Html .= self::_FromInput($v['Name'], $v['Desc'], $v['Value'], $v['Col'], 'time', $v['Disabled'], $v['Placeholder']); break;
                   case 'password':
                       $this->Html .= self::_FromInput($v['Name'], $v['Desc'], $v['Value'], $v['Col'], 'password', $v['Disabled'], $v['Placeholder']); break;
                   case 'button':
                       if(empty($v['ButtonType'])) $v['ButtonType'] = 'submit';
                       if(empty($v['Class'])) $v['Class'] = 'primary';
                       $this->Html .= self::_FromButton($v['Name'], $v['Desc'], $v['ButtonType'], $v['Col'], $v['Class']);break;
                   case 'link':
                       if(empty($v['Class'])) $v['Class'] = 'primary';
                       $this->Html .= self::_FromLink($v['Name'], $v['Desc'], $v['Value'], $v['Col'], $v['Data'], $v['Class']);break;
                   case 'html':
                       if(empty($v['Class'])) $v['Class'] = 'primary';
                       $this->Html .= self::_html($v['Name'], $v['Desc'], $v['Value'], $v['Col'], $v['Data'], $v['Class']);break;
                   case 'htmlStart':
                       $this->Html .= self::_htmlStart($v['Name'], $v['Col']);break;
                   case 'htmlEnd':
                       $this->Html .= self::_htmlEnd();break;
                   case 'hidden':
                       if(empty($v['Class'])) $v['Class'] = 'primary';
                       $this->Html .= self::_Hidden($v['Name'], $v['Desc'], $v['Value'], $v['Col'], $v['Data'], $v['Class']);break;
	               default:
	                   $this->Html .= self::_FromInput($v['Name'], $v['Desc'], $v['Value'], $v['Col'], 'text', $v['Disabled'], $v['Placeholder']); break;
	           }
	       }
	       if($Class != 'form-inline') $Col = 12;
	       if($this->IsSubmit) $this->Html .= self::_FromButton('submit', '提交', $Col, 'primary');
	       $this->Html .= $ExtHtml.'</form>';
	       if(!empty($this->Js)){
	           $this->Js =  '
	               var URL_ROOT = "'.URL_ROOT.'";
                   var UploadBtn = {}, interval;'.$this->Js;
	       }
	}
	
	public function FormOne($v){
	    $Html = $Js = '';
	    switch ($v['Type']){
	        case 'formgroup':
	            $Html = self::_FromGroup($v['Col'], $v['Desc']); break;
	        case 'radio':
	            $Html = self::_FormRadio($v['Name'], $v['Desc'], $v['Value'], $v['Data'], $v['Col'], $v['Disabled']); break;
	        case 'checkbox':
	            $Html = self::_FormCheckbox($v['Name'], $v['Desc'], $v['Value'], $v['Data'], $v['Col'], $v['Disabled']); break;
	        case 'select':
	            $Html = self::_FromSelect($v['Name'], $v['Desc'], $v['Value'], $v['Col'], $v['Data'], $v['Disabled']); break;
	        case 'upload':
	            list($StrHtml, $StrJs) = self::_FormUpload($v['Name'], $v['Desc'], $v['Value'], $v['Col'], $v['Disabled'], $v['Placeholder']);
	            $Html = $StrHtml;
	            $Js = $StrJs;
	            break;
	        case 'slide':
	            list($StrHtml, $StrJs) = self::_FormSlide($v['Name'], $v['Desc'], $v['Value'], $v['Col'], $v['Disabled'], $v['Placeholder']);
	            $Html = $StrHtml;
	            $Js = $StrJs;
	            break;
	        case 'textarea':
	            $Html = self::_FormTextarea($v['Name'], $v['Desc'], $v['Value'], $v['Col'], $v['Disabled'], $v['Placeholder']); break;
	        case 'editor':
	            list($StrHtml, $StrJs) = self::_FormEditor($v['Name'], $v['Desc'], $v['Value'], $v['Col'], $v['Disabled'], $v['Placeholder']);
	            $Html = $StrHtml;
	            $Js = $StrJs;
	            break;
	        case 'money':
	            $Html = self::_FromMoney($v['Name'], $v['Desc'], $v['Value'], $v['Col'], $v['Disabled'], $v['Placeholder']); break;
	        case 'date':
	            $Html = self::_FromInput($v['Name'], $v['Desc'], $v['Value'], $v['Col'], 'date', $v['Disabled'], $v['Placeholder']); break;
	        case 'password':
	            $Html = self::_FromInput($v['Name'], $v['Desc'], $v['Value'], $v['Col'], 'password', $v['Disabled'], $v['Placeholder']); break;
	        case 'button':
	            if(empty($v['ButtonType'])) $v['ButtonType'] = 'submit';
	            if(empty($v['Class'])) $v['Class'] = 'primary';
	            $Html = self::_FromButton($v['Name'], $v['Desc'], $v['ButtonType'], $v['Col'], $v['Class']);break;
	        case 'link':
	            if(empty($v['Class'])) $v['Class'] = 'primary';
	            $Html = self::_FromLink($v['Name'], $v['Desc'], $v['Value'], $v['Col'], $v['Data'], $v['Class']);break;
	        case 'html':
	            if(empty($v['Class'])) $v['Class'] = 'primary';
	            $Html = self::_html($v['Name'], $v['Desc'], $v['Value'], $v['Col'], $v['Data'], $v['Class']);break;
	        case 'hidden':
	            if(empty($v['Class'])) $v['Class'] = 'primary';
	            $Html = self::_Hidden($v['Name'], $v['Desc'], $v['Value'], $v['Col'], $v['Data'], $v['Class']);break;
	        default:
	            $Html= self::_FromInput($v['Name'], $v['Desc'], $v['Value'], $v['Col'], 'text', $v['Disabled'], $v['Placeholder']); break;
	    }
	    return array('Html' => $Html, 'Js' => $Js);
	}
	
	private function _FromLink($Name, $Desc, $Value, $Col = 12, $Data = '_blank', $Class = 'btn-success ml-2'){ //链接
	    return '<div class="form-group col-'.$Col.'"><a href="'.$Value.'" class="btn '.$Class.'" target="'.$Data.'">'.$Desc.'</a></a></div>';
	}
	
	private function _Html($Name, $Desc, $Value, $Col = 12, $Data = '_blank', $Class = 'btn-success ml-2'){
	    return '<div class="form-group col-'.$Col.' " ><label for="Input_'.$Name.'">'.$Desc.'</label><div class="">'.$Value.'</div></div>';
	}
	
	private function _HtmlStart($Name, $Col = 12){
	    return '<div class="form-group row col-'.$Col.' htmlClass" id="Html_'.$Name.'">';
	}
	
	private function _HtmlEnd(){
	    return '</div>';
	}
	
	private function _Hidden($Name, $Desc, $Value, $Col = 12, $Data = '_blank', $Class = 'btn-success ml-2'){
	    return '<div class="form-group col-'.$Col.' d-none">
                        <label for="Input_'.$Name.'">'.$Desc.'</label>
                        <input type="hidden" class="form-control" name="'.$Name.'" id="Input_'.$Name.'" value="'.$Value.'">
                    </div>';
	}
	
	private function _FromButton($Name, $Desc, $Type, $Col = 12, $Class = 'primary'){ //Button
	    return '<div class="form-group col-'.$Col.'"><button type="'.$Type.'" class="btn btn-'.$Class.'" id="Button_'.$Name.'">'.$Desc.'</button></div>';
	}
	
	private function _FormRadio($Name, $Desc, $Value, $DataArr = array(),  $Col, $IsDisabled = 0){
	    $Str = '<div class="form-group col-'.$Col.'""><label  class="mr-3">'.$Desc.'</label>';
	    foreach($DataArr as $k => $v){
	        $Checked = ($Value == $k) ? 'checked="checked"' : '';
	        $Str .= '<label class="radio-inline mr-3"><input type="radio" name="'.$Name.'"  value="'.$k.'" '.$Checked.'> '.$v.'</label>';
	    }
	    $Str .= '</div>';
	    return $Str;
	}
	
	private function _FormCheckbox($Name, $Desc, $Value, $DataArr = array(),  $Col, $IsDisabled = 0){ //Checkbox
	    $ValueArr = explode('|', $Value);
	    $Str = '<div class="form-group col-'.$Col.'""><label  class="mr-3 font-weight-bold">'.$Desc.'</label>';
	    foreach($DataArr as $k => $v){
	        $Checked = in_array($k, $ValueArr) ? 'checked="checked"' : '';
	        $Str .= '<div class="form-check form-check-inline mr-3"><label class="checkbox-inline "><input type="checkbox" name="'.$Name.'['.$k.']"  value="1" '.$Checked.' > '.$v.'</label></div>';
	    }
	    $Str .= '</div>';
	    return $Str;
	}
	
	private function _FromSelect($Name, $Desc, $Value, $Col, $DataArr = array(),  $IsDisabled = 0){ //select
	    $Disabled = ($IsDisabled) ? 'disabled="disabled"' : '';
	    if(empty($Placeholder)) $Placeholder =  '请输入'.$Name  ;
	    $Str = '<div class="form-group col-'.$Col.'"><label for="Input_'.$Name.'">'.$Desc.'</label><select class="form-control" name="'.$Name.'" id="Input_'.$Name.'" '.$Disabled.'>';
	    foreach($DataArr as $sk => $sv){
	        $selected = ($sk == $Value) ? 'selected' : '';
	        $Str .= '<option value="'.$sk.'" '.$selected.'>'.$sv.'</option>';
	    }
	    $Str .= '</select></div>';
	    return $Str;
	}
	
	private function _FormUpload($Name, $Desc, $Value, $Col, $IsDisabled = 0, $Placeholder = ''){
	    $Disabled = ($IsDisabled) ? 'disabled="disabled"' : '';
	    if(empty($Placeholder)) $Placeholder =  '请输入'.$Desc  ;
	    $StrHtml = $StrJs = '';
	    $StrHtml .= '<div class="form-group col-'.$Col.'">
                                    <label for="Input_'.$Name.'">'.$Desc.'</label>
                                    <div class="input-group">
                                      <input type="text" class="form-control" '.$Disabled.' placeholder="'.$Placeholder.'" name="'.$Name.'" Id="Img_'.$Name.'" value="'.$Value.'">
                                      <span class="input-group-append">
                                        <button class="btn btn-success" id="uploadImg_'.$Name.'" type="button">上传</button>
                                      </span>
                                             <span class="input-group-append">
                                        <button class="btn btn-danger" id="ViewImg_'.$Name.'" type="button">查看</button>
                                      </span>
                                    </div>
                                  </div> ';
	    $StrJs .= 'UploadBtn["'.$Name.'"] = $("#uploadImg_'.$Name.'");
                            new AjaxUpload(UploadBtn["'.$Name.'"], {
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
                                $("#Img_'.$Name.'").val(jsonArr.data.url)
                              }
                          });
	                       ';
	    $StrJs .= '$("#ViewImg_'.$Name.'").click(function(){ window.open($("#Img_'.$Name.'").val()); });';
	    return array($StrHtml, $StrJs);
	}
	
	
	private function _FormSlide($Name, $Desc, $Value, $Col, $IsDisabled = 0, $Placeholder = ''){ //多图
	    $Disabled = ($IsDisabled) ? 'disabled="disabled"' : '';
	    if(empty($Placeholder)) $Placeholder =  '请输入'.$Name  ;
	    $StrHtml = '<div class="col-'.$Col.'"><label for="Input_'.$Name.'">'.$Desc.'</label>';
	    $StrJs = '';
	    $ValueArr = explode('|', $Value);
	    foreach($ValueArr as $sk => $sv){
               $StrHtml .= '<div class="form-group">                            
                            <div class="input-group">
                                <input type="text" class="form-control" '.$IsDisabled.' placeholder="'.$Placeholder.'" name="'.$Name.'[]" Id="Img_'.$Name.'_'.$sk.'" value="'.$sv.'">
                                <span class="input-group-btn"><button class="btn btn-success" id="uploadImg_'.$Name.'_'.$sk.'" type="button">上传图片</button></span>
                            </div>
                        </div> ';
               $StrJs .= 'UploadBtn["'.$Name.'_'.$sk.'"] = $("#uploadImg_'.$Name.'_'.$sk.'");
                new AjaxUpload(UploadBtn["'.$Name.'_'.$sk.'"], {
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
                        $("#Img_'.$Name.'_'.$sk.'").val(jsonArr.data.url)
                      }
              });';
        }
        $StrHtml .= '</div>';
        return array($StrHtml, $StrJs);
	}
	
	private function _FormEditor($Name, $Desc, $Value, $Col, $IsDisabled = 0, $Placeholder = ''){ //编辑器
	    $Disabled = ($IsDisabled) ? 'disabled="disabled"' : '';
	    if(empty($Placeholder)) $Placeholder =  '请输入'.$Name  ;
	    $StrHtml = '<div class="form-group col-'.$Col.'">
                        <label for="Input_'.$Name.'">'.$Desc.'</label>
                        <textarea class="form-control" name="'.$Name.'" '.$IsDisabled.' rows="16" placeholder="'.$Placeholder.'">'.$Value.'</textarea>
                    </div>';
        $StrJs = 'var editor;
            KindEditor.ready(function(K) {
                editor = K.create(\'textarea[name="'.$Name.'"]\', {
                    allowFileManager : true,
                    themeType : "simple",
                    urlType : "absolute",
                    uploadJson : "'.$this->UploadEditUrl.'",
                    items : ["source","code","fontname", "fontsize", "|", "forecolor", "hilitecolor", "bold", "italic", "underline",
                    "removeformat", "|", "justifyleft", "justifycenter", "justifyright", "insertorderedlist",
                    "insertunorderedlist", "|", "image", "flash", "media","insertfile","link","unlink","|","table","fullscreen"]
                  })
            });';
        return array($StrHtml, $StrJs);
	}
	
	private function _FromMoney($Name, $Desc, $Value, $Col, $IsDisabled = 0, $Placeholder = ''){ //金钱
	    $Disabled = ($IsDisabled) ? 'disabled="disabled"' : '';
	    if(empty($Placeholder)) $Placeholder =  '请输入'.$Desc  ;
	    return '<div class="form-group col-'.$Col.'">
                        <label for="Input_'.$Name.'">'.$Desc.'</label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend"><span class="input-group-text">&yen;</span></div>
                                <input type="text" class="form-control" name="'.$Name.'" '.$IsDisabled.' id="Input_'.$Name.'" placeholder="'.$Placeholder.'" value="'.$Value.'">
                                <div class="input-group-append"><span class="input-group-text">.00</span></div>
                            </div>
                    </div>';
	}
	
	private function _FromInput($Name, $Desc, $Value, $Col, $Type = 'text', $IsDisabled = 0, $Placeholder = ''){ //输入框
	    $Disabled = ($IsDisabled) ? 'disabled="disabled"' : '';
	    if(empty($Placeholder)) $Placeholder =  '请输入'.$Desc  ;
	    return '<div class="form-group col-'.$Col.'">
                        <label for="Input_'.$Name.'">'.$Desc.'</label>
                        <input type="'.$Type.'" '.$Disabled.' class="form-control" name="'.$Name.'" id="Input_'.$Name.'" placeholder="'.$Placeholder.'" value="'.$Value.'">
                    </div>';
	}
	
	private function _FromGroup($Col, $Desc){ //填充而已
	    return '<div class="form-group col-'.$Col.'">'.$Desc.' 
                    </div>';
	}
	
	private function _FormTextarea($Name, $Desc, $Value, $Col, $IsDisabled = 0, $Placeholder = ''){ //输入框
	    $Disabled = ($IsDisabled) ? 'disabled="disabled"' : '';
	    if(empty($Placeholder)) $Placeholder =  '请输入'.$Desc  ;
	    return '<div class="form-group col-'.$Col.'">
                        <label for="Input_'.$Name.'">'.$Desc.'</label>
                        <textarea class="form-control" name="'.$Name.'" '.$Disabled.' rows="3" id="Input_'.$Name.'" placeholder="'.$Placeholder.'">'.$Value.'</textarea>
                      </div>';
	}

	
	/*
	 *  $keyArr = array('name' => ''标题'');
	 */
	public function Table(array $arr, $keyArr, $Page = ''){
	    $num = count($keyArr);
	    if(empty($this->LinkAdd)) $this->LinkAdd = $this->CommonObj->Url(array($this->Module, \Router::$s_controller, 'add'));
	    if(empty($this->LinkEdit)) $this->LinkEdit = $this->CommonObj->Url(array($this->Module, \Router::$s_controller, 'edit'));
	    if(empty($this->LinkDel)) $this->LinkDel = $this->CommonObj->Url(array($this->Module, \Router::$s_controller, 'del'));
	    $str = '<table class="table"><thead><tr>';
	    foreach($keyArr as $k => $v) $str .= '<th  scope="col">'.$v['Name'].'</th>';
	    if($this->IsEdit || $this->IsDel) $str .= '<th  scope="col">操作</th>';	    
	    $str .= '</tr></thead><tbody>';
	    foreach($arr as $k => $v){	        
	        $str .= '<tr>';
	        foreach($keyArr as $sk => $sv){
	            $Pre = isset($sv['Pre']) ? $sv['Pre'] : '';
	            //var_dump($sv);
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
                        $str .= '<td>'.$Pre.$keyArr[$sk]['Data'][$v[$sk]].'</td>';break;
                        break;
                    case 'Money':
                        $str .= '<td>&yen; '.$v[$sk].'</td>';
                        break;
	                default:
	                    $str .= '<td>'.$Pre.$v[$sk].'</td>';break;
	            }	            
	        }
	        if($this->IsEdit || $this->IsDel){
	            $ActArr = array();
	            $_GET[$this->PrimaryKey] = $v[$this->PrimaryKey];
	            if($this->IsEdit) $ActArr[] = '<a href="'.$this->LinkEdit.'?'.http_build_query($_GET).'">'.$this->NameEdit.'</a>';
	            if($this->IsDel) $ActArr[] = '<a href="'.$this->LinkDel.'?'.http_build_query($_GET).'" onclick="return confirm(\'是否删除?\')">'.$this->NameDel.'</a>';
	            $str .= '<td>'.implode(' ', $ActArr).'</td>';
	            unset($_GET[$this->PrimaryKey]);
	            $num++;
	        }
	        $str .= '</tr>';
	    }
	    if(!empty($Page)) $str .= '</tbody><tfoot><tr><td colspan="'.$num.'" class="page">'.$Page.'</td></tr></tfoot>';
	    $str .= '</table>';
	    return $str;
	}
	
	private function _Clean(){
	    $this->Html = '';
	    $this->Js = '';
	}
}