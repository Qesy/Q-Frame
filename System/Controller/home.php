<?php
defined ( 'PATH_SYS' ) || exit ( 'No direct script access allowed' );
class Home extends Controllers {
	public function err_Action() {
		header ( "HTTP/1.1 404 Not Found" );
		echo '404 error !';
	}
	public function phpinfo_Action() {
		phpinfo ();
	}
	
    public function build_Action(){
	    $DbConfig = db_config ();
	    $Pre = $DbConfig['Prefix'];
	    $PdoObj = Db_pdo::get_instance();
	    $TableArr = $PdoObj->query('show tables;', array());
	    $OtherInterface = $OtherPublic = $OtherInclude = array();
	    foreach($TableArr as $v){
	        $TableName = substr($v['Tables_in_'.$DbConfig['Name']], strlen($Pre));
	        $OtherPublic[] = 'public $'.ucfirst($TableName).'Obj;';
	        $OtherInclud[] = '$this->'.ucfirst($TableName).'Obj = '.strtoupper($Pre).ucfirst($TableName).'::get_instance();';
	        $OtherInterface[] = 'use Model\\'.strtoupper($Pre).ucfirst($TableName).';';
	        self::_b($DbConfig['Name'], $Pre, $TableName);
	    }
	    $OtherStr = implode("\n", $OtherInterface)."\n\n";
	    $OtherStr .= implode("\n", $OtherPublic)."\n\n";
	    $OtherStr .= implode("\n", $OtherInclud)."\n\n";
	    $FilePath = PATH_LIB.'Model/other.php';
	    file_put_contents($FilePath, $OtherStr);
	    echo '<span style="color:#ff0000;">Success !</span>';
	}
	
    private function _b($DbName, $Pre, $TableName){
	    $PdoObj = Db_pdo::get_instance();
	    $ClassName = strtoupper($Pre).ucfirst($TableName);
	    $Arr = $PdoObj->query('SHOW FULL COLUMNS FROM '.$Pre.$TableName, array());
	    $PrimaryKey = '';
	    $Date = date('Y-m-d');
	    foreach($Arr as $k => $v) if($v['Key'] == 'PRI') $PrimaryKey = $v['Field'];	        
	    $tmp = file_get_contents(PATH_STATIC.'tmp/lib.temp');
	    $Str = str_replace(array('{Date}', '{Table}', '{PrimaryKey}', '{ClassName}'), array($Date, $TableName, $PrimaryKey, $ClassName), $tmp);
	    $FilePath = PATH_LIB.'Model/'.$ClassName.'.php';
	    if(file_exists($FilePath)){
	        echo '<span style="color:green;">'.$FilePath.'</span><br>';
	        return;
	    }
	    echo $FilePath.'<br>';
	    file_put_contents($FilePath, $Str);
	}
}