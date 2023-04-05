<?php
class yingyongClassAction extends Action
{
	public function yingyongdataAjax()
	{
		$rows = m('im_group')->getall('`type`=2','*','`sort`');
		$arrs = array();
		/*
		foreach($rows as $k=>$rs){
			$sub 	= m('im_group')->getall('`type`=2 and pid='.$rs['id'].'','*','`sort`');
			$rs['leave'] 	= 1;
			$arrs[] 		= $rs;
			foreach($sub as $k1=>$rs1){
				$rs1['leave'] = 2;
				$arrs[] 	   = $rs1;
			}
		}*/
		echo json_encode(array('rows'=>$rows));
	}
	
	public function getdataAjax()
	{
		$rows = m('im_group')->getall('`type`=2','id,name,face,num,valid','`sort`');
		echo json_encode($rows);
	}
	
	public function loaddataAjax()
	{
		$id = (int)$this->get('id');
		$arr['data'] = m('im_group')->getone($id);
		echo json_encode($arr);
	}
	
	public function beforesave($table, $cans, $id)
	{
		$msg = '';
		$num = $cans['num'];
		if(m($table)->rows("`num`='$num' and `id`<>$id")>0)$msg='编号['.$num.']已存在';
		return array('msg'=>$msg);
	}
	
	public $rows;
	public function menudataAjax()
	{
		$this->rows	= array();
		$mid		= (int)$this->get('mid');
		$agentnum	= m('im_group')->getmou('num',$mid);
		$where 		= "and `mid`='$mid'";
		$this->getmenu(0, 1, $where);
		$modeid 	= (int)m('flow_set')->getmou('id',"`num`='$agentnum'");
		$wherearr	= m('flow_where')->getrows("setid='$modeid' and `num` is not null and `status`=1",'`name`,`num`','`pnum`,`sort`');
		$barr[]		= array('num'=>'','name'=>'-选择-');
		foreach($wherearr as $k=>$rs){
			$wherearr[$k]['name'] = ''.$rs['num'].'.'.$rs['name'].'';
			$barr[] = $wherearr[$k];
		}
		
		echo json_encode(array(
			'totalCount'=> 0,
			'rows'		=> $this->rows,
			'agentnum'	=> $agentnum,
			'modeid'	=> $modeid,
			'wherearr'	=> $barr,
		));
	}
	
	private function getmenu($pid, $oi, $wh='')
	{
		$db		= m('im_menu');
		$menu	= $db->getall("`pid`='$pid' $wh order by `sort`",'*');
		foreach($menu as $k=>$rs){
			$sid			= $rs['id'];
			$rs['level']	= $oi;
			$rs['stotal']	= $db->rows("`pid`='$sid'  $wh ");
			$this->rows[] = $rs;
			
			$this->getmenu($sid, $oi+1, $wh);
		}
	}
	
	public function yingyongbefore($table)
	{
		return array(
			'order' => '`valid` desc,`sort` asc'
		);
	}
	
	public function yingyongafter($table, $rows)
	{
		foreach($rows as $k=>$rs){
			if($rs['valid']=='0')$rows[$k]['status']=0;
		}
		return array(
			'rows' => $rows
		);
	}
	
	public function createyingAjax()
	{
		$bh 	= $this->get('bh');
		$mrs	= m('flow_set')->getone("`num`='$bh'");
		if(!$mrs)return returnerror('编号为“'.$bh.'”的模块不存在');
		$wherrows = m('flow_where')->getall('`setid`='.$mrs['id'].' and ifnull(`num`,\'\')<>\'\'','*','`sort`');
		if(!$wherrows)return returnerror('模块“'.$mrs['name'].'”未创建流程模块条件');
		
		$db 	= m('im_group');
		$dbs 	= m('im_menu');
		if($db->rows("`num`='$bh' and `type`=2")>0)return returnerror('编号为“'.$bh.'”的应用已经存在了');
		$sort	= 100*$mrs['id'];
		$dsrs 	= $db->getone("`types`='".$mrs['type']."'",'*','`sort` desc');
		if($dsrs)$sort = (int)$dsrs['sort']+1;
		
		$udb['name']  = $mrs['name'];
		$udb['types'] = $mrs['type'];
		$udb['num']   = $mrs['num'];
		$udb['url']   = 'auto';
		$udb['type']   = 2;
		$udb['face']  = 'images/logo.png';
		$udb['sort']   = $sort;
		$mid 	= $db->insert($udb);
		
		$xdar0	  = $xdar1 = array();
		foreach($wherrows as $k=>$rs){
			if(!$xdar0 && isempt($rs['pnum']))$xdar0 = $rs;
			if(!$xdar1 && !isempt($rs['pnum']))$xdar1 = $rs;
		}
		$iar['mid']  = $mid;
		if($xdar0){
			$iar['name'] = $xdar0['name'];
			$iar['url']  = $xdar0['num'];
			$iar['type'] = 0;
			$iar['sort'] = 0;
			$dbs->insert($iar);
		}
		if($xdar1){
			$iar['name'] = $xdar1['name'];
			$iar['url']  = $xdar1['num'];
			$iar['receid']  = 'u'.$this->adminid.'';
			$iar['recename']  = $this->adminname;
			$iar['type'] = 0;
			$iar['sort'] = 1;
			$dbs->insert($iar);
		}
		$iar['name'] = '＋新增';
		$iar['url']  = 'add';
		$iar['receid']  = '';
		$iar['recename']  = '';
		$iar['type'] = 1;
		$iar['sort'] = 2;
		$dbs->insert($iar);
		
		return returnsuccess();
	}
}