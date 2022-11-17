<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	{params}
	var mid = params.mid;
	var a = $('#veiw_{rand}').bootstable({
		tablename:'planm',checked:false,fanye:true,statuschange:false,
		url:publicmodeurl('collects','',{'mid':mid}),defaultorder:'`optdt` desc',
		storeafteraction:'collectstotal_after',storebeforeaction:'collectstotal_before',
		columns:[{
			text:'填写时间',dataIndex:'optdt',sortable:true
		},{
			text:'状态',dataIndex:'status',sortable:true
		}],
		loadbefore:function(d){
			if(d.columns)a.setColumns(d.columns);
		},
		itemdblclick:function(d){
			openxiangs('详情','collects',d.id);
		}
	});
	var c = {
		
		daochu:function(){
			a.exceldown();
		},
		search:function(){
			var key = get('key_{rand}').value;
			a.setparams({key:key}, true);
		},
		prints:function(){
			pirnttablelist(a, nowtabs.name);
		}
	};

	c{rand} = c;
	js.initbtn(c);
});
</script>

<div>
<table width="100%">
<tr>


<td width="95%" align="left">
	
		<div class="input-group" style="width:220px;">
			<input class="form-control" id="key_{rand}" onkeydown="if(event.keyCode==13)c{rand}.search()" placeholder="关键词">
			<span class="input-group-btn">
				<button class="btn btn-default" click="search" type="button"><i class="icon-search"></i></button>
			</span>
		</div>

	</td>

<td align="right" nowrap>
	<button class="btn btn-default"  click="daochu" type="button">导出</button>&nbsp;
	<button class="btn btn-default"  click="prints" type="button">打印</button>&nbsp;
</td>
</tr>
</table>
</div>
<div class="blank10"></div>
<div id="veiw_{rand}"></div>
