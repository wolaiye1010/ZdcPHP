<?php
use \Home\Model\YaoLanService\Mcrypt;

//重定向函数
function redirect(){
	$backUrl=I('backUrl');
	empty($backUrl)?$backUrl=REDIRECT_URL:'';
	header('Location:'.$backUrl);
}

//由手机号自动注册720账号
function regist($mobile){
	$userName=json_decode(file_get_contents(GET_AUTO_USER_NAME))->data;
	$password=get_rand_str();
	
	$rs= json_decode(user_reg($userName,$password,$mobile));	
	if(8200==$rs->code){
		$url=SMS_PASSWORD.http_build_query(array(
			'mobile'=>$mobile,
			'password'=>$password,
			'sign'=>md5("mobile={$mobile}de124a4c14539a3a5ee529c99055a17b")
			));	
		//echo '调用发短信函数，发送密码';die();
		$smsLog="$mobile regist success! ".file_get_contents($url);
		write_log($smsLog);	
		login($rs);
	}else{
		return	array('code'=>1003,'msg'=>'注册失败','data'=>$rs->code);		
	}	
}

/**
 *Author zhudongchang 
 *Date 2015/4/15
 *摇篮网用户注册生成密码 函数
 *@param string $username 用户名
 *@param string $password 用户输入原始密码
 *@return string 
 */
function yaolan_password_hash($userName,$password){
        $psdMd516=substr(md5($password),8,16);
        //对字符串进行unicode 编码然后再进行md5
        $hashPassword=md5(iconv('UTF-8','UTF-16LE',$userName.$psdMd516));
        return $hashPassword;
}

//注册函数
function user_reg($username,$password,$email){
        $st=10;//注册来源
        $data=array('uname'=>$username,'psd'=>yaolan_password_hash($username,$password),'uemail'=>$email,'st'=>$st);
        return curl_post(REGIRST,$data);
}

//cookie函数
function set_encrypt_cookie($name,$value,$expire=null,$path='/',$domain='yaolan.com'){
	is_null($expire)?$expire=time()+3600*24*30:'';
	setcookie($name,$value,$expire,$path,$domain);
}

//写cookie 登录函数
function login($userInfo){
	$userId=$userInfo->data->UserBaseInfo->UserId;
	
	$login_info_ip=$_SERVER["REMOTE_ADDR"];
	$login_info_time=time()+3600*24*30;
	$login_info_uid=$userId;
	$password=$userInfo->data->LoginInfo->Password;
	$rememberSalt='3f3f32de09bd3b99';
	$loginStr="$login_info_ip|$login_info_time|$login_info_uid|$password|$rememberSalt";

	$login_info_hash=md5($loginStr);
	$login_info = "$login_info_ip|$login_info_time|$login_info_uid|$login_info_hash";	
	$login_infoDstr=Mcrypt::Ecrypt($login_info);
	set_encrypt_cookie('login_info',$login_infoDstr);
	set_encrypt_cookie('user_id',Mcrypt::Ecrypt($userId));
	set_encrypt_cookie('user_name',Mcrypt::Ecrypt($userInfo->data->UserBaseInfo->UserName));
//	echo '登录成功，重定向到url';	
	redirect();
}

//摇篮网判断登录函数
function is_login(){
	$userId=Mcrypt::Decrypt(array_key_exists('user_id',$_COOKIE)?$_COOKIE['user_id']:null);	
	return is_numeric($userId)&&$userId>0;
}
