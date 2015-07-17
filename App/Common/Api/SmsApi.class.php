<?php
//短信发送api
namespace Common\Api;
class SmsApi {
	
	private $smsServer;  //sms服务器地址
	private $smsServerPort; //sms api 服务器端口
	private $authUser;  //登陆用户名
	private $authPassword;  //登陆密码
	
	
	public function __construct(){
		$this->smsServer = C('smsServer');
		$this->smsServerPort = C('smsServerPort');
		
	}
	
	public static  function sendSms($phone,$smsContent){
		
		
		$post_header = "POST $url HTTP/1.1\r\n";
		$post_header .= "Content-Type:application/x-www-form-urlencoded\r\n";
		$post_header .= "User-Agent: MSIE\r\n";
		$post_header .= "Host: ".$srv_ip."\r\n";
		$post_header .= "Cookie: ".$sessid."\r\n";
		$post_header .= "Content-Length: ".$content_length."\r\n";
		$post_header .= "Connection: close\r\n\r\n";
		$post_header .= $post_str."\r\n\r\n";
		
		
		
		
	  	$ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_HEADER, true);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_POST, true);
	    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
	    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers_login );
	    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_jar );
	    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_jar );
	    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)"); 
	    curl_setopt($ch, CURLOPT_POST, 1); // 发送一个常规的Post请求 
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string );
		
		
		
	}
	
	
	

}