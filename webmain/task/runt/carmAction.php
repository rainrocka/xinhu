<?php
class carmClassAction extends runtAction
{
	//车辆提醒，每天运行(此文件2022-10-18)弃用
	public function runAction()
	{
		//return m('flow')->initflow('carms')->todocarms($this->runrs['todoid']);
		return 'success';
	}
	
}