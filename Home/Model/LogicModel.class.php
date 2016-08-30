<?php
namespace Home\Model;
class LogicModel{
	public static $mobilePattern='/^1[3-8][0-9]{9}$/';

	public static function Reg720($mobile,$tokenGet){
		is_login()?die(redirect()):'';	
	
		$token=generate_token(array('mobile'=>$mobile),SECRET_720);

		if($token!==$tokenGet){
			$rs['code'] = 1005;
			$rs['msg'] = 'token不正确！';
		}elseif(!preg_match(self::$mobilePattern,$mobile)){
			$rs['code']='1006';
			$rs['msg']='手机号格式不正确!';
		}else{
			$url=GET_USER_INFO."?mobile=$mobile";
			$userInfo=json_decode(file_get_contents($url));

			if(8200==$userInfo->code&&10==$userInfo->data->UserSource->SourceType){
				$rs=login($userInfo);	
			}elseif(8200==$userInfo->code){
				$rs['code']=1007;
				$rs['msg']='该手机号已被注册';
			}else{
				$rs=regist($mobile);	
			}
		}
		return $rs;
	}	
}
