<?php 
/**
* 	最新系统推送1.9.7后
*	软件：信呼OA
*	最后更新：2021-10-09
*/
class JPushChajian extends Chajian{


	//-------------最新原生app推送app是1.2.3版本 和 最新app+---------------
	public function push($title, $desc, $cont, $palias)
	{

		$uids		= $palias['uids'];
		$alias2019	= $palias['alias2019'];
		$pushuids	= $palias['pushuids']; //可以推送的用户ID
		$xmpush		= c('xmpush');
		$hwpush		= c('hwpush');
		$getui		= c('getui');
		
		//可推送判断
		$ketualia	= array();
		foreach($alias2019 as $ali1){
			$ali1aa = explode('|', $ali1);
			$_uid	= $ali1aa[2];
			if(in_array($_uid, $pushuids))$ketualia[] = $ali1;
		}
		$alias2019 = $ketualia;
		
		
		//$this->rock->debugs($palias,'pushalias');//判断能不能推送，打印这个
		
		$xharr = array(
			'uids'  => $uids,
			'title' => $this->rock->jm->base64encode($title),
			//'cont'  => $this->rock->jm->base64encode($cont),
			'desc'  => $desc,
			'systype'=> getconfig('systype')
		);
		
		if(!$alias2019)return;
		
		$getuiand = $getuiios = $mybyarr = $xmarr = $hwarr = $iosar = $puarr = array();
		foreach($alias2019 as $k=>$ali1){
			$ali1aa = explode('|', $ali1);
			$regid  = $ali1aa[0];
			$_web   = $ali1aa[1];
			if(contain($_web,'custpile')){
				$mybyarr[] =  $k; //3
			}else if(contain($_web,'getui')){
				if(contain($_web,'iphone')){
					$getuiios[] =  $k; //3
				}else{
					$getuiand[] =  $k; //3
				}
			}else if(contain($_web,'mi')){
				$xmarr[] 	=  $k; //0
			}else if(contain($_web,'huawei')){
				$hwarr[] 	=  $k; //3
			}else if(contain($_web,'iphone')){	
				$iosar[] 	=  $k; //0
			}else{
				$puarr[]	= $ali1;
			}
		}
		
		$mymsg = '';
		$desc = $this->rock->jm->base64decode($desc);
		if($xmarr){
			if($xmpush->sendbool()){
				$vstr = $this->getVal($alias2019, $xmarr, 0);
				$msg  = $xmpush->androidsend($vstr, $title, $desc);
				if($msg)$mymsg.=chr(10).$msg;
			}else{
				$vsta = $this->getVala($alias2019, $xmarr);
				foreach($vsta as $v)$puarr[] = $v;
			}
		}
		
		if($hwarr){
			if($hwpush->sendbool()){
				$vstr = $this->getVal($alias2019, $hwarr, 3);
				$msg  = $hwpush->androidsend($vstr, $title, $desc);
				if($msg)$mymsg.=chr(10).$msg;
			}else{
				$vsta = $this->getVala($alias2019, $hwarr);
				foreach($vsta as $v)$puarr[] = $v;
			}
		}
		
		if($iosar){
			if($xmpush->jpushiosbool()){
				$vstr = $this->getVal($alias2019, $iosar, 0);
				$msg  = $xmpush->jpushiossend($vstr, $title, $desc);
				if($msg)$mymsg.=chr(10).$msg;
			}else{
				$vsta = $this->getVala($alias2019, $iosar);
				foreach($vsta as $v)$puarr[] = $v;
			}
		}
		
		if($getuiand){
			if($getui->isandroid()){
				$vstr = $this->getVal($alias2019, $getuiand, 3);
				$msg  = $getui->push($vstr, $title, $desc);
				if($msg)$mymsg.=chr(10).$msg;
			}else{
				$vsta = $this->getVala($alias2019, $getuiand);
				foreach($vsta as $v)$puarr[] = $v;
			}
		}
		
		if($getuiios){
			if($getui->isios()){
				$vstr = $this->getVal($alias2019, $getuiios, 3);
				$msg  = $getui->push($vstr, $title, $desc);
				if($msg)$mymsg.=chr(10).$msg;
			}else{
				$vsta = $this->getVala($alias2019, $getuiios);
				foreach($vsta as $v)$puarr[] = $v;
			}
		}
		
		if($mybyarr){
			if($getui->sendbool()){
				$vstr = $this->getVal($alias2019, $mybyarr, 3);
				$msg  = $getui->push($vstr, $title, $desc);
				if($msg)$mymsg.=chr(10).$msg;
			}else{
				$msg  = '自己编译未配置推送';
				$mymsg.=chr(10).$msg;
			}
		}
		
		if($mymsg)$this->rock->debugs($mymsg, 'mypush');
	
		
		//需要官网隧道
		if($puarr){
			$xharr['alias2019']	= join(',', $puarr);
			$runurl = c('xinhu')->geturlstr('jpushplat', $xharr);
			c('curl')->getcurl($runurl);
		}
	}
	
	
	private function getVal($alias2019, $new, $oi)
	{
		$stv = array();
		foreach($new as $j){
			$stra = explode('|', $alias2019[$j]);
			if(isset($stra[$oi]))$stv[]= $stra[$oi];
		}
		return $stv;
	}

	private function getVala($alias2019, $new)
	{
		$stv = array();
		foreach($new as $j){
			$stv[] = $alias2019[$j];
		}
		return $stv;
	}
}