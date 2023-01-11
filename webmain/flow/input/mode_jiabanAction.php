<?php
class mode_jiabanClassAction extends inputAction{
	
	public function totalAjax()
	{
		$start	= $this->post('stime');
		$end	= $this->post('etime');
		$jiatype= (int)$this->post('jiatype');
		$date	= c('date', true);
		$sj		= $date->datediff('H', $start, $end);
		$jiafee	= 0;
		if($jiatype==1)$jiafee	= m('kaoqin')->jiafee($this->adminid, $sj, $start);
		
		$this->returnjson(array($sj, '', $jiafee));
	}
	
	
	protected function savebefore($table, $arr, $id, $addbo){
		$msg 	= m('kaoqin')->leavepan($arr['uid'], '', $arr['stime'], $arr['etime'], 0, $id,'加班');
		return $msg;
	}
}	
			