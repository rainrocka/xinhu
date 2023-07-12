<?php
/**
*	公共列表页模版
*	$pageparams 这个是参数
*/
defined('HOST') or die ('not access');
$modenum = arrvalue($pageparams,'bh');
if(!$modenum)exit('404 modenum is empty');

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
	
	$shoukey = lang('关键字');
	if($flow->isflow>0)$shoukey = ''.lang('关键字').'/'.lang('申请人').'/'.lang('单号').'';
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
	<table width="100%" id="tools<?=$modenum?>_{rand}">
	<tr>
		<td style="padding-right:10px;" id="tdleft_{rand}" nowrap><button id="addbtn_{rand}" class="btn btn-primary" click="clickwin,0" disabled type="button"><i class="icon-plus"></i> <?=lang('新增')?></button></td>
		
		<td tdlx="sou"><select class="form-control" style="width:110px;border-top-right-radius:0;border-bottom-right-radius:0;padding:0 2px" id="fields_{rand}"></select></td>
		<td tdlx="sou"><select class="form-control" style="width:60px;border-radius:0px;border-left:0;padding:0 2px" id="like_{rand}"><option value="0"><?=lang('包含')?></option><option value="1"><?=lang('等于')?></option><option value="2"><?=lang('大于')?><?=lang('等于')?></option><option value="3"><?=lang('小于')?><?=lang('等于')?></option><option value="4"><?=lang('不包含')?></option></select></td>
		<td tdlx="sou"><select class="form-control" style="width:130px;border-radius:0;border-left:0;display:none;padding:0 5px" id="selkey_{rand}"><option value="">-<?=lang('请选择')?>-</option></select><input class="form-control" style="width:130px;border-radius:0;border-left:0;padding:0 5px" id="keygj_{rand}" placeholder="<?=lang('关键字')?>"><input class="form-control" style="width:130px;border-radius:0;border-left:0;padding:0 5px;display:none;" id="key_{rand}" placeholder="<?=$shoukey?>">
		</td>
		<?php 
		$lbztxs = $flow->moders['lbztxs'];
		$showzt = false;
		if($isflow>0)$showzt = true;
		if($lbztxs==1)$showzt = true;
		if($lbztxs==2)$showzt = false;
		if($showzt){
			$ztarr	= $flow->getstatusarr();
			$zthtml = '<td><select class="form-control" style="width:120px;border-left:0;border-radius:0;" id="selstatus_{rand}"><option value="">-'.lang('全部').''.lang('状态').'-</option>';
			foreach($ztarr as $zt=>$ztv){
				if($isflow==0 && $zt==23)continue;
				$vals = str_replace('?','', $ztv[0]);
				$zthtml .= '<option style="color:'.arrvalue($ztv, 1).'" value="'.$zt.'">'.lang($vals).'</option>';
			}
			$zthtml .= '</select></td>';
			$zthtml	 = str_replace('?','', $zthtml);
			echo $zthtml;
		}
		?>
		<td tdlx="sou">
			<div style="white-space:nowrap">
			<button style="border-right:0;border-radius:0;border-left:0" class="btn btn-default" click="searchbtn" type="button"><?=lang('搜索')?></button><button class="btn btn-default" id="downbtn_{rand}" type="button" style="padding-left:8px;padding-right:8px;border-top-left-radius:0;border-bottom-left-radius:0"><i class="icon-angle-down"></i></button> 
			</div>
		</td>
		<td id="tdcenter_{rand}" width="90%" style="padding-left:10px"><div id="changatype{rand}" class="btn-group"></div></td>
	
		<td align="right" id="tdright_{rand}" nowrap>
			<span style="display:none" id="daoruspan_{rand}"><button class="btn btn-default" click="daoru,1" type="button"><?=lang('导入')?></button>&nbsp;&nbsp;&nbsp;</span><button class="btn btn-default" style="display:none" id="daobtn_{rand}" disabled click="daochu" type="button"><?=lang('导出')?> <i class="icon-angle-down"></i></button> 
		</td>
	</tr>
	</table>
</div>
<div class="blank10"></div>
<div id="view<?=$modenum?>_{rand}"></div>
<!--HTMLend-->