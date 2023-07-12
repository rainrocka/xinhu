<?php
//表单元素管理
class flow_flowelementClassModel extends flowModel
{
	protected $flowcompanyidfieds	= 'none'; 
	
	
	public function iseditqx()
	{
		if($this->adminid==1)return true;
		return parent::iseditqx();
	}
	
	public function isdeleteqx()
	{
		if($this->adminid==1)return true;
		return parent::isdeleteqx();
	}
	
	
	public function isaligndata()
	{
		$arr[] = array('value'=>'0','name'=>'居中');
		$arr[] = array('value'=>'1','name'=>'居左');
		$arr[] = array('value'=>'2','name'=>'居右');
		return $arr;
	}
	
	public function iseditlxdata()
	{
		$arr[] = array('value'=>'0','name'=>'不可编辑');
		$arr[] = array('value'=>'1','name'=>'所有人');
		$arr[] = array('value'=>'2','name'=>'仅管理员');
		$arr[] = array('value'=>'3','name'=>'仅admin');
		return $arr;
	}
	
	public $checkarr = array('islu','isbt','iszs','islb','ispx','issou','isonly','isdr');
	public function flowrsreplace($rs,$lx=0)
	{
		if($rs['iszb']=='0'){
			$rs['iszb'] = '<font color=#ff6600>主表</font>';
		}else{
			$rs['iszb'] = '第'.$rs['iszb'].'个子表';
		}
		$rs['isalign'] = $this->rock->valtoname($this->isaligndata(), $rs['isalign']);
		if($rs['iseditlx']=='0'){
			$rs['iseditlx'] = '';
		}else{
			$rs['iseditlx'] = $this->rock->valtoname($this->iseditlxdata(), $rs['iseditlx']);
		}
		
		/*
		if($lx==0)foreach($this->checkarr as $fid){
			if(isset($rs[$fid])){
				if($rs[$fid]=='1'){
					$rs[$fid]='√';
				}else{
					$rs[$fid]='';
				}
			}
		}
		*/
		return $rs;
	}
	
	public function flowbillwhere($uid, $lx)
	{
		$where 	= 'and 1=2';
		$mkid = (int)$this->rock->post('mkid','0');
		if($mkid>0)$where='and `mid`='.$mkid.'';
		return array(
			'order' => '`iszb`,`sort`',
			'where' => $where
		);
	}
}