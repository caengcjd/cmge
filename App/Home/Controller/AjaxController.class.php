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
 * 前台首页控制器
 * 主要获取首页聚合数据
 */
class AjaxController extends HomeController {
	//预约
	public function yuyue(){
		$Yuyue = D('Yuyue');
		
		$name=I('name');
		$phone_number=I('phone_number');
		$osflag=I('osflag');
		$osflag_url=I('osflag_url');
		$visit_url=I('visit_url');
		if(!preg_match("/^1[3|4|5|8][0-9]\d{8}$/",$phone_number)){
			$msg = array('code'=>201,'msg'=>'请输入正确的手机号');
			echo json_encode($msg);
			return;
		}
		if(!strstr('123',$osflag)){
			$msg = array('code'=>201,'msg'=>'请选择手机系统');
			echo json_encode($msg);
			return;
		}
		$osflagarray=array('wap','pc');
			if(!in_array($osflag_url,$osflagarray)){
				$msg = array('code'=>201,'msg'=>'传递参数不正确');
				echo json_encode($msg);
				return;
			}

		$visit_urlarray=array('hehe');
			if(!in_array($visit_url,$visit_urlarray)){
				$msg = array('code'=>201,'msg'=>'传递参数不正确');
				echo json_encode($msg);
				return;
			}
		$first_time=time();
		$dqtime = strtotime(date('Y-m-d'));
		$ip = get_client_ip();
		
		$iprscount = $Yuyue->where("first_time>".$dqtime." and ip='".$ip."' and visit_url='".$visit_url."'")->getField('count(*)');
		
		if($iprscount>=5){
			$msg = array('code'=>201,'msg'=>'该IP今天提交达到5次，请明天再来！');
			echo json_encode($msg);
			return;
		}
			$alertdata['1']="您已领取礼包，请勿重复领取！";
			$alertdata['2']="恭喜您，领取成功！礼包码将发送到您的手机上，请注意查收！";
		
		
		$numberrs = $Yuyue->where("number='".$phone_number."' and visit_url='".$visit_url."'")->getField('count(*)');
		if(empty($numberrs)){
			$data = '';
			$data['name'] = $name;
			$data['number'] = $phone_number;
			$data['first_time'] = $first_time;
			$data['osflag_url'] = $osflag_url;
			$data['visit_url'] = $visit_url;
			$data['ip'] = $ip;
			$data['osflag'] = $osflag;
			$yuyuers = $Yuyue->add($data);
			if(!$yuyuers){
				$this->ajaxReturn(array('code'=>201,'msg'=>'系统异常，请联系管理员！'));
			}
		}else{
			$msg = array('code'=>202,'msg'=>$alertdata['1']);
			echo json_encode($msg);
			return;
		}
			$mCode = D ( 'giftcode' );
			$list = $mCode->where ( "user_mobile=''" )->limit ( 1 )->select ();
			
			if (empty ( $list [0] )) {
				$this->ajaxReturn(array('code'=>201,'msg'=>'所有码已经发放完毕！'));
			}
			
			$data = $list [0];
			$data ['user_mobile'] = $phone_number;
			$data ['get_time'] = date ( 'Y-m-d H:i:s' );
			$rs = $mCode->save ( $data );
			if($rs){
				$smsContent = '非正经武侠手游《呵呵江湖》嘻哈来袭，大侠您有宝贝请签收。礼包码：'.$data['code'].'【中国手游】';
				//sendSMS($phone_number, $smsContent);
				$msg = array('code'=>200,'msg'=>$alertdata['2']);
				echo json_encode($msg);
				return;
			}
		
		
		$this->ajaxReturn(array('code'=>201,'msg'=>'系统异常，请联系管理员！'));
	}
	
	
	
	
}