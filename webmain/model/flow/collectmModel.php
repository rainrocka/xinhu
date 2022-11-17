<?php
class flow_collectmClassModel extends flowModel
{
	
	
	public function flowrsreplace($rs, $lx=0)
	{
		
		$state = $rs['state'];
		if($state=='0')$rs['state']='<font color=blue>待生成</font>';
		if($state=='1')$rs['state']='<font color=green>已生成</font>';
		
		$rs['fenleival'] = $rs['fenlei'];
		if($rs['fenlei']=='0')$rs['fenlei']='内部';
		if($rs['fenlei']=='1')$rs['fenlei']='<font color=blue>外部</font>';
		
		if($rs['fenleival']=='0'){
			$yixs = $this->rows('`type`=3 and `leixing`='.$rs['id'].' and `isturn`=1');
			$rs['leixing'] = ''.$rs['leixing'].'/'.$yixs.'';
		}
		
		return $rs;
	}

	
	//生成收集表格
	protected function flowoptmenu($ors, $crs)
	{
		if($ors['num']=='noupcreate' && $this->rs['fenlei']=='0'){
			
			$where = $this->adminmodel->gjoin($this->rs['runrenid'], 'ud', 'where');
			$where = '`status`=1 and `workdate`<=\''.substr($this->rs['startdt'],0,10).'\' and '.$where.'';
			$rows  = $this->adminmodel->getall($where);
			$flow  = m('flow')->initflow('collects');
			$shu   = 0;
			foreach($rows as $k=>$rs){
				$shu++;
				$uarr = array(
					'uid' => $rs['id'],
					'optdt' => $this->rock->now,
					'optid' => $rs['id'],
					'optname' => $rs['name'],
					'applydt' => $this->rock->date,
					'status' => 0,
					'type' 	 => 3,
					'isturn' => 0,
					'comid'   => $this->rs['comid'],
					'name' 	  => $this->rs['name'],
					'startdt' => $this->rs['startdt'],
					'enddt'   => $this->rs['enddt'],
					'leixing' => $this->id,
					'psren'   => $this->uname,
					'psrenid' => $this->rs['uid'],
				);
				$mid = $flow->insert($uarr);
				$flow->loaddata($mid, false);
				$flow->submit('保存', $crs['sm']);
				$cont = '名称：'.$uarr['name'].'\n评审人：'.$uarr['psren'].'\n截止时间：'.$uarr['enddt'].'';
				$flow->pushs($rs['id'],$cont, '你有信息收集表待完成');
			}
			$this->update('`state`=1,`leixing`='.$shu.'', $this->id);
		}
		
		//复制
		if($ors['num']=='noupfuzhi'){
			$flow  = m('flow')->initflow('collectm');
			$uarr = array(
				'uid' => $this->adminid,
				'optdt' => $this->rock->now,
				'optid' => $this->adminid,
				'optname' => $this->adminname,
				'applydt' => $this->rock->date,
				'status' 	=> 0,
				'state' 	=> 0,
				'type' 	 	=> 2,
				'isturn' 	=> 0,
				'comid'   => $this->rs['comid'],
				'name' 	  => $this->rs['name'],
				'startdt' => $this->rock->now,
				'enddt'   => date('Y-m-d H:i:s', time()+24*3600),
				'leixing' => $this->rs['leixing'],
				'runren'   => $this->rs['runren'],
				'runrenid' => $this->rs['runrenid'],
				'explain'  => $this->rs['explain'],
				'fenlei'   => $this->rs['fenlei'],
			);
			$mid  = $flow->insert($uarr);
			$dsbs  = m('plans');
			$zrows = $dsbs->getall('mid='.$this->id.'');
			foreach($zrows as $k=>$rs1){
				$iuarr = $rs1;
				unset($iuarr['id']);
				$iuarr['mid'] = $mid;
				$dsbs->insert($iuarr);
			}
			$flow->loaddata($mid, false);
			$flow->submit('保存', $crs['sm']);
		}
	}
	
}