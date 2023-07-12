<?php
//菜单管理
class flow_flowmenuClassModel extends flowModel
{
	protected $flowcompanyidfieds	= 'none'; 
	
	
	public function iseditqx()
	{
		if($this->adminid==1)return true;
		return parent::iseditqx();
	}
	
	public function isdeleteqx()
	{
		if(getconfig('systype')=='demo')return false;
		if($this->adminid==1)return true;
		return parent::isdeleteqx();
	}
	
	public function flowdeletebillbefore($sm)
	{
		if($this->rows('`pid`='.$this->id.'')>0)return '有下级菜单不能删除';
	}
	
	
	public function flowbillwhere($uid, $lx)
	{
		$where 	= '';
		$pid = (int)$this->rock->post('pid','0');
		$where='and `pid`='.$pid.'';
		if($pid>0){
			$pids = $pid;
			$arows= $this->getall('`pid`='.$pid.'');
			foreach($arows as $k=>$rs)$pids.=','.$rs['id'].'';
			$where=' and (`id`='.$pid.' or `pid`='.$pid.' or `pid` in(select `id` from `[Q]menu` where `pid` in('.$pid.')))';
		}
		return array(
			'order' => '`sort`',
			'where' => $where
		);
	}
	
	//下级需要验证，那上级也必须验证的
	private function updatepirss()
	{
		$rows 	= $this->db->getall('select `pid` from `[Q]menu` where `pid`>0 and `ispir`=1 group by `pid`');
		$sid 	= '0';
		foreach($rows as $k=>$rs)$sid.=','.$rs['pid'].'';
		if($sid!='')m('menu')->update('`ispir`=1', "`id` in($sid)");
	}
}