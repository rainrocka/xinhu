<?php 
class loginClassAction extends ActionNot{
	
	public function defaultAction()
	{
		$this->tpltype	= 'html';
		$this->smartydata['ca_adminuser']	= $this->getcookie('ca_adminuser');
		$this->smartydata['ca_rempass']		= $this->getcookie('ca_rempass');
		$this->smartydata['ca_adminpass']	= $this->getcookie('ca_adminpass');
		$this->smartydata['loginyzm']		= (int)getconfig('loginyzm','0'); //登录类型
		$this->smartydata['platsign']		= $this->getsession('platsign');
	}
	
	public function checkAjax()
	{
		$user 	= $this->jm->base64decode($this->post('adminuser'));
		$user	= str_replace(' ','',$user);
		$pass	= $this->jm->base64decode($this->post('adminpass'));
		$rempass= $this->post('rempass');
		$jmpass	= $this->post('jmpass');
		$cfrom	= $this->post('cfrom','pc');
		if($jmpass == 'true')$pass=$this->jm->uncrypt($pass);
		$userp	= $user;
		$arr 	= m('login')->start($user, $pass, $cfrom);
		$barr 	= array();
		if(is_array($arr)){
			
			if(isset($arr['mobile'])){
				$barr = $arr;
				$barr['success'] = false;
				return $barr;
			}
			
			$uid 	= $arr['uid'];
			$name 	= $arr['name'];
			$user 	= $arr['user'];
			$token 	= $arr['token'];
			$face 	= $arr['face'];
			m('login')->setsession($uid, $name, $token, $user);
			$this->rock->savecookie('ca_adminuser', $userp);
			$this->rock->savecookie('ca_rempass', $rempass);
			$ca_adminpass	= $this->jm->encrypt($pass);
			if($rempass=='0')$ca_adminpass='';
			$this->rock->savecookie('ca_adminpass', $ca_adminpass);
			$barr['success'] = true;
			$barr['face'] 	 = $face;
			$barr['token'] 	 = $token;
		}else{
			$barr['success'] = false;
			$barr['msg'] 	 = $arr;
		}
		return $barr;
	}
	
	public function exitAction()
	{
		m('dept')->online(0);//离线
		m('login')->exitlogin('pc',$this->admintoken);
		$this->rock->location('?m=login');
	}
	
	/**
	*	对外的信息收集
	*/
	public function collectAction()
	{
		if(!getconfig('authorkey'))return $this->jm->base64decode('6Z2e5o6I5p2D54mI5peg5rOV5L2.55So5q2k5Yqf6IO9');
		$this->title = '信息收集表';
		$mid = (int)$this->get('mid','0');
		$mrs = m('planm')->getone('`id`='.$mid.' and `type`=2 and `fenlei`=1 and `status`=1');
		if(!$mrs)return '信息不存在';
		if($mrs['enddt']<$this->rock->now)return '时间已经截止至'.$mrs['enddt'].'';
		if($mrs['startdt']>$this->rock->now)return ''.$mrs['startdt'].'时间才可以开始';
		$mrs['onlyid'] = '0';
		$rows = m('plans')->getall('`mid`='.$mid.'','*','`sort`');
		$contstr = '';
		$fieldarr= array();
		$lexar   = array('select','checkbox','checkboxall','radio');
		foreach($rows as $k=>$rs){
			$arr = array(
				'name' => $rs['pitem'],
				'fieldstype'=> $rs['zxren'],
				'data' 		=> '',
				'attr' 		=> '',
				'dev'	 	=> '',
				'isbt'		=> $rs['itemid'],
			);
			if(!isempt($rs['zxrenid'])){
				if(!in_array($arr['fieldstype'],$lexar)){
					$arr['placeholder'] = $rs['zxrenid'];
				}else{
					$arr['data'] = $rs['zxrenid'];
				}
			}
			$fieldarr['sitemid_'.$rs['id'].''] = $arr;
		}
		$this->inputobj	= c('input');
		$this->inputobj->fieldarr 	= $fieldarr;
		foreach($rows as $k=>$rs){
			$str = $this->inputobj->getfieldcont('sitemid_'.$rs['id'].'');
			$sth = '';
			if($rs['itemid']=='1')$sth='<font color=red>*</font>';
			$contstr.='<div style="color:#555555">'.$sth.$rs['pitem'].'</div>';
			$contstr.='<div>'.$str.'</div>';
			$contstr.='<div class="blank15"></div>';
		}
		$this->title = $mrs['name'];
		$this->assign('contstr', $contstr);
		$this->assign('fieldarr', $fieldarr);
		$this->assign('mrs', $mrs);
	}
	
	/**
	*	保存外部收集
	*/
	public function collectcheckAction()
	{
		$mid 	= (int)$this->post('mid','0');
		$onlyid = (int)$this->post('onlyid','0');
		$mrs 	= m('planm')->getone('`id`='.$mid.' and `type`=2');
		if(!$mrs)return returnerror('不存在');
		$flow  	= m('flow')->initflow('collects');
		$uarr  	= array(
			'uid' => 0,
			'optdt' => $this->rock->now,
			'optid' => 0,
			'optname' => '',
			'applydt' => $this->rock->date,
			'status' => 0,
			'type' 	 => 3,
			'isturn' => 1,
			'comid'   => $mrs['comid'],
			'name' 	  => $mrs['name'],
			'startdt' => $mrs['startdt'],
			'enddt'   => $mrs['enddt'],
			'leixing' => $mid,
			'psren'   => $mrs['optname'],
			'psrenid' => $mrs['optid'],
		);
		$id = $flow->insert($uarr);
		$flow->loaddata($id, false);
		$flow->submit();
		return returnsuccess();
	}
}