<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Home\Controller;
use Think\Controller;

/**
 * 前台公共控制器
 * 为防止多分组Controller名称冲突，公共Controller名称统一使用分组名称
 */
class HomeController extends Controller {

	/* 空操作，用于输出404页面 */
	public function _empty(){
		$this->redirect('Index/index');
	}


    protected function _initialize(){
        /* 读取站点配置 */
        $config = api('Config/lists');
        C($config); //添加配置

        if(!C('WEB_SITE_CLOSE')){
            $this->error('站点已经关闭，请稍后访问~');
        }
		
		//适配不同站点的差异化
        
        $domain = $_SERVER['SERVER_NAME'];
        
        $diffArr = C('SITE_DIFF');
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $osPat = "iphone|nokia|sony|ericsson|mot|samsung|sgh|lg|philips|panasonic|alcatel|lenovo|cldc|midp|wap|mobile";
        $pash = $_SERVER["REQUEST_URI"];
    }
		//微信curl

    private function httpGet($url) {

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($curl, CURLOPT_TIMEOUT, 500);

        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);

        curl_setopt($curl, CURLOPT_URL, $url);



        $res = curl_exec($curl);

        //dump(curl_error($curl));

        curl_close($curl);

        return $res;

    }



    //微信随机字符串建立

    private function createNonceStr($length = 16) {

        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

        $str = "";

        for ($i = 0; $i < $length; $i++) {

            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);

        }

        return $str;

    }



    //获取微信access_token

    private function getAccessToken() {

        // access_token 应该全局存储与更新，以下代码以写入到文件中做示例

        $data = S('weixin_access_token');

        if (empty($data)) {

            // 如果是企业号用以下URL获取access_token

            // $url = "https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid=$this->appId&corpsecret=$this->appSecret";

            $appId=C('weixin_appId');

            $appSecret=C('weixin_appSecret');

            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appId&secret=$appSecret";

            $res = json_decode($this->httpGet($url),true);

            $access_token = $res['access_token'];

            S('weixin_access_token',$access_token,7000);

        } else {

            $access_token = $data;

        }

        return $access_token;

    }



    //获取微信jsapi_ticket

    private function getJsApiTicket() {

        // jsapi_ticket 应该全局存储与更新，以下代码以写入到文件中做示例

        $data = S('weixin_jsapi_ticket');

        if (empty($data)) {

            $accessToken = $this->getAccessToken();

            // 如果是企业号用以下 URL 获取 ticket

            // $url = "https://qyapi.weixin.qq.com/cgi-bin/get_jsapi_ticket?access_token=$accessToken";

            $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken";

            $res = json_decode($this->httpGet($url),true);

            $ticket = $res['ticket'];

            S('weixin_jsapi_ticket',$ticket,7000);

        } else {

            $ticket = $data;

        }

        return $ticket;

    }



    //微信JS签名

    protected function getSignPackage() {

        $jsapiTicket = $this->getJsApiTicket();



        // 注意 URL 一定要动态获取，不能 hardcode.

        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

        $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";



        $timestamp = time();

        $nonceStr = $this->createNonceStr();



        // 这里参数的顺序要按照 key 值 ASCII 码升序排序

        $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

        $signature = sha1($string);

        $signPackage = array(

            "appId"     => C('weixin_appId'),

            "nonceStr"  => $nonceStr,

            "timestamp" => $timestamp,

            "url"       => $url,

            "signature" => $signature,

            "rawString" => $string

        );

        return $signPackage;

    }

	

	//微信判断

    public function isweixin(){

        $user_agent = $_SERVER['HTTP_USER_AGENT'];

        if (strpos($user_agent, 'MicroMessenger') === false) {

            // 非微信浏览器

            $this->assign('weixin','0');

        } else {

            // 微信浏览器

			$weixinsign=$this->getSignPackage();

			$weixinsign['title'] = '《呵呵江湖》非正经武侠手游';
			$weixinsign['desc'] = '《呵呵江湖》采用全新的"滑动战斗"玩法，玩家通过自主收集能量球，触发狂拽炫酷叼的绝杀技能。开放式战场模式，玩家可以自由移动英雄的位置，手控全局。从此告别无脑数值比拼，告别重度操作，让战斗真正随心而动。';
			$weixinsign['imgUrl'] = 'http://hehe.cmge.com/Public/Home/second/wap/images/game-icon.png';

        	$this->assign('weixinsign',$weixinsign);

            $this->assign('weixin','1');

        }

    }

	public function returndetail(){

		return $weixinsign=$this->getSignPackage();

	}

}
