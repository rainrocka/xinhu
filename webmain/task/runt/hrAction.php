<?php
class hrClassAction extends runtAction
{
	/*
	* 员工合同到期提醒/人事每天调动运行
	*/
	public function httodoAction()
	{
		m('hr')->hrrun(); //人事每天调动/离职等运行
	
		//员工合同到期提醒
		$flow 	= m('flow')->initflow('userract');
		$flow->updatestate();
		$dtobj  = c('date');
		$dt 	= $this->rock->date;
		$dt30 	= $dtobj->adddate($dt,'d',35);
		$rows 	= m('userract')->getall("state=1 and `enddt`<='$dt30'",'id,enddt,httype,name,uname');
		$str 	= '';
		foreach($rows as $k=>$rs){
			$jg = $dtobj->datediff('d', $dt, $rs['enddt']);
			if($jg==30 || $jg<=7){
				$str.='人员['.$rs['uname'].']的【'.$rs['httype'].'.'.$rs['name'].'】将在'.$jg.'天后的'.$rs['enddt'].'到期;';
			}
		}
		if($str != ''){
			$this->todoarr	= array(
				'modenum' 	=> 'userract',
				'agentname' => '员工合同',
				'title' 	=> '员工合同到期提醒',
				'cont' 		=> $str,
			);
		}
		
		//生日提醒
		m('flow')->initflow('userinfo')->birthdaytodo();
		
		//自动从考核项目中添加
		m('flow')->initflow('hrcheck')->hrkaohemrun();
		
		//个人资料完善提醒
		
		return 'success';
	}
	

}