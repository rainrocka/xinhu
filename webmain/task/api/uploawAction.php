<?php 
class uploawClassAction extends apiAction
{
	public function initAction()
	{
		$this->display= false;
	}
	
	/**
	*	上传文件
	*/
	public function upfileAction()
	{
		if(!$_FILES)exit('sorry!');
		$upimg	= c('upfile');
		$maxsize= (int)$this->get('maxsize', $upimg->getmaxzhao());//上传最大M
		$uptypes= 'jpg|png|docx|doc|pdf|xlsx|xls|zip|rar';
		$upimg->initupfile($uptypes, ''.UPDIR.'|'.date('Y-m').'', $maxsize);
		$upses	= $upimg->up('file');
		if(!is_array($upses))exit($upses);
		$arr 	= c('down')->uploadback($upses);
		$arr['autoup'] = (getconfig('qcloudCos_autoup') || getconfig('alioss_autoup')) ? 1 : 0; //是否上传其他平台
		return $arr;
	}
}