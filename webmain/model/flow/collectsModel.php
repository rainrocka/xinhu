<?php
class flow_collectsClassModel extends flowModel
{
	
	public $subtable = 'plans';
	
	
	public function flowfieldarr($farr, $lx)
	{
		$mid = (int)$this->rock->post('mid','0');
		//if($mid==0)$mid = (int)$this->rock->post('id','0');
		$this->newfarr = array();
		if($mid){
			$mrs = $this->getone($mid);
			$sid = $mrs['leixing'];
			$zrows = m($this->subtable)->getall('mid='.$sid.'','*','`sort`');
			
			foreach($zrows as $k=>$rs){
				$frs  = $farr[0];
				$frs['name'] = $rs['pitem'];
				$frs['fieldstype'] = $rs['zxren'];
				$frs['data'] = $rs['zxrenid'];
				$frs['attr'] = '';
				$frs['lens'] = '0';
				$frs['isbt'] = ($rs['itemid']=='1')?'1':'0';
				$frs['islu'] = '1';
				$frs['id'] 	 = '0';
				$frs['fields'] = 'sitemid_'.$rs['id'].'';
				
				$farr[] = $frs;
				$this->newfarr[] = $frs;
			}
		}
		
		return $farr;
	}
	
	//在运行这个，模版处理
	public function flowinputtpl($cont, $lx)
	{
		//pc
		if($lx==0){
			$str = '';
			foreach($this->newfarr as $k=>$rs){
				$str.='<tr><td class="ys1" align="right">'.(($rs['isbt']=='1')?'*':'').''.$rs['name'].'</td><td colspan="3" class="ys2">{'.$rs['fields'].'}</td></tr>';
			}
			$cont = str_replace('{autotpl}', $str, $cont);
		}
		return $cont;
	}
	
	//报错子表信息
	protected function flowsubmit($na, $sm)
	{
		$sid   = $this->rs['leixing'];
		$dbs   = m($this->subtable);
		$zrows = $dbs->getall('mid='.$sid.'','*','`sort`');
		foreach($zrows as $k=>$rs){
			$fields = 'sitemid_'.$rs['id'].'';
			$where  = '`mid`='.$this->id.' and `itemid`='.$rs['id'].'';
			
			$uarr = array(
				'pitem' => $rs['pitem'],
				'zxren' => $this->rock->post($fields),
				'zxrenid' => $this->rock->post($fields.'id'),
				'itemid'=> $rs['id'],
				'mid'	=> $this->id,
				'comid'	=> $this->rs['comid'],
				'sort'	=> $rs['sort'],
			);
			
			if($dbs->rows($where)==0)$where='';
			$dbs->record($uarr, $where);
		}
		$this->updaterenshu($sid);
	}
	
	public function updaterenshu($sid)
	{
		$zrshu = $this->rows('`leixing`='.$sid.' and `type`=3 and `status`<>5');
		$this->update('`leixing`='.$zrshu.'', $sid);
	}
	
	protected function flowrsreplaceedit($rs)
	{
		$this->addotherfield();
		$zrows = m($this->subtable)->getall('mid='.$rs['id'].'','*','`sort`');
		foreach($zrows as $k=>$rs1){
			$rs['sitemid_'.$rs1['itemid'].''] = $rs1['zxren'];
			$this->rssust['sitemid_'.$rs1['itemid'].''] = $rs1['zxren'];
		}
		return $rs;
	}
	
	/*
	protected function flowgetfields($lx)
	{
		$zrows = m($this->subtable)->getall('mid='.$this->id.'','*','`sort`');
		$arr  = array();
		foreach($zrows as $k=>$rs1){
			$arr['sitemid_'.$rs1['itemid'].''] = $rs1['pitem'];
		}
		
		return $arr;
	}*/
	
	private function addotherfield()
	{
		$frs = $this->fieldsarra[0];
		$sid   = $this->rs['leixing'];
		$zrows = m($this->subtable)->getall('mid='.$sid.'','*','`sort`');
		foreach($zrows as $k=>$rs1){
			$frs['name'] 		= $rs1['pitem'];
			$frs['fieldstype'] 	= $rs1['zxren'];
			$frs['islu'] 	= '1';
			$frs['fields'] 		= 'sitemid_'.$rs1['id'];
			
			$this->fieldsarra[] = $frs;
			$this->fieldsarr[]  = $frs;
		}
	}
	
	public function flowrsreplace($rs, $lx=0)
	{
		
		if($lx==1 || $lx==3){
			$rs = $this->flowrsreplaceedit($rs);
		}
		
		return $rs;
	}
	
	protected function flowzuofeibill($sm)
	{
		//$this->update('`leixing`=`leixing`-1', $this->rs['leixing']);
		$this->updaterenshu($this->rs['leixing']);
		m($this->subtable)->delete('`mid`='.$this->id.'');
	}
	
	
	
	public function flowxiangfields(&$fields)
	{
		$fields['base_name'] 	 = '填写人';
		$fields['base_deptname'] = '填写人部门';
		return $fields;
	}
	
	protected function flowbillwhere($uid, $lx)
	{
		$where		= '';
		$leixingid 	= (int)$this->rock->post('leixingid');
		if($leixingid>0)$where='and `leixing`='.$leixingid.'';
		
		return array(
			'keywhere' => $where,
		);
	}
}