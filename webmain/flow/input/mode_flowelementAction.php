<?php
/**
*	此文件是流程模块【flowelement.表单元素管理】对应控制器接口文件。
*/ 
class mode_flowelementClassAction extends inputAction{
	
	public $pobj;
	protected function savebefore($table, $arr, $id, $addbo){
		include_once('webmain/main/flow/flowAction.php');
		$this->pobj = new flowClassAction();
		$strs= $this->pobj->elemensavefieldsbefore($table, $arr, $id);
		if($strs)return $strs;
		
		$rows = array();
		if($arr['zdsm'])$rows['zdsm'] 	= htmlspecialchars_decode($arr['zdsm']);
		return array(
			'rows' => $rows
		);
	}
	
	protected function saveafter($table, $arr, $id, $addbo){
		$this->pobj->elemensavefields($table, $arr);
	}
	
	
	public function iszbdata()
	{
		$mkid  = (int)$this->get('mkid','0');
		$mid   = (int)$this->get('mid','0');
		$mkrs  = array();
		$talbe = '';
		if($mid>0){
			$mkid = m('flow_element')->getmou('mid', $mid);
		}
		if($mkid){
			$mkrs = m('flow_set')->getone($mkid);
			$talbe = $mkrs['table'];
		}
		$arr[] = array('value'=>'0','name'=>'主表('.$talbe.')字段');
		if($mkrs){
			$tables = $mkrs['tables'];
			$names  = $mkrs['names'];
			if(!isempt($tables)){
				$tablesa = explode(',', $tables);
				$namesa  = explode(',', $names);
				foreach($tablesa as $k=>$v)$arr[] = array('value'=>$k+1,'name'=>'第个'.($k+1).'子表('.arrvalue($namesa, $k).'.'.$v.')字段');
			}
		}
		return $arr;
	}
	
	public function attrdata()
	{
		$arr[] = array('value'=>'readonly','name'=>'只读');
		$arr[] = array('value'=>'onlyen','name'=>'不能有中文');
		$arr[] = array('value'=>'onlycn','name'=>'必须包含中文');
		$arr[] = array('value'=>'maxhang','name'=>'布局占整行');
		$arr[] = array('value'=>'email','name'=>'邮件格式');
		$arr[] = array('value'=>'mobile','name'=>'中文手机号');
		$arr[] = array('value'=>'number','name'=>'必须是数字');
		$arr[] = array('value'=>'date','name'=>'必须是日期格式如2020-02-02');
		return $arr;
	}
	
	public function devdata()
	{
		$arr[] = array('value'=>'admin','name'=>'{admin}','subname'=>'当前用户姓名');
		$arr[] = array('value'=>'deptname','name'=>'{deptname}','subname'=>'当前用户部门');
		$arr[] = array('value'=>'uid','name'=>'{uid}','subname'=>'当前用户ID');
		$arr[] = array('value'=>'date','name'=>'{date}','subname'=>'当前日期');
		$arr[] = array('value'=>'optdt','name'=>'{optdt}','subname'=>'当前时间');
		$arr[] = array('value'=>'ranking','name'=>'{urs.ranking}','subname'=>'当前用户职位');
		$arr[] = array('value'=>'urs','name'=>'{urs.mobile}','subname'=>'当前用户其他信息字段，mobile改成需要字段名');
		return $arr;
	}
	
	
	protected function storeafter($table, $rows)
	{
		$mkid = (int)$this->post('mkid','0');
		$mkrs = false;
		if($mkid>0)$mkrs = m('flow_set')->getone($mkid);
		if($this->loadci>1)return array(
			'rows' => $rows,
			'mkrs' => $mkrs,
		);
		
		return array(
			'rows' 		=> $rows,
			'modearr' 	=> m('mode')->getmodearr(),
			'mkrs' => $mkrs,
		);
	}
	
	public function fieldsstore()
	{
		$mkid  = (int)$this->get('mkid','0');
		$iszb  = (int)$this->get('iszb','0');
		$mkrs  = m('flow_set')->getone($mkid);
		$table  = $mkrs['table'];
		$tables = $mkrs['tables'];
		if($iszb>0 && !isempt($tables)){
			$tablesa = explode(',', $tables);
			$table   = $tablesa[$iszb-1];
		}
		$farrs = array();
		$farr	= $this->db->gettablefields('[Q]'.$table.'');
		foreach($farr as $k=>$rs){
			$farrs[]= array('value'=>$rs['name'],'name'=>$rs['name'],'subname'=>$rs['explain']);
		}
		return $farrs;
	}
}	
			