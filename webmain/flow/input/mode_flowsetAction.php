<?php
/**
*	此文件是流程模块【flowset.流程模块列表】对应控制器接口文件。
*/ 
class mode_flowsetClassAction extends inputAction{
	
	public $pobj;
	protected function savebefore($table, $arr, $id, $addbo){
		include_once('webmain/main/flow/flowAction.php');
		$this->pobj = new flowClassAction();
		return $this->pobj->flowsetsavebefore($table, $arr);
	}
	
		
	protected function saveafter($table, $arr, $id, $addbo){
		$this->pobj->flowsetsaveafter($table, $arr);
	}
	
	public function typedata()
	{
		$arr = array();
		$rows = $this->db->getall('select `type` from `[Q]flow_set` group by `type` order by `sort`');
		foreach($rows as $k=>$rs)$arr[] = array('value'=>'','name'=>$rs['type']);
		return $arr;
	}
	
	public function getothernrAjax()
	{
		$mkid 	= (int)$this->get('mkid','0');
		$ind  	= (int)$this->get('ind','0');
		$bh   	= 'flowelement';
		$atype  = 'all'; 
			
		//读取数据
		$flow  = m('flow')->initflow($bh);//初始化模块
		$cont  = $flow->getrowstable($atype, 'and `mid`='.$mkid.'', 100,'name,fields,fieldstype,islu,isbt,iszb');//读取表格数据
		return $cont;
	}
}	
			