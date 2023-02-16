<?php

//供应商
class flow_custgysClassModel extends flowModel
{
	
	public function flowrsreplace($rs, $lx=0)
	{
		$rs['statusval']=$rs['status'];
		if($rs['status']=='0')$rs['status']='<font color="gray">停用</font>';
		if($rs['status']=='1')$rs['status']='<font color="green">启用</font>';
		
		return $rs;
	}
}