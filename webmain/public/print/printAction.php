<?php 
class printClassAction extends Action{
	
	
	public function defaultAction()
	{
		$table = $this->get('table');
		$num   		= $this->get('modenum');
		$modename   = $this->jm->base64decode($this->get('modename'));
		$this->assign('table', $table);
		$this->assign('modename', $modename);
	}
	
	
}