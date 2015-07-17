<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Home\Model;
use Think\Model;
use Think\Page;

/**
 * 文档基础模型
 */
class PlayerModel extends Model{

    /* 自动验证规则 */
    protected $_validate = array(
        array('phone','/^1\d{10}$/i','请输入正确的"手机号"!!',self::MUST_VALIDATE ,'regex',self::MODEL_INSERT),
        array('phone','','该手机号已预约过了!!',self::MUST_VALIDATE ,'unique',self::MODEL_INSERT),
    );

    /* 自动完成规则 */
    protected $_auto = array(
        array('appointTime',NOW_TIME,self::MODEL_INSERT),
        array('ip','getIP',self::MODEL_INSERT, 'function'),
        array('userAgent','getUserAgent',self::MODEL_INSERT, 'function'),
    );

}