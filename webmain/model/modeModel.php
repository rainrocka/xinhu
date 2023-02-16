<?php
class modeClassModel extends Model
{
	public function initModel()
	{
		$this->settable('flow_set');
	}
	public function getmodearr($whe='')
	{
		$where 	= 'status=1 '.$whe.'';
		if($whe=='all')$where='1=1';
		$arr 	= $this->getall($where,'`id`,`num`,`name`,`table`,`type`,`isflow`,`status`','sort');
		$typea = array();
		foreach($arr as $k=>$rs){
			$arr[$k]['name'] = ''.$rs['id'].'.'.$rs['name'].'('.$rs['num'].')';
			$typea[$rs['type']][] = $arr[$k];
		}
		$arr  = array();
		foreach($typea as $k1=>$srow){
			foreach($srow as $k2=>$rs2)$arr[] = $rs2;
		}
		return $arr;
	}
	
	//判断模块是否开启存在
	public function iscun($num)
	{
		$to = $this->rows("`num`='$num' and `status`=1");
		return $to==1;
	}
	
	public function getmoderows($uid, $sww='')
	{
		$where	= m('admin')->getjoinstr('receid', $uid);
		$arr 	= $this->getall("`status`=1 and `type`<>'系统' $sww $where",'`id`,`num`,`name`,`table`,`type`,`isflow`,`isscl`','`sort`');
		return $arr;
	}
	
	public function getmodemyarr($uid=0, $whe='')
	{
		$where = '';
		if($whe!='')$where = $whe;
		if($uid>0)$where = m('admin')->getjoinstr('receid', $uid);
		$arr = $this->getall('status=1 and isflow>0 '.$where.'','`id`,`name`,`type`','sort');
		return $arr;
	}
	
	//生成列表页面
	public function createlistpage($modeid, $lxss=0, $glx=0, $inrs=null)
	{
		if(is_array($modeid)){
			$mors	= $modeid;
		}else{
			$mors 	= m('flow_set')->getone($modeid,'`id`,`table`,`names`,`num`,`name`,`isflow`,`lbztxs`');
		}
		$num	= $mors['num'];
		$path 	= ''.P.'/flow/page/rock_page_'.$num.'.php';
		
		//分单位自己生成
		if($glx==0 && getconfig('platdwnum') && COMPANYNUM){
			$dbbase = str_replace('_company_'.COMPANYNUM.'','', DB_BASE);
			$ones 	= $this->db->getone(''.$dbbase.'.`[Q]flow_set`', "`num`='$num'");
			if($ones)return 'ok';
		}
		
		//当是一键生成时，不存在就不要生成了
		if($lxss==1 && !file_exists($path))return 'none';
		
		$flow	= m('flow')->initflow($num);
		$chufarr= array();
		if(method_exists($flow, 'flowxiangfields'))$chufarr = $flow->flowxiangfields($chufarr);
		
		$table	= $mors['table'];
		$name	= $mors['name'];
		$modeid	= (int)$mors['id'];
		$isflow	= (int)$mors['isflow'];
		$lbztxs	= $mors['lbztxs'];
		$showzt	= false;

		
		$farr[] = array('name'=>arrvalue($chufarr, 'base_name', '申请人'),'fields'=>'base_name');
		$farr[] = array('name'=>arrvalue($chufarr, 'base_deptname', '申请人部门'),'fields'=>'base_deptname');
		$farr[] = array('name'=>arrvalue($chufarr, 'base_sericnum', '单号'),'fields'=>'sericnum');
		$farrs 	= m('flow_element')->getall("`mid`='$modeid'",'`fields`,`name`,`fieldstype`,`ispx`,`isalign`,`iszb`,`islb`,`issou`,`data`','`iszb`,`sort`');
		$inpub  = c('input');
		$zbarr	= $zbnamea = array();
		if(!isempt($mors['names']))$zbnamea = explode(',', $mors['names']);
		foreach($farrs as $k=>$rs){
			if($glx==1 && $rs['issou']=='1' && ($rs['fieldstype']=='select' || $rs['fieldstype']=='rockcombo')){
				$rs['store'] =$inpub->getdatastore($rs['fieldstype'],$inrs,$rs['data']);
			}
			if($rs['iszb']=='0'){
				$farr[] = $rs; //主表
				if($rs['fields']=='status')$showzt=true;
			}else{
				if($rs['issou']=='1'){
					$xus = floatval($rs['iszb'])-1;
					$zbn = arrvalue($zbnamea, $xus);
					if($zbn)$rs['name'] = ''.$zbn.'.'.$rs['name'].'';
					$rs['fields'] = 'zb'.$xus.'_'.$rs['fields'].'';
					$zbarr[] = $rs;
				}
			}
		}
	
		$jgpstr 	= '<!--SCRIPTend-->';
		$hstart 	= '<!--HTMLstart-->';
		$hendts 	= '<!--HTMLend-->';
		$oldcont 	= @file_get_contents($path);
		$autoquye	= $this->rock->getcai($oldcont,'//[自定义区域start]','//[自定义区域end]');
		
		//$isdaoru 	= m('flow_element')->rows("`mid`='$modeid' and `isdr`=1");
		$drstrbtn	= '';
		//if($isdaoru>0){
			$drstrbtn	= "<span style=\"display:none\" id=\"daoruspan_{rand}\"><button class=\"btn btn-default\" click=\"daoru,1\" type=\"button\">导入</button>&nbsp;&nbsp;&nbsp;</span>";
		//}
		
		//读取流程模块的条件
		$whtml 		= '<div id="changatype{rand}" class="btn-group"></div>';
		$zthtml		= '';
		if($isflow>0)$showzt = true;
		if($lbztxs==1)$showzt = true;
		if($lbztxs==2)$showzt = false;
		if($showzt){
			$ztarr	= $flow->getstatusarr();
			$zthtml = '<td><select class="form-control" style="width:120px;border-left:0;border-radius:0;" id="selstatus_{rand}"><option value="">-全部状态-</option>';
			foreach($ztarr as $zt=>$ztv){
				if($isflow==0 && $zt==23)continue;
				$zthtml .= '<option style="color:'.arrvalue($ztv, 1).'" value="'.$zt.'">'.$ztv[0].'</option>';
			}
			$zthtml .= '</select></td>';
			$zthtml	 = str_replace('?','', $zthtml);
		}
		$fselarr	= array();
		$bear		= $this->db->getrows('[Q]option',"`num` like 'columns_".$num."_%'",'`num`,`value`');
		foreach($bear as $k2=>$rs2)$fselarr[$rs2['num']]=$rs2['value'];
		$placeholder= '关键字';
		if($isflow>0)$placeholder= '关键字/申请人/单号';
		if($glx==1){
			return array(
				'isflow' => $isflow,
				'fieldsarr' => $farr,
				'fieldzarr' => $zbarr,
				'fieldsselarr' => $fselarr,
				'chufarr' => $chufarr,
				'modename' => $name,
				'modenames' => $this->rock->repempt($mors['names']),
			);
		}
$html= "".$hstart."
<div>
	<table width=\"100%\">
	<tr>
		<td style=\"padding-right:10px;\" id=\"tdleft_{rand}\" nowrap><button id=\"addbtn_{rand}\" class=\"btn btn-primary\" click=\"clickwin,0\" disabled type=\"button\"><i class=\"icon-plus\"></i> 新增</button></td>
		
		<td><select class=\"form-control\" style=\"width:110px;border-top-right-radius:0;border-bottom-right-radius:0;padding:0 2px\" id=\"fields_{rand}\"></select></td>
		<td><select class=\"form-control\" style=\"width:60px;border-radius:0px;border-left:0;padding:0 2px\" id=\"like_{rand}\"><option value=\"0\">包含</option><option value=\"1\">等于</option><option value=\"2\">大于等于</option><option value=\"3\">小于等于</option><option value=\"4\">不包含</option></select></td>
		<td><select class=\"form-control\" style=\"width:130px;border-radius:0;border-left:0;display:none;padding:0 5px\" id=\"selkey_{rand}\"><option value=\"\">-请选择-</option></select><input class=\"form-control\" style=\"width:130px;border-radius:0;border-left:0;padding:0 5px\" id=\"keygj_{rand}\" placeholder=\"关键词\"><input class=\"form-control\" style=\"width:130px;border-radius:0;border-left:0;padding:0 5px;display:none;\" id=\"key_{rand}\" placeholder=\"".$placeholder."\">
		</td>
		$zthtml
		<td>
			<div style=\"white-space:nowrap\">
			<button style=\"border-right:0;border-radius:0;border-left:0\" class=\"btn btn-default\" click=\"searchbtn\" type=\"button\">搜索</button><button class=\"btn btn-default\" id=\"downbtn_{rand}\" type=\"button\" style=\"padding-left:8px;padding-right:8px;border-top-left-radius:0;border-bottom-left-radius:0\"><i class=\"icon-angle-down\"></i></button> 
			</div>
		</td>
		<td  width=\"90%\" style=\"padding-left:10px\">$whtml</td>
	
		<td align=\"right\" id=\"tdright_{rand}\" nowrap>
			".$drstrbtn."<button class=\"btn btn-default\" style=\"display:none\" id=\"daobtn_{rand}\" disabled click=\"daochu\" type=\"button\">导出 <i class=\"icon-angle-down\"></i></button> 
		</td>
	</tr>
	</table>
</div>
<div class=\"blank10\"></div>
<div id=\"view".$num."_{rand}\"></div>
".$hendts."";		
$str = "<?php
/**
*	模块：".$num.".".$name."
*	说明：自定义区域内可写你想要的代码
*	来源：流程模块→表单元素管理→[模块.".$name."]→生成列表页
*/
defined('HOST') or die ('not access');
?>
<script>
$(document).ready(function(){
	{params}
	var modenum = '".$num."',modename='".$name."',isflow=".$isflow.",modeid='".$modeid."',atype = params.atype,pnum=params.pnum,modenames='".$mors['names']."',listname='".$this->rock->jm->base64encode($table)."';
	if(!atype)atype='';if(!pnum)pnum='';
	var fieldsarr = [],fieldsselarr= [],chufarr= ".json_encode($chufarr).";
	
	<?php
	include_once('webmain/flow/page/rock_page.php');
	?>
	
//[自定义区域start]

$autoquye

//[自定义区域end]
	c.initpagebefore();
	js.initbtn(c);
	var a = $('#view'+modenum+'_{rand}').bootstable(bootparams);
	c.init();
	
});
</script>
".$jgpstr."";	
		$bstrs = '<!--HTML-->';
		if(!isempt($oldcont) && contain($oldcont, $jgpstr) && contain($oldcont, $bstrs)){
			$strarr = explode($jgpstr, $oldcont);
			$nstr 	= $strarr[1];
			$htmlqy = $this->rock->getcai($nstr, $hstart, $hendts);
$rstr 	= "".$hstart."
".$htmlqy."
".$hendts."";
			$nstr 	= str_replace($rstr, '', $nstr);
			$nstr 	= str_replace($bstrs, $html.$bstrs, $nstr);
			$str	.= "\n".$nstr;
		}else{
			$str.= "\n".$html;
		}
		$bo = $this->rock->createtxt($path, $str);
		if(!$bo)$path='';
		return $path;
	}
	
	
	
	
	
	
	
	
	
	
	
	/**
	*	统计
	*/
	public function flowtotal($modeid, $fields, $type)
	{
		
	}
	
	
	
	public function menulist()
	{
		$arr['schedule'] 	= '45';
		$arr['hrsalary'] 	= '184,98,277,276,99,278,102';
		$arr['knowtraim'] = '202,199,200,201';
		$arr['hrcheck'] 	= '274,272,273,244,275';
		$arr['emailm'] 	= '157';
		$arr['meet'] 	= '89,150,151,283';
		$arr['kqdkjl'] 	= '59,92,33,58,60,234,86,88,169,170'; //打卡记录
		$arr['waichu'] 	= '160,159'; //打卡记录
		$arr['kaoqin'] 	= '32,61,53,54,197,55,56,152,153,240,241,243,242,36,87,260,57';
		$arr['kqdw'] 	= '93,94'; 
		$arr['jiaban'] 	= '217'; 
		$arr['daily'] 	= '76,77,195,78,198,231,192,193,194,196'; 
		
		$arr['project'] 	= '79,69,70,72,71,82'; 
		$arr['work'] 		= '66,65,67,83,68,80,81'; 
		$arr['word'] 		= '124,125,203'; 
		$arr['wordxie'] 		= '281'; 
		$arr['wordeil'] 		= '282'; 
		$arr['knowledge'] 		= '134,158'; 
		$arr['knowtiku'] 		= '135,136';
		$arr['news'] 		= '288';
		$arr['wenjuan'] 		= '321';
		
		$arr['wupin'] 		= '28,247,248,30,249,253,31,251,250,319,261,323,252';
		$arr['assetm'] 		= '137,411,412,413';
		$arr['repair'] 		= '413';
		
		$arr['cheliang'] 		= '138,143,214,144,146,215,216';
		$arr['book'] 		= '139,141,145';
		$arr['seal'] 		= '165,166,167';
		$arr['dangan'] 		= '336,337,338';
		
		$arr['gongwen'] 		= '204,239,289,290,232,256,258,291,292,293,306,205,257,259,294,255,233,206,304,305'; 
		
		$arr['crm'] 		= '63,37,64,73,104,105,114,118,126,179,262,299,131,132,75,112,113,123,263,317,110,111,108,109,106,119,115,116,117,107,120,121,122,175,176,177,178,264,302,318,300,301,386,387';
		
		$arr['userinfo'] 		= '85,101,149';
		$arr['userract'] 		= '95';
		$arr['userzheng'] 		= '339';
		$arr['hrdemand'] 		= '265';
		$arr['hrmanshi'] 		= '266';
		$arr['hrpositive'] 		= '96';
		$arr['hrredund'] 		= '97';
		$arr['hrtransfer'] 		= '128';
		$arr['hrtrsalary'] 		= '129';
		$arr['reward'] 		= '130';
		
		$arr['finjishou'] 		= '311,312,316,369';
		$arr['finzhang'] 		= '308';
		$arr['finkemu'] 		= '309';
		$arr['finount'] 		= '310';
		
		$arr['finfybx'] 		= '187,191';
		$arr['finccbx'] 		= '188';
		$arr['finjkd'] 		= '189,218';
		$arr['finhkd'] 		= '190';
		$arr['finpay'] 		= '229';
		$arr['finkai'] 		= '230';
		$arr['finpiao'] 		= '279,280';
		$arr['finyisu'] 		= '325,326,329,327,328';
		
		$arr['finbei'] 	= '330,331,335,333,332,334';
		$arr['wxgzh']	= '180,181,284,285,366';
		$arr['weixinqy']	= '171,172,155,173,174,370';
		$arr['ding']	= '208,209,210,211,212';
		$arr['gongcheng']	= '345,346,347,348,349,350,351,352,353,354,355,356,357,358,359,360,373,374,375,376,377,378,379,380,381,382,383,384,385';
		$arr['officidu']	= '361,362,363,364,365';
		$arr['gong']	= '90,287';
		
		$arr['jxcbase']	= '388,389,390,391,392,393,394,395,396,397,398,399,400,401,402,403,404,405,406,417,418';
		
		$arr['planm']	= '410,407,408,409';
		$arr['collectm']	= '414,415,416';
		$arr['eduxueqi']	= '419,420,421,422,423,424,425,426,427,428,429,430,431,432,433,434,435';
		$arr['yqhealthy']	= '436,437';
		$arr['wyxiaoqu']	= '438,439,440,441,442,443,444,445,446,447,448,449,450,451';
		return $arr;
	}
	
	public function yinglist()
	{
		$arr['kaoqin'] = '29,45,28,30,24,58';
		$arr['work'] = '12';
		$arr['project'] = '44';
		$arr['gongwen'] = '41,42,43';
		$arr['emailm'] = '26';
		$arr['schedule'] = '21';
		$arr['daily'] = '7';
		$arr['meet'] = '4';
		$arr['hrsalary'] = '38';
		$arr['news'] = '52';
		$arr['word'] = '20';
		$arr['wordxie'] = '50';
		$arr['knowledge'] = '27';
		$arr['knowtiku'] = '32';
		$arr['knowtraim'] = '33';
		$arr['crm'] = '18,17,56,19,34,35,36';
		$arr['bianjian'] = '59';
		$arr['gong'] = '3';
		$arr['wyxiaoqu'] = '67,68,69,70';
		return $arr;
	}
}