<?php
/**
*	此文件是流程模块【custfuwu.客户服务】对应控制器接口文件。
*/ 
class mode_custfuwuClassAction extends inputAction{
	
	
	
	protected function savebefore($table, $arr, $id, $addbo){
		$rows['dtype'] = '1';//一定要是1，不能去掉
		return array(
			'rows'=>$rows
		);
	}
	
	
	protected function saveafter($table, $arr, $id, $addbo){
		
	}
}	
			