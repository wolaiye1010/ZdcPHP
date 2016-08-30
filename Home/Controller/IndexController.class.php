<?php
namespace Home\Controller;
use \Home\Model\LogicModel;
class IndexController{
	function index(){
		//取header 数据
		$data=json_decode(urldecode(I('server.HTTP_EXTENDDATA')),true);
		$data=empty($data)?array():$data;
		
		$mobile=I('data.mobile','',null,$data);
		$tokenGet=I('data.token','',null,$data);
		
		//header 为空取post get 数据	
		empty($mobile)?$mobile=I('mobile'):'';
		empty($tokenGet)?$tokenGet=I('token'):'';

		$rs=LogicModel::Reg720($mobile,$tokenGet);
		echo json_encode($rs);
	}
	
	function Test(){
//		echo get_rand_str(32);	
		$str='mobile='.$_REQUEST['mobile'].SECRET_720;
		var_dump($str,md5($str));
	}
	
	//退出登录
	function loginOut(){
		setCookie('user_id','',time()-3600);
		setCookie('user_name','',time()-3600);
		setCookie('login_info','',time()-3600);
		$backUrl=I('request.back_url','http://www.yaolan.com/');
		$jsStr=<<<EOT
<h1>退出登录成功！ 3秒后跳转!<h1>
<script type="text/javascript"> 
	window.setTimeout(function(){ top.location.href = "$backUrl";}, 3000);    
</script>
EOT;
		echo $jsStr;
	}
}
