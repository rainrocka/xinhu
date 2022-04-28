<?php 
class indexClassAction extends ActionNot{
	
	public function initAction()
	{
		$this->mweblogin(0, false);
	}
	
	public function defaultAction()
	{
		$this->title = getconfig('apptitle',$this->bd6('5L!h5ZG8T0E:'));
		if(COMPANYNUM){
			$companyinfo = m('company')->getone(1);
			$oanemes	 = $companyinfo['oanemes'];
			if(isempt($oanemes))$oanemes = $companyinfo['name'];
			$this->title = $oanemes;
		}
		$ybarr	 = $this->option->authercheck();
		if(is_string($ybarr))return $ybarr;
		$this->assign('xhauthkey', getconfig('authkey', $ybarr['authkey']));
		$this->assign('tplmess', $this->option->getval('wxgzh_tplmess'));
	}
	
	public function bd6($str)
	{
		return $this->jm->base64decode($str);
	}
	
	public function editpassAction()
	{
		
	}
	
	
	
	/**
	*	用户信息
	*/
	public function userinfoAction()
	{
		$uid = (int)$this->get('uid');
		$urs = m('admin')->getone($uid, '`id`,`name`,`deptallname`,`ranking`,`tel`,`email`,`mobile`,`sex`,`face`');
		if(!$urs)exit('not user');
		
		//权限过滤
		$flow = m('flow')->initflow('user');
		$ursa = $flow->viewjinfields(array($urs));
		$urs  = $ursa[0];
		
		if(isempt($urs['face']))$urs['face']='images/noface.png';
		$this->assign('arr', $urs);
	}
	
	public function companyAction()
	{
		$this->assign('carr', m('admin')->getcompanyinfo($this->adminid));
		$this->assign('ofrom', $this->get('ofrom'));
	}
	
	
	public function testAction()
	{
		
	}
	
}