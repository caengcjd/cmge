<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Home\Controller;

/**
 * 登录模块
 */
class ApiloginController extends HomeController{

    //cmge 通行证登陆
    public function cmgeLogin(){
        $backurl = $_SERVER["HTTP_REFERER"];
        if(!$backurl){
            $backurl = C('SITE_DOMAIN');
        }
        if(stripos($backurl,'wap')){
            $backurl = C('SITE_DOMAIN');
        }
        $cmgeAppSecret = '9e2d553635c9131bcca1d4ef0b19256a';
        $cmgeAppKey='cmgeCq';
        $callback=C('SITE_DOMAIN').'/index.php?s=/Home/Apilogin/cmgeCallBack&backurl='.$backurl;
        $url = 'http://i.cmge.com/oauth/GetAccessCode?time='.time()
            .'&sign='.md5($cmgeAppKey.$callback)
            .'&appKey='.$cmgeAppKey
            .'&callback='.urlencode($callback);
        redirect($url);
    }

    //cmge 通行证注册
    public function cmgeRegister(){
        $backurl = $_SERVER["HTTP_REFERER"];
        if(!$backurl){
            $backurl = C('SITE_DOMAIN');
        }
        if(stripos($backurl,'wap')){
            $backurl = C('SITE_DOMAIN');
        }
        $cmgeAppSecret = '9e2d553635c9131bcca1d4ef0b19256a';
        $cmgeAppKey='cmgeCq';
        $callback=C('SITE_DOMAIN').'/index.php?s=/Home/Apilogin/cmgeCallBack&backurl='.$backurl;
        $url = 'http://i.cmge.com/oauth/Register?time='.time()
            .'&sign='.md5($cmgeAppKey.$callback)
            .'&appKey='.$cmgeAppKey
            .'&callback='.urlencode($callback);
        redirect($url);
    }

    //cmge 登陆返回
    public function cmgeCallBack(){

        header("Content-type: text/html; charset=utf-8");
        $accessCode = trim($_REQUEST['accessCode']);
        $cmgeUsername = trim($_REQUEST['username']);
        $backurl = trim($_REQUEST['backurl']);
        if(empty($accessCode)){
            die('登录失败');
        }

        $cmgeAppSecret = '9e2d553635c9131bcca1d4ef0b19256a';
        $cmgeAppKey='cmgeCq';

        $curl_url = 'http://i.cmge.com/oauth/GetToken?accessCode='.$accessCode.'&appSecret='.$cmgeAppSecret.'&time='.time()
            .'&sign='.md5($accessCode.$cmgeAppSecret) ;

        $returnJson = file_get_contents($curl_url);

        $jsonArr = json_decode($returnJson,true);

        $getUserInfoUrl = 'http://i.cmge.com/oauth/GetUserData?username='.$cmgeUsername.'&token='.$jsonArr['token']
            .'&appKey='.$cmgeAppKey.'&time='.time().'&sign='.md5($cmgeUsername.$jsonArr['token'].$cmgeAppSecret);

        $userInfo = file_get_contents($getUserInfoUrl);
        $userInfo = json_decode($userInfo,true);

        session('nickname',$userInfo['nick_name']);

        $url = $backurl;
        header("location:$url");
    }

    //ajax认证
    public function ajaxUserCallBack(){
        $nickname=session('nickname');
        if(!empty($nickname)){
            return false;
        }
        $username=I('username');
        $phone=I('phone');
        $email=I('email');
        $sex=I('sex');
        $nick_name=I('nick_name');
        $avatar=I('avatar');
        $brithday=I('brithday');

        //校验参数合法性
        $cmgeAppSecret = '9e2d553635c9131bcca1d4ef0b19256a';
        $a=md5($username.$email.$phone.$sex.$nick_name.$avatar.$brithday.$cmgeAppSecret);
        $sign=I('sign');
        if($a!=$sign){
            return false;
        }

        session('nickname',$nick_name);

        $this->ajaxReturn(array('code'=>200));
    }

    //注销
    public function logout(){

        session(null);

        $url = $_SERVER["HTTP_REFERER"];
        if(!$url){
            $url = C('SITE_DOMAIN');
        }
        session('GetThelogout',1);//同步退出，勿删
        header("location:$url");
    }
}
