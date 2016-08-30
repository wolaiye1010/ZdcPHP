<?php
//自动加载类
function framework_autoload($className){
	$className=str_replace('\\','/',$className);
	$path=$className.'.class'.'.php';
	if(file_exists($path)){
		include_once($path);
	}else{
		die($path.'不存在');
	}
}

//写日志函数
function write_log($msg,$dirPath='',$isEcho=false){
        $dirPath?'':$dirPath='logs'.DIRECTORY_SEPARATOR;
	$path=$dirPath.'log'.date('Ymd',time()).'.txt';
        if(!is_dir(dirname($path))){
                mkdir(dirname($path),0755,true)or die('创建目录失败.');
        }
        $msg=date('Y-m-d H:i:s',time())."---------------$msg----\n";
        file_put_contents($path,$msg,FILE_APPEND);
        if($isEcho){
                echo $msg.'<br/>';
        }
}

//获取随机数字字母字符串
function get_rand_str($len=8){
	$randArr=array_merge(range(0,9),range('a','z'),range('A','Z'));
	shuffle($randArr);
	$rs=array_slice($randArr,0,$len);
	//foreach(array_rand($randArr,$len) as $val){
	//	$rs[]=$randArr[$val];	
	//}
	return implode($rs);
}

//post 函数
function curl_post($url,$data=array()){
        $ch=curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch,CURLOPT_POST,true);//如果有下面的一行代码，这个可以不设置
        curl_setopt($ch,CURLOPT_POSTFIELDS,$data);

        $content=curl_exec($ch);
        curl_close($ch);
        return $content;
}

//get函数携带 header
function curl_get_carry_header($url,array $header=array()){
	$ch=curl_init();
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
	$content=curl_exec($ch);
	curl_close($ch);
	return $content;
}


//get函数
function curl_get($url){
	$ch=curl_init();
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
	$content=curl_exec($ch);
	curl_close($ch);
	return $content;
}

//获取输入参数
function I($name,$default='',$filter=null,array $datas=array()){
	if(strpos($name,'.')) { // 指定参数来源
		list($method,$name) =   explode('.',$name,2);
	}else{ // 默认为自动判断
		$method =   'param';
	}
	switch(strtolower($method)) {
		case 'get'     :   $input =& $_GET;break;
		case 'post'    :   $input =& $_POST;break;
		case 'put'     :   parse_str(file_get_contents('php://input'), $input);break;
		case 'param'   :
		    switch($_SERVER['REQUEST_METHOD']) {
			case 'POST':
			    $input  =  $_POST;
			    break;
			case 'PUT':
			    parse_str(file_get_contents('php://input'), $input);
			    break;
			default:
			    $input  =  $_GET;
		    }
		    break;
		case 'request' :   $input =& $_REQUEST;   break;
		case 'session' :   $input =& $_SESSION;   break;
		case 'cookie'  :   $input =& $_COOKIE;    break;
		case 'server'  :   $input =& $_SERVER;    break;
		case 'globals' :   $input =& $GLOBALS;    break;
		case 'data'    :   $input =& $datas;      break;
		default:
		    return NULL;
	}
	
	$rs=array_key_exists($name,$input)?$input[$name]:$default;	
	if($filter){
		return function_exists($filter)?$filter($rs):$rs;
	}else{
		return $rs;
	}

/*
	list($method,$name) =explode('.',$name,2);
	$method=strtolower($method);
	switch($method){
		case 'get':$input=&$_GET;break;
		case 'post':$input=&$_POST;break;
		default :$input=&$_REQUEST;
	}		

	$rs=array_key_exists($name,$input)?$input[$name]:$default;	
	if($filter){
		return function_exists($filter)?$filter($rs):$rs;
	}else{
		return $rs;
	}
*/
}

//获取token
function generate_token($params=array(), $appSecret = ''){
	$result = '';
	ksort($params);
	foreach ( $params as $key => $value ) {
		$result .= "$key=$value";
	}
	$result .= $appSecret;
	return md5($result);
}

//初始化配置文件
function init_app(){
	global $m,$appConfig,$frameWorkConfig;
	//加载app common配置
	file_exists(APP_PATH.'Common/Conf/config.php') and $appConfig=require APP_PATH.'Common/Conf/config.php';
	file_exists(APP_PATH.'Common/Common/function.logic.php') and require APP_PATH.'Common/Common/function.logic.php';	
	//加载相应模块 配置
	file_exists(APP_PATH.$m.'/Conf/config.php') and $moduleConfig=require APP_PATH.$m.'/Conf/config.php';
	file_exists(APP_PATH.$m.'/Common/function.logic.php') and require APP_PATH.$m.'/Common/function.logic.php';

	//合并配置
	is_array($appConfig) or $appConfig=array();
	is_array($moduleConfig) or $moduleConfig=array();
	$appConfig=array_merge($frameWorkConfig,$appConfig,$moduleConfig);
}

//获取配置信息函数
function C($key='',$val=null){
	global $appConfig;
	if(empty($key)){
		return $appConfig;
	}
	
	if(is_null($val)){
		return $appConfig[$key];	
	}else{
		$appConfig[$key]=$val;
		return true;
	}
}