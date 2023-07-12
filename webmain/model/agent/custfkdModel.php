<?php
//客户.付款单的应用
class agent_custfkdClassModel extends agentModel
{
	
	
	//状态显示替换
	protected function agentrows($rows, $rowd, $uid)
	{
		$statea = $this->flow->statearrf;
		foreach($rowd as $k=>$rs){
			$state 	 = $rs['paystatus'];
			$ztarr	 = $statea[$state];
			$rows[$k]['statustext']		= $ztarr[0];
			$rows[$k]['statuscolor']	= $ztarr[1];
			$rows[$k]['modenum']	    = 'custfinb';
		}
		return $rows;
	}
	
	public function gettotal()
	{
		$stotal	= $this->getwdtotal($this->adminid);
		$titles	= '';
		return array('stotal'=>$stotal,'titles'=> $titles);
	}
		
	private function getwdtotal($uid)
	{
		$where	= '`uid`='.$uid.' and `type`=1 and `ispay`=0';
		$stotal	= m('custfina')->rows($where);
		return $stotal;
	}	
}