<?php
class flow_planmClassModel extends flowModel
{
	public function leixingdata()
	{
		$arr[] = array('value'=>'1','name'=>'年度');
		$arr[] = array('value'=>'2','name'=>'季度');
		$arr[] = array('value'=>'3','name'=>'月度');
		$arr[] = array('value'=>'4','name'=>'周');
		$arr[] = array('value'=>'5','name'=>'项目');
		$arr[] = array('value'=>'0','name'=>'其他');
		return $arr;
	}
	
	private function leixingval($lx)
	{
		$data = $this->leixingdata();
		$str  = $lx;
		foreach($data as $kv=>$rv){
			if($rv['value']==$lx){
				$str = $rv['name'].'计划';
				break;
			}
		}
		return $str;
	}
	
	public function flowrsreplace($rs, $lx=0){
		
		$rs['leixing'] = $this->leixingval($rs['leixing']);
		$str = '';
		if($rs['startdt']>$this->rock->now){
			$str = '未开始';
		}else if($rs['enddt']<$this->rock->now){
			
		}else{
			$str = '执行中';
		}
		if($rs['state']==1)$str='已完成';
		if($rs['state']==2)$str='执行中';
		if($rs['state']==0)$str='待执行';
		if($rs['enddt']<$this->rock->now)$str.=',<font color=gray>已截止</font>';
		if($rs['startdt']>$this->rock->now)$str='未开始';
		
		$rs['state'] = $str;
		return $rs;
	}
	
	//自定义审核人读取
	protected function flowcheckname($num){
		$sid = '';
		$sna = '';
		if($num=='zhixi'){
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
	
	protected function flowbillwhere($uid, $lx)
	{
		$where = '';
		if($lx=='bumen'){
			$dbs   	= m('dept');
			$detpids= '';
			$drows 	= $dbs->getall($this->rock->dbinstr('headid', $uid)); //读取我负责的部门
			foreach($drows as $k=>$rs)$detpids.=','.$rs['id'].'';
			if($detpids!=''){
				$detpids = substr($detpids,1);
				for($i=1;$i<=2;$i++){
					$drows   = $dbs->getall('`pid` in('.$detpids.')');
					foreach($drows as $k=>$rs)$detpids.=','.$rs['id'].'';
					if(!$drows)break;
				}
			}
			if(!$detpids){
				$where='and 1=2';
			}else{
				$drows   = $dbs->getall('`id` in('.$detpids.')');
				$whewea  = array();
				foreach($drows as $k=>$rs){
					$whewea[] = $this->rock->dbinstr('runrenid', 'd'.$rs['id'].'');
				}
				$where = 'and ('.join(' or ', $whewea).')';
			}
		}
		return $where;
	}
	
	//判断是不是再执行人里面。
	private $runboolpdid = 0;
	public function runboolpd()
	{
		if($this->rs['status']!=1 
		|| ($this->rs['enddt']<$this->rock->now && $this->rs['state']==1)
		|| $this->rs['startdt']>$this->rock->now)return false;
		if($this->runboolpdid>0){
			if($this->runboolpdid==1)return true;
			if($this->runboolpdid==2)return false;
		}
		$bo = $this->adminmodel->containjoin($this->rs['runrenid'], $this->adminid);
		$this->runboolpdid = $bo ? 1: 2;
		return $bo;
	}
	
	//是否可执行
	protected function flowdatalog($arr)
	{
		$runbool = $this->runboolpd();
		
		return array(
			'modelujs' => $runbool,
		);
	}
	
	//子表数据替换处理
	protected function flowsubdata($rows, $lx=0){
		if($lx!=1 || !$rows || !$this->runboolpd())return $rows;
		$inputobj = c('input');
		foreach($rows as $k=>$rs){
			$rows[$k]['zxren'] = $inputobj->inputchangeuser(array(
				'type'	=> 'changedeptusercheck',
				'changerange' => $this->rs['runrenid'],
				'name' 	=> 'zhixing_zxren_'.$rs['id'].'',
				'id' 	=> 'zhixing_zxrenid_'.$rs['id'].'',
				'value' => $rs['zxren'],
				'valueid' => $rs['zxrenid'],
				'title' => '执行人'
			));
			$rows[$k]['zxtime'] = '<input name="zhixing_zxtime_'.$rs['id'].'" onclick="js.datechange(this,\'datetime\')" readonly value="'.$rs['zxtime'].'" class="inputs datesss">';
		}
		$zt = $this->rs['state'];
		$rows[] = array(
			'pitem'=>'执行状态',
			'zxren'=>'<select id="ztstate" class="inputs"><option value="0">待执行</option><option value="2"'.(($zt==2)?' selected':'').'>执行中</option><option value="1"'.(($zt==1)?' selected':'').'>已完成</option></select>',
			'zxtime'=>'<div align="left"><button type="button" onclick="submittijiao(this)" class="webbtn">保存</button></div>'
		);
		return $rows;
	}
	
	//统计未完成
	public function getwwctotals($uid)
	{
		$where = m('admin')->getjoinstr('runrenid', $uid,0,1);
		$where = "`status`=1 and `state`<>1 and `startdt`<'{$this->rock->now}' and `type`=0 $where";
		return m('planm')->rows($where);
	}
}