<?php
class dayClassAction extends runtAction
{
	//每天运行一次
	public function runAction()
	{
		
		if($this->moderock('work'))m('flow')->initflow('work')->tododay(); //任务到期提醒
		
		if($this->moderock('daiban'))m('flow')->initflow('daiban')->tododay(); //流程待办处理提醒
		
		if($this->moderock('meet'))m('flow')->initflow('meet')->createmeet(); //会议生成
		
		$this->crmrun();
		
		return 'success';
	}
	
	//http://127.0.0.1/app/xinhu/task.php?m=day|runt&a=getitle
	public function getitleAction()
	{
		return TITLE;
	}
	
	public function crmrun()
	{
		//客户提醒
		if($this->moderock('custract'))m('flow')->initflow('custract')->custractdaoqi();
		
		//自动放入公海
		if($this->moderock('customer'))m('flow')->initflow('customer')->addgonghai();
		
		//计划跟进提醒
		if($this->moderock('custplan'))m('flow')->initflow('custplan')->plantodo();
		
		//车辆提醒
		if($this->moderock('carms'))m('flow')->initflow('carms')->todocarms('');
	}
}