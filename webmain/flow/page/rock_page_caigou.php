<?php
/**
*	模块：caigou.物品采购
*	说明：自定义区域内可写你想要的代码
*	来源：流程模块→表单元素管理→[模块.物品采购]→生成列表页
*/
defined('HOST') or die ('not access');
?>
<script>
$(document).ready(function(){
	{params}
	var modenum = 'caigou',modename='物品采购',isflow=1,modeid='28',atype = params.atype,pnum=params.pnum,modenames='采购物品',listname='Z29vZG0:';
	if(!atype)atype='';if(!pnum)pnum='';
	var fieldsarr = [],fieldsselarr= [],chufarr= [];
	
	<?php
	include_once('webmain/flow/page/rock_page.php');
	?>
	
//[自定义区域start]

if(pnum=='all'){
	c.setcolumns('state',{
		renderer:function(v,d){
			if(d.states!='1' && d.status=='1')v+=',<a href="javascript:;" onclick="rukuope{rand}('+d.id+')">去入库</a>';
			return v;
		}
	});
	rukuope{rand}=function(id){
		addtabs({url:'main,goods,churuku,type=0,mid='+id+',kind=0,kindname=采购入库','num':'rukuopt'+id+'',name:''+id+'.'+modename+'操作'});
	}
}

//[自定义区域end]
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
		<td style="padding-right:10px;" id="tdleft_{rand}" nowrap><button id="addbtn_{rand}" class="btn btn-primary" click="clickwin,0" disabled type="button"><i class="icon-plus"></i> <?=lang('新增')?></button></td>
		
		<td><select class="form-control" style="width:110px;border-top-right-radius:0;border-bottom-right-radius:0;padding:0 2px" id="fields_{rand}"></select></td>
		<td><select class="form-control" style="width:60px;border-radius:0px;border-left:0;padding:0 2px" id="like_{rand}"><option value="0"><?=lang('包含')?></option><option value="1"><?=lang('等于')?></option><option value="2"><?=lang('大于')?><?=lang('等于')?></option><option value="3"><?=lang('小于')?><?=lang('等于')?></option><option value="4"><?=lang('不包含')?></option></select></td>
		<td><select class="form-control" style="width:130px;border-radius:0;border-left:0;display:none;padding:0 5px" id="selkey_{rand}"><option value="">-<?=lang('请选择')?>-</option></select><input class="form-control" style="width:130px;border-radius:0;border-left:0;padding:0 5px" id="keygj_{rand}" placeholder="<?=lang('关键字')?>"><input class="form-control" style="width:130px;border-radius:0;border-left:0;padding:0 5px;display:none;" id="key_{rand}" placeholder="<?=lang('关键字')?>/<?=lang('申请人')?>/<?=lang('单号')?>">
		</td>
		<td><select class="form-control" style="width:120px;border-left:0;border-radius:0;" id="selstatus_{rand}"><option value="">-<?=lang('全部')?><?=lang('状态')?>-</option><option style="color:blue" value="0"><?=lang('待处理')?></option><option style="color:green" value="1"><?=lang('已审核')?></option><option style="color:red" value="2"><?=lang('不同意')?></option><option style="color:#888888" value="5"><?=lang('已作废')?></option><option style="color:#17B2B7" value="23"><?=lang('退回')?></option></select></td>
		<td>
			<div style="white-space:nowrap">
			<button style="border-right:0;border-radius:0;border-left:0" class="btn btn-default" click="searchbtn" type="button"><?=lang('搜索')?></button><button class="btn btn-default" id="downbtn_{rand}" type="button" style="padding-left:8px;padding-right:8px;border-top-left-radius:0;border-bottom-left-radius:0"><i class="icon-angle-down"></i></button> 
			</div>
		</td>
		<td  width="90%" style="padding-left:10px"><div id="changatype{rand}" class="btn-group"></div></td>
	
		<td align="right" id="tdright_{rand}" nowrap>
			<span style="display:none" id="daoruspan_{rand}"><button class="btn btn-default" click="daoru,1" type="button"><?=lang('导入')?></button>&nbsp;&nbsp;&nbsp;</span><button class="btn btn-default" style="display:none" id="daobtn_{rand}" disabled click="daochu" type="button"><?=lang('导出')?> <i class="icon-angle-down"></i></button> 
		</td>
	</tr>
	</table>
</div>
<div class="blank10"></div>
<div id="viewcaigou_{rand}"></div>
<!--HTMLend-->