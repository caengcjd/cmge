<?php

// +----------------------------------------------------------------------

// | OneThink [ WE CAN DO IT JUST THINK IT ]

// +----------------------------------------------------------------------

// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.

// +----------------------------------------------------------------------

// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>

// +----------------------------------------------------------------------



namespace Home\Controller;

use OT\DataDictionary;

use Admin\Controller\PublicController;



/**

 * 前台首页控制器

 * 主要获取首页聚合数据

 */

class IndexController extends HomeController {

    public function index(){
	   $this->display();   
    }
    public function addPlayer(){
        $Player = D('Player');
        if (!$Player->create()){
            // 如果创建失败 表示验证没有通过 输出错误提示信息
            self::$errorCode = '200001'; 
            self::$errorMessage = $Player->getError();
        }else{
            // 验证通过 可以进行其他数据操作
            $ret = $Player->add();
            if(!$ret) {
                self::$errorCode = '200002'; 
                self::$errorMessage = '预约失败，请稍后再试'; 
            }
        }
        echo $this->ajaxReturn(
            array(
                'errorCode'    => self::$errorCode,
                'errorMessage' => self::$errorMessage
            )
        );
    }
}