<?php
class finaClassModel extends Model
{

	public function initModel()
	{
		$this->settable('fininfom');
	}
	
	//统计我未还款
	public function totaljie($uid, $id=0)
	{
		$where 	= 'and id<>'.$id.'';
		$to1  	= floatval($this->getmou('sum(money)money',"`uid`='$uid' and `type`=2 and `status`=1"));
		$to2  	= floatval($this->getmou('sum(money)money',"`uid`='$uid' and `type`=3 and `status`<>5 $where"));
		$to 	= $to1-$to2;
		return $to;
	}
	
	public function getjkdwhere()
	{
		return '(select `uid` from `[Q]fininfom` where `type`=2 and `status`=1)';
	}
	
	//统计
	public function totalfkd($rows, $uids)
	{
		$carr = $barr = array();
		
		//借款
		$hkto = $this->db->getall("select uid,sum(money)money from `[Q]fininfom` where `uid` in($uids) and `type`=2 and `status`=1 group by `uid`");
		foreach($hkto as $k=>$rs)$carr[$rs['uid']] = $rs['money'];
		
		//还的
		$hkto = $this->db->getall("select uid,sum(money)money from `[Q]fininfom` where `uid` in($uids) and `type`=3 and `status`=1 group by `uid`");
		foreach($hkto as $k=>$rs)$barr[$rs['uid']] = $rs['money'];
		
		
		foreach($rows as $k=>$rs){
			$uid = $rs['id'];
			$moneyjk = floatval(arrvalue($carr, $uid, 0));
			$moneyhk = floatval(arrvalue($barr, $uid, 0));
			$moneyhx = $moneyjk - $moneyhk;
			
			if($moneyjk==0)$moneyjk='';
			if($moneyhk==0)$moneyhk='';
			if($moneyhx==0)$moneyhx='';
			
			$rows[$k]['moneyjk']	= $moneyjk;
			$rows[$k]['moneyhk']	= $moneyhk;
			$rows[$k]['moneyhx']	= $moneyhx;
		}
		
		return $rows;
	}
	
	
	
	
	
	
	//获取当前账套
	public function getzhangtao($lx=0)
	{
		$dt = $this->rock->date;
		$where= m('admin')->getcompanywhere(3);
		if($lx==0)$where.=" and `startdt`<='$dt' and `enddt`>='$dt'";
		$rows = m('finzhang')->getall("status=1 ".$where."",'*','sort, startdt desc');
		$arr  = array();
		foreach($rows as $k=>$rs){
			$sarr  = array(
				'value' => $rs['id'],
				'name' => $rs['name'],
				'subname' => $rs['startdt'].'→'.$rs['enddt'],
			);
			if($lx==2){
				unset($sarr['subname']);
				$sarr['name'].='('.$rs['startdt'].')';
			}
			$arr[] = $sarr;
		}
		return $arr;
	}
	//获取财务帐号
	public function getaccount($zhid='')
	{
		if(!$zhid)$zhid='0';
		$where= 'and `zhangid`='.$zhid.'';
		$rows = m('finount')->getall("status=1 ".$where."",'*','sort');
		$arr  = array();
		foreach($rows as $k=>$rs){
			$name = $rs['name'];
			if(!isempt($rs['banknum']))$name.='(**'.substr($rs['banknum'],-4).')';
			$arr[] = array(
				'value' => $rs['id'],
				'name' => $name,
			);
		}
		return $arr;
	}
	//更新财务帐号金额
	public function updatemoney($accid='')
	{
		$where= '';
		if(!isempt($accid))$where=' and `accountid`='.$accid.'';
		$rows = $this->db->getall('SELECT accountid,SUM(money)money FROM `[Q]finjibook` where `status`=1 '.$where.' group by accountid');
		$db = m('finount');
		$ids= '0';
		foreach($rows as $k=>$rs){
			$db->update('`money`='.$rs['money'].'', $rs['accountid']);
			$ids.=','.$rs['accountid'].'';
		}
		if(isempt($accid))$db->update('`money`=0', '`id` not in('.$ids.')');
	}

	
	//读取科目数据
	private $datarows = array();
	public function kemudata()
	{
		$data = m('finkemu')->getall('`status`=1','*','`sort`');
		$this->kemudatas($data, 0,'');
		
		return $this->datarows;
	}
	private function kemudatas($data,$pid,$nae)
	{
		foreach($data as $k=>$rs){
			if($pid==$rs['pid']){
				$this->datarows[] = array(
					'value' => $rs['id'],
					'name'  => ''.$rs['num'].' '.$nae.''.$rs['name'].''
				);
				$this->kemudatas($data, $rs['id'],$nae.$rs['name'].'-');
			}
		}
	}
}