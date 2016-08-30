<?php
APP_DEBUG?error_reporting(E_ALL):error_reporting(0);

define('FRAMEWORK_PAHT',dirname(__FILE__).'/');

//加载框架公共函数
require FRAMEWORK_PAHT.'Common/functions.php';

file_exists(FRAMEWORK_PAHT.'Conf/config.php') and $frameWorkConfig=require FRAMEWORK_PAHT.'Conf/config.php';
is_array($frameWorkConfig) or $frameWorkConfig=array();

//获取模块
if(defined('BIND_MODULE')){
	$module=$m=BIND_MODULE;
}else{
	$module=$m=array_key_exists('m',$_GET)?$_GET['m']:'Home';
}

//设置页面返回的编码。
$setHeaderChar = 'Content-type: text/html;charset=UTF-8';
header($setHeaderChar);

//设置时区为 中国/上海
ini_set('date.timezone','Asia/Shanghai');

//定义按照类名自动加载的include起始路径
$autoLoadIncludePath=array(FRAMEWORK_PAHT.'Library/',APP_PATH);
set_include_path(get_include_path().PATH_SEPARATOR .implode(PATH_SEPARATOR,$autoLoadIncludePath));
//php自动加载
spl_autoload_register('framework_autoload');

//mvc 处理
$c=array_key_exists('c',$_REQUEST)?$_REQUEST['c']:'Index';
$controller='\\'.$m.'\Controller\\'.$c.'Controller';

$action=$a=array_key_exists('a',$_REQUEST)?$_REQUEST['a']:'Index';

if(!(file_exists(APP_PATH.$m)&&is_dir(APP_PATH.$m))){
	echo $m.'模块不存在';
}else{
	//初始化app
	init_app();
	//var_dump(C('DB_CONFIG'));die();
	if(!class_exists($controller)){
		echo $c.'控制器不存在！';
	}else if(!method_exists($controller,$action)){
		echo $action.'方法不存在!';
	}else{
		$controllerObj=new $controller();
		$res=$controllerObj->$action();
	}
}