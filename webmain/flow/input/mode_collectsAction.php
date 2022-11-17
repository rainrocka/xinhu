<?php
/**
*	此文件是流程模块【collects.信息收集表】对应控制器接口文件。
*/ 
class mode_collectsClassAction extends inputAction{
	
	/**
	*	重写函数：保存前处理，主要用于判断是否可以保存
	*	$table String 对应表名
	*	$arr Array 表单参数
	*	$id Int 对应表上记录Id 0添加时，大于0修改时
	*	$addbo Boolean 是否添加时
	*	return array('msg'=>'错误提示内容','rows'=> array()) 可返回空字符串，或者数组 rows 是可同时保存到数据库上数组
	*/
	protected function savebefore($table, $arr, $id, $addbo){
		$rows['type'] = 3; //必须为3
		
	
		return array(
			'rows' => $rows
		);
	}
	
	/**
	*	重写函数：保存后处理，主要保存其他表数据
	*	$table String 对应表名
	*	$arr Array 表单参数
	*	$id Int 对应表上记录Id
	*	$addbo Boolean 是否添加时
	*/	
	protected function saveafter($table, $arr, $id, $addbo){
		
	}
	
	protected function storeaftersss($table, $rows, $barr=array())
	{
		$arr = array();
		$mid = (int)$this->get('leixingid','0');
		if($mid>0 && isset($barr['listinfo'])){
			$fieldsarr = $barr['listinfo']['fieldsarr'];
			
		}
		return $arr;
	}
	
	public function collectstotal_before()
	{
		$mid = (int)$this->get('mid','0');
		$this->mid = $mid;
		$key = $this->post('key');
		$this->zdarr = m('plans')->getall('mid='.$this->mid.'','*','`sort`');
		$this->zdobj = array();
		$where = '';
		if($key)$where=" and (`optname` like '%".$key."%'";
		$wher1 = '';
		foreach($this->zdarr as $k=>$rs){
			$flx = $rs['zxren'];
			$this->zdobj[$rs['id']] = $flx;
		}
		if($key)$wher1=" a.`zxren` like '%".$key."%'";
		if($wher1)$where.=' or `id` in(select a.`mid` from `[Q]plans` a left join `[Q]planm` b on a.`mid`=b.`id` where b.`type`=3 and b.`leixing`='.$mid.' and '.$wher1.')';
		if($key)$where.=')';
		return 'and `type`=3 and `leixing`='.$mid.''.$where.'';
	}
	
	public function collectstotal_after($table, $rows)
	{
		$dbs  = m('plans');
		$arows= $this->zdarr;
		$mrs  = m($table)->getone($this->mid);

		if($this->loadci==1){
			$farr = array();
			if($mrs['fenlei']=='0'){
				$farr[] = array(
					'text' => '填写人',
					'dataIndex' => 'optname'
				);
			}
			foreach($arows as $k=>$rs){
				$sarr = array(
					'text' => $rs['pitem'],
					'dataIndex' => 'items_'.$rs['id'].'',
					'filestype' => $rs['zxren'],
					'sortable' => false
				);
				if($rs['zxren']=='uploadfile' || $rs['zxren']=='textarea')$sarr['align']='left';
				$farr[] = $sarr;
			}
			$farr[] = array(
				'text' => '填写时间',
				'dataIndex' => 'optdt',
				'sortable' => true
			);
			$farr[] = array(
				'text' => '状态',
				'dataIndex' => 'status',
				'sortable' => true
			);
			$barr['columns'] = $farr;
		}
		$faobj = m('file');
		foreach($rows as $k=>$rs){
			$arows= $dbs->getall('mid='.$rs['id'].'','*','`sort`');
			foreach($arows as $k1=>$rs1){
				$flx = arrvalue($this->zdobj, $rs1['itemid']);
				$val = $rs1['zxren'];
				if($flx=='uploadimg' && !isempt($val)){
					$val = '<img src="'.$val.'" onclick="$.imgview({\'url\':this.src})" height="60">';
				}
				if($flx=='uploadfile' && !isempt($val)){
					$val = $faobj->getstr('', '', 0, "`id` in($val)");
				}
				$rows[$k]['items_'.$rs1['itemid'].''] = $val;
			}
			$status = '';
			if($rs['status']=='0')$status='<font color=blue>待审核</font>';
			if($rs['status']=='1')$status='<font color=green>已审核</font>';
			if($rs['status']=='2')$status='<font color=red>未通过</font>';
			$rows[$k]['status'] = $status;
		}
		$barr['rows'] = $rows;
		return $barr;
	}
}	
			