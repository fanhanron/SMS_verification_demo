<?php
namespace app\index\controller;
use think\facade\Request;
use dysms\Dysms;
use think\Controller;
use think\facade\Session;

class Index extends Controller
{
	//渲染页面
    public function index()
    {
		if(Request::isPost()){
			$data = Request::post();
			
			die;
		}
		return $this->fetch();


	}
	//获取验证码
	public function get_dysms(){
		
		if(Request::isPost()){
			$data = Request::post();
			
			$setPhoneNumbers = $data['phone'];				//获取手机号码  必填
			
			$accessKeyId = "*************"; // AccessKeyId  必填
			
			$accessKeySecret = "*************"; // AccessKeySecret  必填
			
			$setSignName = "********";    //设置签名名称  必填
			
			$setTemplateCode = "SMS_00000001";		//设置模板CODE，  	必填
			
			$code = rand(10000,99999);	//生成随机验证码
			
			$Dysms = new Dysms();
			//传入手机号码，验证码, AccessKeyId, AccessKeySecret, 签名名称, 模板CODE
			$response = $Dysms::sendSms($setPhoneNumbers, $code, $accessKeyId, $accessKeySecret, $setSignName, $setTemplateCode); 
			//echo "发送短信(sendSms)接口返回的结果:\n <br>";
			if($response->Code == 'OK'){
				//Session::init(['expire' => 30]);	//设置30秒
				Session::set('code',$code);
				$response->session_code = Session::get('code');
			}
			return json_encode($response);
			die;
		}
	}
	//检验，验证码
	public function check_dysms() {
		$data = Request::post();

		if(Request::post()){
			$data = Request::post();
			
			if( (string)$data['security'] === (string)Session::get('code') ){
				$msg = "验证成功".$data['security'].", ". Session::get('code');
			}else{
				$msg = "验证失败 | ".$data['security']." | ". Session::get('code');
			}
			return $msg;
			
			die;
		}
	}
}
