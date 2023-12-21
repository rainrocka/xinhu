<?php
class flow_caigouClassModel extends flowModel
{
	public $minwidth	= 600;//子表最小宽
	
	
	private $goodsobj;

	public function initModel()
	{
		$this->goodsobj = m('goods');
	}
	
	//审核完成处理,是否直接出入库
	protected function flowcheckfinsh($zt){
		if($zt==1)m('goods')->chukuopts($this->id, $this->modename);
	}
	
	//作废或删除时
	protected function flowzuofeibill($sm)
	{
		//删除入库详情的
		m('goodss')->delete("`mid`='$this->id'");
	}

	
	//子表数据替换处理
	protected function flowsubdata($rows, $lx=0){
		$db = m('goods');
		foreach($rows as $k=>$rs){
			$one = $db->getone($rs['aid']);
			if($one){
				$name = $one['name'];
				if(!isempt($one['xinghao']))$name.='('.$one['xinghao'].')';
				if($lx==1)$rows[$k]['aid'] = $name; //1展示时
				$rows[$k]['temp_aid'] = $name;
			}
		}
		return $rows;
	}
	
	//$lx,0默认,1详情展示，2列表显示
	public function flowrsreplace($rs, $lx=0)
	{
		$rs['states']= $rs['state'];
		$rs['state'] = $this->goodsobj->crkstate($rs['state']);
		//读取物品
		if($lx==2){
			$rs['wupinlist'] = $this->goodsobj->getgoodninfo($rs['id'], 1);
		}
		return $rs;
	}
}