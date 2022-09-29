<?php
/**
*	此文件是流程模块【planm.工作计划】对应控制器接口文件。
*/ 
class mode_planmClassAction extends inputAction{
	
	/**
	*	重写函数：保存前处理，主要用于判断是否可以保存
	*	$table String 对应表名
	*	$arr Array 表单参数
	*	$id Int 对应表上记录Id 0添加时，大于0修改时
	*	$addbo Boolean 是否添加时
	*	return array('msg'=>'错误提示内容','rows'=> array()) 可返回空字符串，或者数组 rows 是可同时保存到数据库上数组
	*/
	protected function savebefore($table, $arr, $id, $addbo){
		
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
	
	public function chageleixingAjax()
	{
		$lx = (int)$this->get('lx');
		$str = '';
		$barr['lx'] = $lx;
		if($lx==1)$str=''.date('Y').'年度工作计划';
		if($lx==2)$str=''.date('Y').'第{?}季度工作计划';
		if($lx==3)$str=''.date('Y').'年'.date('m').'月份工作计划';
		if($lx==4)$str=''.date('Y').'年'.date('m').'月份第{?}周工作计划';
		
		$barr['name'] = $str;
		
		return returnsuccess($barr);
	}
	
	public function leixingdata()
	{
		
		return $this->flow->leixingdata();
	}
	
	public function savezhixingAjax()
	{
		$str = $this->post('str');
		$mid = (int)$this->post('mid');
		$arr = json_decode($str, true);
		$dbs = m('plans');
		$state = (int)$this->post('ztstate');;
		foreach($arr as $id=>$rs){
			$dbs->update(array(
				'zxren' => $rs['zxren'],
				'zxrenid' => $rs['zxrenid'],
				'zxtime' => $rs['zxtime'],
			),$id);
		}
		m('planm')->update('`state`='.$state.'', $mid);
		return returnsuccess($arr);
	}
}	
			