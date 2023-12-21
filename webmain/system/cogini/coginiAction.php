<?php
class coginiClassAction extends Action
{
	
	
	public function phpiniAction()
	{
	}
	
	public function phpinishowAction()
	{
	}
	
	public function phpinisaveAction()
	{
		if(getconfig('systype')=='demo')return returnerror('演示禁止操作');
		$path = trim($this->post('path'));
		if(!$path || !file_exists($path))return returnerror('无权限设置，请找到对应文件修改'.$path.'');
		if(substr($path, -4)!='.ini')return returnerror('无效');
		$cont = @file_get_contents($path);
		if(!$cont)return returnerror('无权限获取'.$path.'内容');
		$str  = '';
		$conta= explode("\n", $cont);
		foreach($conta as $k=>$s){
			if($k>0)$str.=chr(10);
			$s2 = $s;
			$s1 = '';
			if($s){
				$s1 = $this->phpinisave($s,'upload_max_filesize');
				if(!$s1)$s1 = $this->phpinisave($s,'post_max_size');
				if(!$s1)$s1 = $this->phpinisave($s,'memory_limit');
				if(!$s1)$s1 = $this->phpinisave($s,'max_execution_time');
				if(!$s1)$s1 = $this->phpinisave($s,'max_input_vars');
				if(!$s1)$s1 = $this->phpinisave($s,'html_errors', false, 'On');
				if(!$s1)$s1 = $this->phpinisave($s,'error_log', true);
				if(!$s1)$s1 = $this->phpinisave($s,'upload_tmp_dir', true);
			}
			if($s1)$s2 = $s1;
			$str.=$s2;
		}
		$bo = @file_put_contents($path, $str);
		if(!$bo)return returnerror('无权限写入'.$path.'');
		return returnsuccess();
	}
	
	private function phpinisave($s1,$key,$ybo=false,$sv='')
	{
		if(contain($s1,$key) && contain($s1,'=')){
			$val = trim($this->post($key));
			if(!$val)$val = $sv;
			if(!$val)return '';
			if($ybo)$val = '"'.$val.'"';
			return ''.$key.' = '.$val.'';
		}
		return '';
	}
	
	
	
	public $publicfile = 'include/langlocal/langtxt/';
	public function langcogAction()
	{
		
		return '授权版可用';
	}
	
	
}