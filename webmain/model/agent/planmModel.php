<?php
/**
	通知公告的
*/
class agent_planmClassModel extends agentModel
{
	
	public function gettotal()
	{
		$stotal	= $this->getwdtotal($this->adminid);
		$titles	= '';
		return array('stotal'=>$stotal,'titles'=> $titles);
	}
	
	private function getwdtotal($uid)
	{

		$stotal	= m('flow:planm')->getwwctotals($uid);
		return $stotal;
	}
	
	
	protected function agenttotals($uid)
	{
		$a = array(
			'mydwc' => $this->getwdtotal($uid)
		);
		return $a;
	}
	
}