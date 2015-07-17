<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Admin\Controller;

/**
 * 查看预约结果控制器
 * 
 */
class ReservationController extends AdminController {

	//系统首页
    public function index( $p = 1){
		
		$this->indexofyuyue();
		
		$this->meta_title = '预约查询';
        $this->display();
    }
	
	public function tongji(){
		$Yuyue = 'Yuyue';
		
		$maxtime = M('Yuyue')->getField("max(created)");
		$mintime = M('Yuyue')->getField("min(created)");
		$timeDifference = floor((strtotime($maxtime)-strtotime($mintime))/86400);

		$pccount = M('Yuyue')->where('osflag_url="pc"')->getField("count(id)");
		$wapcount = M('Yuyue')->where('osflag_url="wap"')->getField("count(id)");
		$zongcount = M('Yuyue')->getField("count(id)");

		for($i=0;$i<=$timeDifference;$i++){
			$data = strtotime($maxtime)-$i*86400;
			$orderdate = date('Y-m-d',$data);
			$begin_time= $orderdate;
			$over_time = date('Y-m-d',strtotime('+1 day',strtotime($orderdate)));

			$list[$i]['pc']=M('Yuyue')->where('created between "'.$begin_time.'" and  "'.$over_time.'" and osflag_url="pc"')->getField("count(id)");
			$list[$i]['wap']=M('Yuyue')->where('created between "'.$begin_time.'" and  "'.$over_time.'" and osflag_url="wap"')->getField("count(id)");
			$list[$i]['count'] = $list[$i]['pc']+$list[$i]['wap'];
			$list[$i]['date'] = $orderdate;
			//exit;
		}
		
		$this->assign('_list',$list);
		$this->assign('pccount',$pccount);
		$this->assign('wapcount',$wapcount);
		$this->assign('zongcount',$zongcount);
		$this->meta_title = '预约统计';
		$this->display();
	}
	
	public function indexofyuyue(){
		/* 查询条件初始化 */
        $map = array();
        if(isset($_GET['name'])){
            $map['name']  = array('like', '%'.(string)I('name').'%');
        }
		if(isset($_GET['osflag_url'])){
            $map['osflag_url'] = I('osflag_url');
            $osflag_url = $map['osflag_url'];
        }
		if(isset($_GET['osflag'])){
            $map['osflag'] = I('osflag');
        }
		if ( isset($_GET['created']) ) {
            $map['created'][] = array('egt',I('created'));
        }
		if ( isset($_GET['end_time']) ) {
            $map['created'][] = array('elt',date('Y-m-d',strtotime('+1 day',strtotime(I('end_time')))));
        }

		$Yuyue = 'Yuyue';
		$list   =   $this->lists($Yuyue,$map);
		
		$this->assign('created',I('created'));
		$this->assign('end_time',I('end_time'));
		$this->assign('osflag_url',I('osflag_url'));
		$this->assign('osflag',I('osflag'));
		$this->assign('_list',$list);
	}
	

}