<?php
class flow_assetmlyClassModel extends flowModel
{
	
	
	public function flowrsreplace($rs, $lx=0)
	{
		if($lx==2){
			$rows = $this->db->getall('select `pitem` from `[Q]plans` where `mid`='.$rs['id'].'');
			$s = '';
			foreach($rows as $k1=>$rs1)$s.=''.$rs1['pitem'].';';
			$rs['contentlist'] = $s;
		}
		
		$state = $rs['state'];
		if($state!='2'){
			if($rs['status']==1)$state = 1;
		}
		if($state != $rs['state'])$this->update('`state`='.$state.'', $rs['id']);
		
		if($state=='0')$rs['state']='待领取';
		if($state=='1')$rs['state']='已领取';
		if($state=='2')$rs['state']='已归还';
			
		
		return $rs;
	}

	
	//自定义审核人读取
	protected function flowcheckname($num){
		$sid = '';
		$sna = '';
		if($num=='queren'){
			$ssid = '';
			$runrenid = $this->rs['runrenid'];
			if(!isempt($runrenid)){
				$dbs  = m('dept');
				$runa = explode(',', $runrenid);
				foreach($runa as $id1){
					$id1d = str_replace(array('d','u'),'', $id1);
					if(contain($id1,'d')){
						$drs = $dbs->getone($id1d);
						if($drs && !isempt($drs['headid']))$ssid.=','.$drs['headid'].'';
					}else{
						$ssid.=','.$id1d.'';
					}
				}
			}
			if($ssid){
				$ssid = substr($ssid, 1);
				$rows = $this->adminmodel->getall('id in('.$ssid.') and `status`=1');
				if($rows){
					foreach($rows as $k=>$rs){
						$sid.=','.$rs['id'].'';
						$sna.=','.$rs['name'].'';
					}
					$sid = substr($sid, 1);
					$sna = substr($sna, 1);
				}
			}
		}
		return array($sid, $sna);
	}
	
	//审批完成更新领用
	protected function flowcheckfinsh($zt)
	{
		if($zt==1){
			$rows = m('plans')->getall('`mid`='.$this->id.'');
			foreach($rows as $k=>$rs){
				m('assetm')->update(array(
					'useid' => $this->rs['runrenid'],
					'usename' => $this->rs['runren'],
					'state'  => 1
				),$rs['itemid']);
			}
			$this->update('`state`=1', $this->id);
		}
	}
	
	//归还
	protected function flowoptmenu($ors, $crs)
	{
		if($ors['num']=='guihainoup'){
			$rows = m('plans')->getall('`mid`='.$this->id.'');
			foreach($rows as $k=>$rs){
				m('assetm')->update(array(
					'useid' => '',
					'usename' => '',
					'state'  => 0
				),$rs['itemid']);
			}
			$this->update('`state`=2', $this->id);
		}
	}
}