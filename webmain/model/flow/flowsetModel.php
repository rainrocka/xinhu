<?php
//流程模块列表
class flow_flowsetClassModel extends flowModel
{
	protected $flowcompanyidfieds	= 'none'; 
	public $modedata = array();
	public function initModel()
	{
		$this->modedata = array('','顺序流程','顺序前置流程','自由流程','选择流程','自定义流程');
	}
	
	public function iseditqx()
	{
		if(getconfig('systype')=='demo')return false;
		if($this->adminid==1)return true;
		return parent::iseditqx();
	}
	
	public function isdeleteqx()
	{
		if(getconfig('systype')=='demo')return false;
		if($this->rs['type']=='系统')return false;
		if($this->adminid==1)return true;
		return parent::isdeleteqx();
	}
	
	public function flowmodedata()
	{
		$arr[] = array('value'=>'0','name'=>'无流程');
		$arr[] = array('value'=>'1','name'=>'顺序流程(按照预设好的步骤一步一步审核)');
		$arr[] = array('value'=>'2','name'=>'顺序前置流程(出现重复人审核自动跳过)');
		if($this->isshouquan()){
			$arr[] = array('value'=>'3','name'=>'自由流程(每步都需要由申请人指定哪个步骤)');
			$arr[] = array('value'=>'4','name'=>'选择流程(当下一步出现多步骤需要指定哪个步骤)');
			$arr[] = array('value'=>'5','name'=>'自定义流程(由申请人自己定义审批人员)');
		}
		return $arr;
	}
	
	public function isflowlxdata()
	{
		$arr[] = array('value'=>'0','name'=>'在原来流程上');
		$arr[] = array('value'=>'1','name'=>'重头走审批');
		return $arr;
	}
	
	public function iscsdata()
	{
		$arr[] = array('value'=>'0','name'=>'不开启');
		$arr[] = array('value'=>'1','name'=>'开启(可选抄送对象)');
		$arr[] = array('value'=>'2','name'=>'开启(必须选择抄送对象)');
		return $arr;
	}
	
	public function lbztxsdata()
	{
		$arr[] = array('value'=>'0','name'=>'默认');
		$arr[] = array('value'=>'1','name'=>'必须显示');
		$arr[] = array('value'=>'2','name'=>'不要显示');
		return $arr;
	}
	
	private function isshouquan()
	{
		$key = getconfig('authorkey');
		if(!isempt($key) && $this->rock->isjm($key)){
			return true;
		}else{
			return false;
		}
	}
	
	
	public function flowrsreplace($rs,$lx=0)
	{
		if($rs['isflow']==0){
			$rs['isflow']='';
		}else{
			$rs['isflow']= arrvalue($this->modedata, $rs['isflow']);
		}
		$rs['isflowlx'] = $this->rock->valtoname($this->isflowlxdata(), $rs['isflowlx']);
		$rs['iscs'] 	= $this->rock->valtoname($this->iscsdata(), $rs['iscs']);
		$rs['lbztxs'] 	= $this->rock->valtoname($this->lbztxsdata(), $rs['lbztxs']);
		
		return $rs;
	}
}