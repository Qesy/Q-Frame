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
    public $IsBack = false;
    public $LinkIndex;
    public $LinkExport;
    public $LinkAdd;
    public $LinkEdit;
    public $LinkDel;
    public $NameEdit = '修改';
    public $NameDel = '删除';
    public $UploadUrl;
    public $UploadEditUrl;
    public $UploadEditFileUrl;
    public $FormStyle = 1; //1 是正常 2 inline
    public static function get_instance() {
        if (! isset ( self::$s_instance )) {
            self::$s_instance = new self ();
        }
        return self::$s_instance;
    }
    public $CommObj;
    function __construct(){
        $this->CommObj = Common::get_instance();
    }
    
    public function Form($Method = 'POST', $Class = '', $ExtHtml = ''){
        if(!is_array($this->Arr)) return;
        $this->UploadUrl = !empty($this->UploadUrl) ? $this->UploadUrl : $this->CommObj->Url(array('backend', 'index', 'ajaxUpload'));
        $this->UploadEditUrl = !empty($this->UploadEditUrl) ? $this->UploadEditUrl : $this->CommObj->Url(array('backend', 'index', 'uploadEditor'));
        $this->LinkIndex = !empty($this->LinkIndex) ? $this->LinkIndex : $this->CommObj->Url(array($this->Module, \Router::$s_Controller, 'index'));
        $this->LinkExport = !empty($this->LinkExport) ? $this->LinkExport : $this->CommObj->Url(array($this->Module, \Router::$s_Controller, 'export'));
        self::_Clean();
        $this->FormStyle = ($Class == 'form-inline') ? 2 : 1;
        $this->Html = '<form method="'.$Method.'" class="BuildForm '.$Class.'">';
        foreach($this->Arr as $k => $v){
            if(empty($v['Col']) && $Class != 'form-inline') $v['Col'] = 12;
            $v['Required'] = empty($v['Required']) ? 0 : 1;
            switch ($v['Type']){
                case 'formgroup':
                    $this->Html .= self::_FromGroup($v['Col'], $v['Desc']); break;
                case 'radio':
                    $this->Html .= self::_FormRadio($v['Name'], $v['Desc'], $v['Value'], $v['Data'], $v['Col'], $v['Disabled']); break;
                case 'checkbox':
                    $this->Html .= self::_FormCheckbox($v['Name'], $v['Desc'], $v['Value'], $v['Data'], $v['Col'], $v['Disabled']); break;
                case 'select':
                    $this->Html .= self::_FromSelect($v['Name'], $v['Desc'], $v['Value'], $v['Col'], $v['Data'], $v['Disabled'], $v['Required']); break;
                case 'upload':
                    list($StrHtml, $StrJs) = self::_FormUpload($v['Name'], $v['Desc'], $v['Value'], $v['Col'], $v['Disabled'], $v['Placeholder'], $v['Required']);
                    $this->Html .= $StrHtml;
                    $this->Js .= $StrJs;
                    break;
                case 'uploadBatch':
                    list($StrHtml, $StrJs) = self::_FormUploadBatch($v['Name'], $v['Desc'], $v['Value'], $v['Col'], $v['Disabled'], $v['Placeholder']);
                    $this->Html .= $StrHtml;
                    $this->Js .= $StrJs;
                    break;
                case 'slide':
                    list($StrHtml, $StrJs) = self::_FormSlide($v['Name'], $v['Desc'], $v['Value'], $v['Col'], $v['Disabled'], $v['Placeholder']);
                    $this->Html .= $StrHtml;
                    $this->Js .= $StrJs;
                    break;
                case 'textarea':
                    $this->Html .= self::_FormTextarea($v['Name'], $v['Desc'], $v['Value'], $v['Col'], $v['Disabled'], $v['Placeholder'], $v['Required']); break;
                case 'editor':
                    list($StrHtml, $StrJs) = self::_FormEditor($v['Name'], $v['Desc'], $v['Value'], $v['Col'], $v['Disabled'], $v['Placeholder'], $v['Required']);
                    $this->Html .= $StrHtml;
                    $this->Js .= $StrJs;
                    break;
                case 'money':
                    $this->Html .= self::_FromMoney($v['Name'], $v['Desc'], $v['Value'], $v['Col'], $v['Disabled'], $v['Placeholder']); break;
                case 'date':
                    $this->Html .= self::_FromInput($v['Name'], $v['Desc'], $v['Value'], $v['Col'], $v['Type'], $v['Disabled'], $v['Placeholder'], $v['Required']); break;
                case 'datetime':
                    $v['Type'] = 'datetime-local';
                    $this->Html .= self::_FromInput($v['Name'], $v['Desc'], $v['Value'], $v['Col'], $v['Type'], $v['Disabled'], $v['Placeholder'], $v['Required']); break;
                    /* case 'time':
                     $this->Html .= self::_FromInput($v['Name'], $v['Desc'], $v['Value'], $v['Col'], 'time', $v['Disabled'], $v['Placeholder']); break; */
                case 'password':
                    $this->Html .= self::_FromInput($v['Name'], $v['Desc'], $v['Value'], $v['Col'], 'password', $v['Disabled'], $v['Placeholder'], $v['Required']); break;
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
                case 'htmlFill':
                    if(empty($v['Class'])) $v['Class'] = 'primary';
                    $this->Html .= self::_html($v['Name'], $v['Desc'], $v['Value'], $v['Col'], $v['Data'], $v['Class'], 1);break;
                case 'htmlStart':
                    $this->Html .= self::_htmlStart($v['Name'], $v['Col']);break;
                case 'htmlEnd':
                    $this->Html .= self::_htmlEnd();break;
                case 'hidden':
                    if(empty($v['Class'])) $v['Class'] = 'primary';
                    $this->Html .= self::_Hidden($v['Name'], $v['Desc'], $v['Value'], $v['Col'], $v['Data'], $v['Class']);break;
                case 'buttonGroup':
                    $this->Html .= self::_ButtonGroup($v['Name'], $v['Desc'], $v['Value'], $v['Data'], $v['Col'], $v['Disabled']);
                    break;
                default:
                    $this->Html .= self::_FromInput($v['Name'], $v['Desc'], $v['Value'], $v['Col'], 'text', $v['Disabled'], $v['Placeholder'], $v['Required']); break;
            }
        }
        if($Class != 'form-inline') $Col = 12;
        if($this->IsSubmit) $this->Html .= self::_FromButton('submit', '提交', 'submit', $Col, 'primary');
        if($this->IsBack) $this->Html .= self::_FromButton('submit', '返回', 'back', $Col, 'secondary');
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
        $SubCol = ($Col*2 > 12) ? 12 : ($Col*2);
        return '<div class="form-group col-'.$SubCol.'  col-lg-'.$Col.'"><a href="'.$Value.'" class="btn '.$Class.'" target="'.$Data.'">'.$Desc.'</a></a></div>';
    }
    
    private function _Html($Name, $Desc, $Value, $Col = 12, $Data = '_blank', $Class = 'btn-success ml-2', $IsFill = 2){
        $SubCol = ($Col*2 > 12) ? 12 : ($Col*2);
        $IsFillStr = ($IsFill == 1) ? 'd-none d-lg-block' : '';
        return '<div class="form-group col-'.$SubCol.'  col-lg-'.$Col.' '.$IsFillStr.'" ><label for="Input_'.$Name.'">'.$Desc.'</label><div class="'.$Class.'">'.$Value.'</div></div>';
    }
    
    private function _HtmlStart($Name, $Col = 12){
        $SubCol = ($Col*2 > 12) ? 12 : ($Col*2);
        return '<div class="form-group row col-'.$SubCol.'  col-lg-'.$Col.' htmlClass" id="Html_'.$Name.'" style="margin:-10px;">';
    }
    
    private function _HtmlEnd(){
        return '</div>';
    }
    
    private function _Hidden($Name, $Desc, $Value, $Col = 12, $Data = '_blank', $Class = 'btn-success ml-2'){
        $SubCol = ($Col*2 > 12) ? 12 : ($Col*2);
        return '<div class="form-group col-'.$SubCol.'  col-lg-'.$Col.' d-none">
                        <label for="Input_'.$Name.'">'.$Desc.'</label>
                        <input type="hidden" class="form-control" name="'.$Name.'" id="Input_'.$Name.'" value="'.$Value.'">
                    </div>';
    }
    
    private function _FromButton($Name, $Desc, $Type, $Col = 12, $Class = 'primary'){ //Button
        $SubCol = ($Col*2 > 12) ? 12 : ($Col*2);
        if($Type == 'back'){
            return '<div class="form-group col-'.$SubCol.'  col-lg-'.$Col.'"><button type="button" onclick="history.go(-1)" class="btn btn-'.$Class.' '.(($this->FormStyle == 2) ? 'btn-xs' : '').'" id="Button_'.$Name.'">'.$Desc.'</button></div>';
        }
        return '<div class="form-group col-'.$SubCol.'  col-lg-'.$Col.'"><button type="'.$Type.'" class="btn btn-'.$Class.' '.(($this->FormStyle == 2) ? 'btn-xs' : '').'" id="Button_'.$Name.'">'.$Desc.'</button></div>';
    }
    
    private function _FormRadio($Name, $Desc, $Value, $DataArr = array(),  $Col, $IsDisabled = 0){
        $SubCol = ($Col*2 > 12) ? 12 : ($Col*2);
        $Str = '<div class="form-group col-'.$SubCol.'  col-lg-'.$Col.'"><label  class="mr-3">'.$Desc.'</label>';
        foreach($DataArr as $k => $v){
            $Checked = ($Value == $k) ? 'checked="checked"' : '';
            $Str .= '<label class="radio-inline mr-3"><input type="radio" name="'.$Name.'"  value="'.$k.'" '.$Checked.'> '.$v.'</label>';
        }
        $Str .= '</div>';
        return $Str;
    }
    
    private function _FormCheckbox($Name, $Desc, $Value, $DataArr = array(),  $Col, $IsDisabled = 0){ //Checkbox
        $ValueArr = explode('|', $Value);
        $SubCol = ($Col*2 > 12) ? 12 : ($Col*2);
        $Str = '<div class="form-group col-'.$SubCol.'  col-lg-'.$Col.'"><label  class="mr-3 font-weight-bold">'.$Desc.'</label>';
        foreach($DataArr as $k => $v){
            $Checked = in_array($k, $ValueArr) ? 'checked="checked"' : '';
            $Str .= '<div class="form-check form-check-inline mr-3"><label class="checkbox-inline "><input type="checkbox" name="'.$Name.'['.$k.']"  value="1" '.$Checked.' > '.$v.'</label></div>';
        }
        $Str .= '</div>';
        return $Str;
    }
    
    private function _ButtonGroup($Name, $Desc, $Value, $DataArr = array(),  $Col, $IsDisabled = 0){ //Checkbox
        $ValueArr = explode('|', $Value);
        $SubCol = ($Col*2 > 12) ? 12 : ($Col*2);
        $Str = '<div class="form-group col-'.$SubCol.'  col-lg-'.$Col.'">
                    <label  class="mr-3 font-weight-bold">'.$Desc.'</label>
                        <div class="selectgroup selectgroup-pills">';
        foreach($DataArr as $k => $v){
            $Str .= '
			<button type="button"  class="btn btn-primary mr-1 btn-sm btn-round Button_'.$Name.'"  data="'.$k.'">
			'.$v.'</button>
		';
        }
        $Str .= '</div></div>';
        return $Str;
    }
    
    private function _FromSelect($Name, $Desc, $Value, $Col, $DataArr = array(),  $IsDisabled = 0, $Required = 0){ //select
        $Disabled = ($IsDisabled) ? 'disabled="disabled"' : '';
        if(empty($Placeholder)) $Placeholder =  '请输入'.$Name  ;
        $SubCol = ($Col*2 > 12) ? 12 : ($Col*2);
        $RequiredViewStr = $RequiredStr = '';
        if(!empty($Required)){
            $RequiredStr = 'required="required"';
            $RequiredViewStr = '<span class="text-danger ml-2" style="font-weight: 900;">*</span>';
        }
        $Class = ($this->FormStyle == 2) ? '' : 'col-'.$SubCol.'  col-lg-'.$Col;
        $Str = '<div class="form-group '.$Class.'"><label for="Input_'.$Name.'" class="'.(($this->FormStyle == 2) ? 'mr-2' : '').'">'.$Desc.$RequiredViewStr.'</label><select class="form-control '.(($this->FormStyle == 2) ? 'form-control-sm' : '').'" name="'.$Name.'" id="Input_'.$Name.'" '.$Disabled.' '.$RequiredStr.'>';
        $Str .= '<option value="" >请选择'.$Desc.'</option>';
        foreach($DataArr as $sk => $sv){
            $selected = ($sk == $Value) ? 'selected' : '';
            $Str .= '<option value="'.$sk.'" '.$selected.'>'.$sv.'</option>';
        }
        $Str .= '</select></div>';
        return $Str;
    }
    
    private function _FormUploadBatch($Name, $Desc, $Value, $Col, $IsDisabled = 0, $Placeholder = ''){
        $Disabled = ($IsDisabled) ? 'disabled="disabled"' : '';
        if(empty($Placeholder)) $Placeholder =  '请输入'.$Desc  ;
        $StrHtml = $StrJs = '';
        $SubCol = ($Col*2 > 12) ? 12 : ($Col*2);
        $StrHtml .= '<div class="form-group col-'.$SubCol.'  col-lg-'.$Col.'">
                                    <label for="Input_'.$Name.'"><button type="button" Id="Img_'.$Name.'" class="btn btn-primary btn-sm">上传图片</button>  </label>
                                    <input type="hidden" name="'.$Name.'" id="SlideInput">
                                    <div class="form-group p-0">
                                      <div class="d-flex " id="SlideArrHtml">
                                      </div>
                                    </div>
                                  </div> ';
        $StrJs .=
        'UploadBtn["'.$Name.'"] = $("#Img_'.$Name.'");
	        var valStr = "'.$Value.'";
	        var SlideArr = (valStr == "") ? [] : valStr.split("|");
	            SlideListFunc();
	            console.log(SlideArr);
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
                  SlideArr.push(jsonArr.data.url)
                  SlideListFunc()
                  $("#'.$Name.'Input").val(SlideArr.join("|"))
                }
            });';
        return array($StrHtml, $StrJs);
    }
    
    private function _FormUpload($Name, $Desc, $Value, $Col, $IsDisabled = 0, $Placeholder = '', $Required = 0){
        $Disabled = ($IsDisabled) ? 'disabled="disabled"' : '';
        if(empty($Placeholder)) $Placeholder =  '请输入'.$Desc  ;
        $RequiredViewStr = $RequiredStr = '';
        if(!empty($Required)){
            $RequiredStr = 'required="required"';
            $RequiredViewStr = '<span class="text-danger ml-2" style="font-weight: 900;">*</span>';
        }
        $StrHtml = $StrJs = '';
        $SubCol = ($Col*2 > 12) ? 12 : ($Col*2);
        $StrHtml .= '<div class="form-group col-'.$SubCol.'  col-lg-'.$Col.'">
                                    <label for="Input_'.$Name.'">'.$Desc.'</label>'.$RequiredViewStr.'
                                    <div class="input-group">
                                      <input type="text" class="form-control" '.$Disabled.' placeholder="'.$Placeholder.'" name="'.$Name.'" Id="Img_'.$Name.'" value="'.$Value.'" '.$RequiredStr.'>
                                      <span class="input-group-append">
                                        <button class="btn btn-success" id="uploadImg_'.$Name.'" type="button" '.$Disabled.'>上传</button>
                                      </span>
                                             <span class="input-group-append">
                                        <button class="btn btn-danger" id="ViewImg_'.$Name.'" type="button">查看</button>
                                      </span>
                                    </div>
                                  </div> ';
        $StrJs .= ($IsDisabled) ? '' : 'UploadBtn["'.$Name.'"] = $("#uploadImg_'.$Name.'");
                            new AjaxUpload(UploadBtn["'.$Name.'"], {
                              action: "'.$this->UploadUrl.'",
                              name: "filedata",
                              onSubmit : function(file, ext){
                                this.disable();
                              },
                              onComplete: function(file, response){
                                var jsonArr = JSON.parse(response);
                                console.log("aaa", jsonArr.code)
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
        $SubCol = ($Col*2 > 12) ? 12 : ($Col*2);
        $StrHtml = '<div class="col-'.$SubCol.'  col-lg-'.$Col.'"><label for="Input_'.$Name.'">'.$Desc.'</label>';
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
    
    private function _FormEditor($Name, $Desc, $Value, $Col, $IsDisabled = 0, $Placeholder = '', $Required = 0){ //编辑器
        $Disabled = ($IsDisabled) ? 'disabled="disabled"' : '';
        if(empty($Placeholder)) $Placeholder =  '请输入'.$Name  ;
        $RequiredViewStr = $RequiredStr = '';
        if(!empty($Required)){
            $RequiredStr = 'required="required"';
            $RequiredViewStr = '<span class="text-danger ml-2" style="font-weight: 900;">*</span>';
        }
        $SubCol = ($Col*2 > 12) ? 12 : ($Col*2);
        $StrHtml = '<div class="form-group col-'.$SubCol.'  col-lg-'.$Col.'">
                        <label for="Input_'.$Name.'">'.$Desc.'</label>'.$RequiredViewStr.'
                        <textarea class="form-control Input_Editor" name="'.$Name.'" '.$IsDisabled.' rows="16" id="Input_'.$Name.'" placeholder="'.$Placeholder.'" >'.$Value.'</textarea>
                    </div>';
        return array($StrHtml);
    }
    
    /* 	private function _FormEditor($Name, $Desc, $Value, $Col, $IsDisabled = 0, $Placeholder = ''){ //编辑器
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
     fileManagerJson : "'.$this->UploadEditFileUrl.'",
     items : ["source","code","fontname", "fontsize", "|", "forecolor", "hilitecolor", "bold", "italic", "underline",
     "removeformat", "|", "justifyleft", "justifycenter", "justifyright", "insertorderedlist",
     "insertunorderedlist", "|", "image", "flash", "media","insertfile","link","unlink","|","table","fullscreen"]
     })
     });';
     return array($StrHtml, $StrJs);
     } */
    
    private function _FromMoney($Name, $Desc, $Value, $Col, $IsDisabled = 0, $Placeholder = ''){ //金钱
        $Disabled = ($IsDisabled) ? 'disabled="disabled"' : '';
        if(empty($Placeholder)) $Placeholder =  '请输入'.$Desc  ;
        $SubCol = ($Col*2 > 12) ? 12 : ($Col*2);
        return '<div class="form-group col-'.$SubCol.'  col-lg-'.$Col.'">
                        <label for="Input_'.$Name.'">'.$Desc.'</label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend"><span class="input-group-text">&yen;</span></div>
                                <input type="text" class="form-control" name="'.$Name.'" '.$IsDisabled.' id="Input_'.$Name.'" placeholder="'.$Placeholder.'" value="'.$Value.'">
                                <div class="input-group-append"><span class="input-group-text">.00</span></div>
                            </div>
                    </div>';
    }
    
    private function _FromInput($Name, $Desc, $Value, $Col, $Type = 'text', $IsDisabled = 0, $Placeholder = '', $Required = 0){ //输入框
        $Disabled = ($IsDisabled) ? 'disabled="disabled"' : '';
        if(empty($Placeholder)) $Placeholder =  '请输入'.$Desc  ;
        $SubCol = ($Col*2 > 12) ? 12 : ($Col*2);
        $RequiredViewStr = $RequiredStr = '';
        if(!empty($Required)){
            $RequiredStr = 'required="required"';
            $RequiredViewStr = '<span class="text-danger ml-2" style="font-weight: 900;">*</span>';
        }
        $Class = ($this->FormStyle == 2) ? '' : 'col-'.$SubCol.'  col-lg-'.$Col;
        return '<div class="form-group '.$Class.'">
                        <label for="Input_'.$Name.'" class="'.(($this->FormStyle == 2) ? 'mr-2' : '').'">'.$Desc.'</label>'.$RequiredViewStr.'
                        <input type="'.$Type.'" '.$Disabled.' class="form-control '.(($this->FormStyle == 2) ? 'form-control-sm' : '').'" name="'.$Name.'" id="Input_'.$Name.'" placeholder="'.$Placeholder.'" value="'.$Value.'" '.$RequiredStr.'>
                    </div>';
    }
    
    private function _DateTimeInput($Name, $Desc, $Value, $Col, $Type = 'text', $IsDisabled = 0, $Placeholder = '', $Required = 0){ //输入框
        $Disabled = ($IsDisabled) ? 'disabled="disabled"' : '';
        if(empty($Placeholder)) $Placeholder =  '请输入'.$Desc  ;
        $RequiredViewStr = $RequiredStr = '';
        if(!empty($Required)){
            $RequiredStr = 'required="required"';
            $RequiredViewStr = '<span class="text-danger ml-2" style="font-weight: 900;">*</span>';
        }
        $SubCol = ($Col*2 > 12) ? 12 : ($Col*2);
        return '
                    <div class="form-group col-'.$SubCol.' col-lg-'.$Col.' "  id="Form_'.$Name.'">
                        <label for="Input_'.$Name.'">'.$Desc.'</label>'.$RequiredViewStr.'
                            <div class="input-group date '.$Type.'Only" id="FormDate_'.$Name.'" data-target-input="nearest">
                            <input type="text" '.$Disabled.' class="form-control datetimepicker-input " name="'.$Name.'" value="'.$Value.'" data-target="#FormDate_'.$Name.'" '.$RequiredStr.'/>
                            <div class="input-group-append" data-target="#FormDate_'.$Name.'" data-toggle="datetimepicker"  >
                        <div class="input-group-text"><i class="fa fa-calendar"></i>&nbsp;</div>
                    </div>
                     </div></div>';
    }
    
    private function _FromGroup($Col, $Desc){ //填充而已
        $SubCol = ($Col*2 > 12) ? 12 : ($Col*2);
        return '<div class="form-group col-'.$SubCol.'  col-lg-'.$Col.' d-none d-lg-block">'.$Desc.'
                    </div>';
    }
    
    private function _FormTextarea($Name, $Desc, $Value, $Col, $IsDisabled = 0, $Placeholder = '', $Required = 0){ //输入框
        $Disabled = ($IsDisabled) ? 'disabled="disabled"' : '';
        if(empty($Placeholder)) $Placeholder =  '请输入'.$Desc  ;
        $RequiredViewStr = $RequiredStr = '';
        if(!empty($Required)){
            $RequiredStr = 'required="required"';
            $RequiredViewStr = '<span class="text-danger ml-2" style="font-weight: 900;">*</span>';
        }
        $SubCol = ($Col*2 > 12) ? 12 : ($Col*2);
        return '<div class="form-group col-'.$SubCol.'  col-lg-'.$Col.'">
                        <label for="Input_'.$Name.'">'.$Desc.'</label>'.$RequiredViewStr.'
                        <textarea class="form-control" name="'.$Name.'" '.$Disabled.' rows="4" id="Input_'.$Name.'" placeholder="'.$Placeholder.'" '.$RequiredStr.'>'.$Value.'</textarea>
                      </div>';
    }
    
    
    /*
     *  $keyArr = array('name' => ''标题'');
     */
    public function Table(array $arr, $keyArr, $Page = '', $Class= '', $IsResponsive = 2){
        $num = count($keyArr);
        if(empty($this->LinkAdd)) $this->LinkAdd = $this->CommObj->Url(array($this->Module, \Router::$s_Controller, 'add')).'?'.http_build_query($_GET);
        if(empty($this->LinkEdit)) $this->LinkEdit = $this->CommObj->Url(array($this->Module, \Router::$s_Controller, 'edit'));
        if(empty($this->LinkDel)) $this->LinkDel = $this->CommObj->Url(array($this->Module, \Router::$s_Controller, 'del'));
        $str = '<table class="table '.$Class.'"><thead><tr>';
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
                    case 'Switch':
                        $str .= '<td><span class="switch switch-sm">
                                <input type="checkbox" class="StateBtn switch" id="switch-'.$sk.'-'.$v[$this->PrimaryKey].'" data="'.$v[$this->PrimaryKey].'" dataState="'.(($v[$sk] == 1) ? 2 : 1).'" dataField="'.$sk.'" '.(($v[$sk] == 1) ? 'checked' : '').'>
                                <label for="switch-'.$sk.'-'.$v[$this->PrimaryKey].'"></label>
                              </span></td>';break;
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
        if(!empty($Page)) $str .= '</tbody><tfoot><tr><td colspan="'.$num.'" class="page ">'.$Page.'</td></tr></tfoot>';
        $str .= '</table>';
        return ($IsResponsive == 1) ? '<div class="table-responsive-sm">'.$str.'</div>' : $str;
    }
    
    private function _Clean(){
        $this->Html = '';
        $this->Js = '';
    }
}