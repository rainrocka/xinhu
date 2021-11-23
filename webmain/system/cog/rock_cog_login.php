<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	{params};

	var a = $('#veiw_{rand}').bootstable({
		tablename:'logintoken',celleditor:true,sort:'moddt',dir:'desc',modedir:'{mode}:{dir}',checked:true,fanye:true,
		storebeforeaction:'logintokenbefore',
		columns:[{
			text:'姓名',dataIndex:'name'
		},{
			text:'用户ID',dataIndex:'uid',sortable:true
		},{
			text:'来源',dataIndex:'cfrom',sortable:true
		},{
			text:'IP',dataIndex:'ip'
		},{
			text:'浏览器',dataIndex:'web'
		},{
			text:'Device',dataIndex:'device'
		},{
			text:'app推送',dataIndex:'ispush',type:'checkbox',sortable:true
		},{
			text:'在线状态',dataIndex:'online',type:'checkbox',sortable:true
		},{
			text:'最后在线',dataIndex:'moddt',sortable:true
		},{
			text:'ID',dataIndex:'id',sortable:true
		}],
		rendertr:function(d){
			var s = '';
			if(d.online==0)s='style="color:#aaaaaa"';
			return s;
		}
	});
	

	var c = {
		delss:function(){
			a.del({url:js.getajaxurl('dellogin','{mode}','{dir}'),checked:true});
		},
		search:function(){
			var s=get('key_{rand}').value;
			a.setparams({key:s},true);
		},
		daochu:function(){
			a.exceldown();
		}
	};
	js.initbtn(c);
});
</script>


<div>


<table width="100%"><tr>
	<td>
		<input class="form-control" style="width:300px" id="key_{rand}"   placeholder="来源/姓名/浏览器">
	</td>
	<td nowrap style="padding-left:10px">
		<button class="btn btn-default" click="search" type="button">搜索</button>&nbsp; 
		<button class="btn btn-default" click="daochu,1" type="button">导出</button>
	</td>
	
	
	
	<td width="80%"></td>
	<td align="right" nowrap>
	
		<button class="btn btn-danger" id="del_{rand}" click="delss" type="button"><i class="icon-trash"></i> 删除</button>
	</td>
</tr>
</table>
</div>
<div class="blank10"></div>
<div id="veiw_{rand}"></div>