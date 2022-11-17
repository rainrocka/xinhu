<?php

//健康报备
class flow_yqhealthyClassModel extends flowModel
{
	
	public function flowrsreplace($rs, $lx=0)
	{
		$rs['stateval']=$rs['state'];
		if($rs['state']=='0')$rs['state']='<font color="green">绿码</font>';
		if($rs['state']=='1')$rs['state']='<font color="#ff6600">黄码</font>';
		if($rs['state']=='2')$rs['state']='<font color="red">红码</font>';
		
		return $rs;
	}
}