<?php
namespace Home\Controller;
use \Zdc\Db\PDOHelper;
class DemoController{
	function demo720(){
		$_REQUEST['code']?die(highlight_file(__FILE__)):'';
		$url='http://lnmp.yaolan.com/720/index.php?c=Index&a=index';
		$data=array(
			'mobile'=>'13021939679',
			'token'=>'ab1db6164f63886ca29c59f5d0e723a2',
		);

		$header=array(
			'extendData:'.urlencode(json_encode($data)),
		);
		var_dump(curl_get_carry_header($url,$header));
	}
	
	function test(){
		$rs=PDOHelper::query('select * from auto_reg_tianya limit 100');
		var_dump($rs);
	}
}