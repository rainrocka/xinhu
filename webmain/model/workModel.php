<?php
class workClassModel extends Model
{
	/**
	*	未完成统计的也可以用m('flow')->initflow('work')->getdaiban();
	*/
	public function getwwctotals($uid)
	{
		$s 	= $this->rock->dbinstr('distid', $uid);
		$to	= $this->rows('`status` in(3,4) and '.$s.'');
		
		return $to;
	}
	
	//更新对应项目进度
	public function updateproject($id)
	{
		$id    = (int)$id;
		if($id==0)return;
		$zshu  = $this->rows('`projectid`='.$id.' and `status`<>5');
		$wcshu = $this->rows('`projectid`='.$id.' and `status`=1');
		$blix  = '0';
		if($zshu>0){
			$blix = ($wcshu/$zshu) *100;
		}
		m('project')->update('progress='.$blix.'', $id);
	}
}