<?php
/**
*	公共列表页模版
*	$pageparams 这个是参数
*/
defined('HOST') or die ('not access');
$modenum = arrvalue($pageparams,'bh');
if(!$modenum)exit('没有参数bh');

$flow 	= m('flow:'.$modenum.'')->initbase($modenum);
$isflow = $flow->isflow;
?>
<script>
$(document).ready(function(){
	{params}
	var modenum = '<?=$modenum?>',modename='<?=$flow->moders['name']?>',isflow=<?=$isflow?>,modeid='<?=$flow->moders['id']?>',atype = params.atype,pnum=params.pnum,modenames='',listname='';
	if(!atype)atype='';if(!pnum)pnum='';
	var fieldsarr = [],fieldsselarr= [],chufarr= [];
	
	
	<?php
	include_once('webmain/flow/page/rock_page.php');
	if(method_exists($flow,'flowlistscript'))echo $flow->flowlistscript($pageparams);
	
	$path = 'webmain/flow/page/rock_page_'.$modenum.'_script.php';
	if(file_exists($path))include_once($path);
	
	$shoukey = '关键字';
	if($flow->isflow>0)$shoukey = '关键字/申请人/单号';
	?>
	

	c.initpagebefore();
	js.initbtn(c);
	var a = $('#view'+modenum+'_{rand}').bootstable(bootparams);
	c.init();
	
});
</script>
<!--SCRIPTend-->
<!--HTMLstart-->
<div>
	<table width="100%">
	<tr>
		<td style="padding-right:10px;" id="tdleft_{rand}" nowrap><button id="addbtn_{rand}" class="btn btn-primary" click="clickwin,0" disabled type="button"><i class="icon-plus"></i> 新增</button></td>
		
		<td><select class="form-control" style="width:110px;border-top-right-radius:0;border-bottom-right-radius:0;padding:0 2px" id="fields_{rand}"></select></td>
		<td><select class="form-control" style="width:60px;border-radius:0px;border-left:0;padding:0 2px" id="like_{rand}"><option value="0">包含</option><option value="1">等于</option><option value="2">大于等于</option><option value="3">小于等于</option><option value="4">不包含</option></select></td>
		<td><select class="form-control" style="width:130px;border-radius:0;border-left:0;display:none;padding:0 5px" id="selkey_{rand}"><option value="">-请选择-</option></select><input class="form-control" style="width:130px;border-radius:0;border-left:0;padding:0 5px" id="keygj_{rand}" placeholder="关键词"><input class="form-control" style="width:130px;border-radius:0;border-left:0;padding:0 5px;display:none;" id="key_{rand}" placeholder="<?=$shoukey?>">
		</td>
		<?php 
		$lbztxs = $flow->moders['lbztxs'];
		$showzt = false;
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
			echo $zthtml;
		}
		?>
		<td>
			<div style="white-space:nowrap">
			<button style="border-right:0;border-radius:0;border-left:0" class="btn btn-default" click="searchbtn" type="button">搜索</button><button class="btn btn-default" id="downbtn_{rand}" type="button" style="padding-left:8px;padding-right:8px;border-top-left-radius:0;border-bottom-left-radius:0"><i class="icon-angle-down"></i></button> 
			</div>
		</td>
		<td  width="90%" style="padding-left:10px"><div id="changatype{rand}" class="btn-group"></div></td>
	
		<td align="right" id="tdright_{rand}" nowrap>
			<span style="display:none" id="daoruspan_{rand}"><button class="btn btn-default" click="daoru,1" type="button">导入</button>&nbsp;&nbsp;&nbsp;</span><button class="btn btn-default" style="display:none" id="daobtn_{rand}" disabled click="daochu" type="button">导出 <i class="icon-angle-down"></i></button> 
		</td>
	</tr>
	</table>
</div>
<div class="blank10"></div>
<div id="view<?=$modenum?>_{rand}"></div>
<!--HTMLend-->