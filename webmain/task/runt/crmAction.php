<?php
class crmClassAction extends runtAction
{
	//每天运行(此文件2022-10-18)弃用
	public function runAction()
	{
		//客户合同到期
		//m('flow')->initflow('custract')->custractdaoqi();
		
		//自动放入公海
		//m('flow')->initflow('customer')->addgonghai();
		
		//计划跟进提醒
		//if(m('mode')->iscun('custplan'))m('flow')->initflow('custplan')->plantodo();
		
		return 'success';
	}
	
}