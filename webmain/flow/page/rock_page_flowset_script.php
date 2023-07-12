//<script>

c.optalign = 'left';
bootparams.statuschange = true;
bootparams.checked = true;
var editarr = ['pctx','status','emtx','mctx','wxtx','ddtx','istxset','ispl','islu','isup','ishz'];
for(var i=0;i<editarr.length;i++)c.setcolumns(editarr[i],{type:'checkbox'});

c.getbtnstr('旧版','oldban','','','right');

<?php if(getconfig('rockinzip'))echo "c.getbtnstr('生成安装包','shengsheng','','','right');"; ?>
c.getbtnstr('重新匹配流程','pipei','','','right');
c.getbtnstr('复制','fuzhi','','disabled','right');
c.getbtnstr('生成所有列表页','allcreate','','','right');
c.getbtnstr('模块开发管理 <i class="icon-angle-down"></i>','downbtn','primary','disabled','right');

if(ISDEMO){
	c.setcolumns('status',{
		editor:false,
		type:'checkbox'
	});
}

c.shengsheng=function(){
	var sid = a.getchecked();
	addtabs({'name':'创建安装包',url:'main,flow,createinstall,sid='+jm.base64encode(sid)+'',num:'createinstall'});
}

c.oldban=function(){
	addtabs({'name':'流程模块列表(旧版)',url:'main,flow,set',num:'flowsetold','icons':'th-list'});
}

c.pipei=function(){
	js.ajax(js.getajaxurl('reloadpipei','flow','main'),{},function(s){
		js.msg('success', s);
	},'get',false,'匹配中...,匹配完成');
}
c.fuzhi=function(){
	if(a.changeid==0)return;
	js.prompt('输入新模块编号','将会从模块['+a.changedata.name+']复制主表子表和表单元素字段的！', function(jg,txt){
		if(jg=='yes' && txt)c.copys(txt);
	});
}
c.copys=function(txt){
	js.ajax(js.getajaxurl('copymode','flow','main'),{id:a.changeid,name:txt},function(s){
		if(s=='ok'){
			a.reload();
		}else{
			js.msg('msg',s);
		}
	},'post',false,'复制中...,复制成功：还是要做其他很多事的，具体请到官网看模块开发视频。');
}
c.downbtn=function(){}

function anbtsenb(bo){
	get('btnfuzhi_{rand}').disabled=bo;
	get('btndownbtn_{rand}').disabled=bo;
}

bootparams.itemclick=function(){
	anbtsenb(false);
}
bootparams.beforeload=function(){
	anbtsenb(true);
}

c.allcreate=function(){
	js.ajax(js.getajaxurl('allcreate','flow','main'),{},function(s){
		js.msg('success', s);
	},'get',false,'生成中...');
}

$('#btndownbtn_{rand}').rockmenu({
	width:170,top:35,donghua:false,
	data:[{
		name:'主表管理',lx:0
	},{
		name:'清空此模块数据',lx:2
	},{
		name:'同步到单位数据',lx:3
	},{
		name:'同步菜单到单位数据',lx:4
	}],
	itemsclick:function(d, i){
		var id = a.changedata.id;
		if(!id)return;
		if(d.lx==0)c.biaoge();
		if(d.lx==2)c.clearalldata(id);
		if(d.lx==3)c.tongbudanwu();
		if(d.lx==4)c.tongbumenu();
	}
});

c.biaoge=function(){
	this.showtalbe(a.changedata.table);
}
c.showtalbe=function(table){
	if(!table)return;
	var name='<?=PREFIX?>'+table+'';
	addtabs({num:'tablefields'+name+'',url:'system,table,fields,table='+name+'',name:'['+name+']字段管理'});
}

c.clearalldata=function(id){
	if(a.changedata.type=='系统'){
		js.msgerror('系统类型模块不能清空');
		return;
	}
	js.confirm('确定要清空此['+a.changedata.name+']模块所有数据嘛？<b style="color:red">慎重慎重慎重慎重！</b>',function(ssal){
		if(ssal=='yes')c.clearalldatas(id);
	});
}
c.clearalldatas=function(id){
	js.ajax(js.getajaxurl('clearallmode','flow','main'),{id:id},function(s){
		if(s=='ok'){
			a.reload();
		}else{
			js.msg('msg',s);
		}
	},'post',false,'清空中...,清空成功');
}
c.tongbudanwu=function(){
	var sid = a.getchecked();
	if(!sid){
		js.msgerror('复选框中没有选中模块');return;
	}
	this.xuandanwefe(sid,0);
}
c.xuandanwefe=function(sid,lx){
	js.tanbody('senddw','同步到单位数据里', 350, 200, {
		html:'<form name="sendform"><div style="padding:10px;" id="senddwdiv"><img src="images/mloading.gif"></div></form>',
		btn:[{text:'确定同步'}]
	});
	
	js.ajax(js.getajaxurl('getcompanydata','flow','main'),{},function(ret){
		var str = '',da=ret.data;
		for(var i=0;i<da.length;i++){
			str+='<div><label><input type="checkbox" name="xuanzhe[]" value="'+da[i].id+'">'+da[i].name+'</label></div>';
		}
		if(!str)str=ret.msg;
		$('#senddwdiv').html(str);
	},'get,json');
	
	$('#senddw_btn0').click(function(){
		c.sendgongwenjsok(sid,lx);
	});
},

c.sendgongwenjsok=function(id1,lx){
	var da = js.getformdata('sendform');
	if(!da.xuanzhe){
		js.msgerror('请选择单位');
		return;
	}
	da.modeids = id1;
	da.lx 	   = lx;
	js.loading('同步中...');
	js.tanclose('senddw');
	js.ajax(publicmodeurl('company','anaymodedata'),da,function(ret){
		js.msgok(ret.data);
	},'post,json');
},
c.tongbumenu=function(){
	$.selectdata({
		title:'选择需要同步的菜单',
		url:js.getajaxurl('getmenu','upgrade','system',{glx:1}),
		checked:true,maxshow:500,
		onselect:function(d1,sna,sid){
			if(sid)c.xuandanwefe(sid, 1)
		}
	});
}