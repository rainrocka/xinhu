<?php
class viewClassAction extends Action
{
	public function loaddataAjax()
	{
		$id = (int)$this->get('id');
		$setid	= (int)$this->get('mid');
		$arr['data'] 		= m('flow_extent')->getone($id);
		$arr['wherelist'] 	= m('flow_where')->getall('setid='.$setid.'','id,name','sort');
		$arr['fieldsarr'] 	= m('flow_element')->getrows('mid='.$setid.' and `iszb`=0','name,fields','`sort`');
		echo json_encode($arr);
	}
	
	public function afterstroesss($table,$rows)
	{
		foreach($rows as $k=>$rs){
			$rows[$k]['modename'] = $this->db->getmou('[Q]flow_set','name',$rs['modeid']);
			$rows[$k]['whereid']  = $this->db->getmou('[Q]flow_where','name',$rs['whereid']);
		}
		return array(
			'rows'=>$rows,
			'modearr' => m('mode')->getmodearr(),
		);
	}
	
	public function flowview_savebefore($table, $das)
	{
		$setid = $das['modeid'];
		$where = $this->jm->base64decode($das['wherestr']);
		if($where=='all')return '';
		$where  = m('where')->getstrwhere($where);
		$stable = m('flow_set')->getmou('`table`', $setid);
		$where  = '`id`=0 and '.str_replace('{asqom}','', $where);
		$sql    = 'select * from `[Q]'.$stable.'` a where '.$where.'';
		$bool 	= $this->db->query($sql, false);
		if(!$bool){
			return '条件不能使用:'.$this->db->errorlast.'';
		}
	
	}
	
	public function autographAction()
	{
		
	}
}