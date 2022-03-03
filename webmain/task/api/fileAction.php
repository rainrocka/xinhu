<?php 
/**
*	文件下载相关接口-用于app
*/
class fileClassAction extends apiAction
{
	
	/**
	*	获取文件信息
	*/
	public function getfileAction()
	{
		$id 	= (int)$this->post('id',0);
		$rs 	= m('file')->getone($id);
		if(!$rs)$this->showreturn('', '文件不存在1', 201);
		$path 	= $rs['filepath'];
		if(isempt($path) || !file_exists($path))$this->showreturn('', '文件['.$rs['filename'].']不存在', 202);
		$rs['filetype']	= m('file')->getmime($rs['fileext']);
		$this->showreturn($rs);
	}
	
	/**
	*	下载文件
	*/
	public function downAction()
	{
		$id  = (int)$this->jm->gettoken('id');
		m('file')->show($id);
	}
	
	/**
	*	获取文件信息
	*/
	public function getfilenewAction()
	{
		$id 	= (int)$this->post('id',0);
		$rs 	= m('file')->getone($id);
		if(!$rs)$this->showreturn('', '文件不存在1', 201);
		$path 	= $rs['filepath'];
		if(isempt($rs['filenum'])){
			if(substr($path,0,4)!='http' && !file_exists($path))
				$this->showreturn('', '文件['.$rs['filename'].']不存了', 202);
		}
		$rs['filetype']	= m('file')->getmime($rs['fileext']);
		$rs['downurl']	= '';
		
		
		$this->showreturn($rs);
	}
	
	/**
	*	生成水印图片
	*/
	public function shuiyinAction()
	{
		header("Content-type:image/png");
		$font	= 'upload/data/simsun.ttc';
		if(!file_exists($font))$font = 'C:/Windows/Fonts/simsun.ttc';
		$w 		= 110;
		$im		= imagecreatetruecolor($w,$w);
		$bg		= imagecolorallocate($im,255,255,255);
		imagefill($im,0,0,$bg);	//添加背景颜色
		$str 	= $this->adminname;
		//$str 	= '信呼开发团队'; //改成你要的文字去掉注释
		$black	= imagecolorallocate($im,220,220,220);
		if(file_exists($font)){
			imagettftext($im, 14,45, 20, $w-10,$black, $font, $str);
		}else{
			imagestring($im,5,5, $w-50,$this->adminuser,$black);
		}
		imagepng($im);
		imagedestroy($im);
	}
}