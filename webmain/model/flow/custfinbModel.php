<?php
//客户付款单
class flow_custfinbClassModel extends flowModel
{
	public $statearrs,$statearrf;
	public $delbool = 0;
	public function initModel(){
		$this->statearrs		= c('array')->strtoarray('未收款|red,已收款|green');
		$this->statearrf		= c('array')->strtoarray('未付款|red,已付款|green');
	}
	
	public function flowrsreplace($rs)
	{
		if($rs['id']==0)return $rs;
		
		$starrr			= array('收','付');
		$rs['paystatus']	= $rs['ispay'];
		$ispay 			= '<font color=red>未'.$starrr[$rs['type']].'款</font>';
		if($rs['ispay']==1)$ispay = '<font color=green>已'.$starrr[$rs['type']].'款</font>';
		$rs['ispay']	 = $ispay;
		$rs['type']	 	 = ''.$starrr[$rs['type']].'款单';
		
		$htid			 = $rs['htid'];
		$url 	= '';
		if($htid>0)$url  = $this->getxiangurl('custract', $htid, 'auto');
		if($htid<0)$url  = $this->getxiangurl('custxiao', 0-$htid, 'auto');
		if(arrvalue($rs,'xgid') && arrvalue($rs,'xgnum'))$url  = $this->getxiangurl($rs['xgnum'], $rs['xgid'], 'auto');
		if($url && !isempt($rs['htnum']))
			$rs['htnum'] = '<a href="javascript:;" onclick="js.open(\''.$url.'\')">'.$rs['htnum'].'</a>';
		
		if($rs['custid']>0){
			//$url  = $this->getxiangurl('customer', $rs['custid'], 'auto');
			//$rs['custname'] = '<a href="javascript:;" onclick="js.open(\''.$url.'\')">'.$rs['custname'].'</a>';
		}
		
		$jzid	= arrvalue($rs,'jzid');
		if($jzid>0){
			$url  = $this->getxiangurl('finjizhi', $jzid, 'auto');
			$rs['jzid'] = '<a href="javascript:;" onclick="js.open(\''.$url.'\')">已生成</a>';
		}else if($jzid=='-1'){
			$rs['jzid'] = '<font color=#aaaaaa>不需要</font>';
		}else{
			$rs['jzid'] = '';
		}
		
		return $rs;
	}
	
	//操作菜单操作
	protected function flowoptmenu($ors, $arr)
	{
		//标识已付款处理
		if($ors['num']=='pay'){
			$ispay = 0;
			$paydt = arrvalue($arr,'fields_paydt', $this->rock->now);
			if(!isempt($paydt))$ispay = 1;
			$this->update("`ispay`='$ispay',`paydt`='$paydt'", $this->id);
			m('crm')->ractmoney($this->rs['htid']);
		}
		
		//复制一单
		if($ors['num']=='noupfuzhe'){
			$jine = $this->rock->number(trim($arr['sm']));
			$uarr = $this->getone($this->id);
			$money= $uarr['money'];
			unset($uarr['id']);
			$uarr['createname'] = $this->adminname;
			$uarr['createid']   = $this->adminid;
			$uarr['money']   	= $jine;
			$this->insert($uarr);
			$this->update('`money`=`money`-'.$jine.'', $this->id);
		}
	}
	
	//操作菜单操作之前
	protected function flowoptmenubefore($ors, $arr)
	{
		if($ors['num']=='noupfuzhe'){
			$sm = trim($arr['sm']);
			if(!$sm || !is_numeric($sm))return '输入“'.$sm.'”的不是金额';
			$sm = $this->rock->number($sm);
			if(floatval($sm)<=0)return '输入金额必须大于0';
			if(floatval($sm) >= floatval($this->rs['money']))return '输入的金额不能超过'.$this->rs['money'].'';
		}
	}
	
	protected function flowbillwhere($uid, $lx)
	{
		$month	= $this->rock->post('month');
		$where 	= '';
		if($month!=''){
			$where.=" and `dt` like '$month%'";
		}

		return array(
			'where' => $where,
			'order' => '`optdt` desc'
		);
	}
	
	protected function flowdeletebill($sm)
	{
		$this->delbool++;
		$xgid 	= arrvalue($this->rs,'xgid');
		$xgnum 	= arrvalue($this->rs,'xgnum');
		$sid 	= $this->id;
		if($xgnum && $xgid && $this->delbool==1){
			$sflow = m('flow:'.$xgnum.'')->initbase($xgnum);
			$drows = $this->getall("`xgnum`='$xgnum' and `xgid`='$xgid'");//相关联一起删
			foreach($drows as $k=>$rs1){
				$mid = $rs1['id'];
				$this->loaddata($mid, false);
				$this->deletebill($sm, false);
			}
			$sflow->update('`payid`=0', $xgid);
		}
		$this->id = $sid;
	}
	
	protected function flowgetoptmenu($opt,$bo=false)
	{
		if($opt=='noupcreatejz' && $bo){
			return m('mode')->iscun('finjizhi');
		}
	}
	
	public function flowlistscript()
	{
		include_once('webmain/flow/page/rock_page_custfina_script.php');
		return '';
	}
}