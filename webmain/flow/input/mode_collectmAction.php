<?php
/**
*	此文件是流程模块【collectm.信息收集】对应控制器接口文件。
*/ 
class mode_collectmClassAction extends inputAction{
	
	/**
	*	重写函数：保存前处理，主要用于判断是否可以保存
	*	$table String 对应表名
	*	$arr Array 表单参数
	*	$id Int 对应表上记录Id 0添加时，大于0修改时
	*	$addbo Boolean 是否添加时
	*	return array('msg'=>'错误提示内容','rows'=> array()) 可返回空字符串，或者数组 rows 是可同时保存到数据库上数组
	*/
	protected function savebefore($table, $arr, $id, $addbo){
		$rows['type'] = 2; //必须为2
		
		if($arr['fenlei']=='0'){
			$dbs = m('admin');
			$rows['leixing'] = $dbs->rows($dbs->gjoin($arr['runrenid'], 'ud', 'where'));
		}
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
	
	public function beizhustring()
	{
		return '<span style="color:gray">类型是外部收集，字段类型仅支持文本框，文本域，日期类型，单选框，复选框的类型。</span>';
	}
	
	public function createouturlAjax()
	{
		header("Content-type:image/png");
		$urls= $this->rock->getouturl();
		$id  = (int)$this->get('id');
		m('planm')->update('`state`=1', $id);
		$url = ''.$urls.'?m=login&a=collect&mid='.$id.'';
		$img = c('qrcode')->show($url);
		echo $img;
	}
}	
			