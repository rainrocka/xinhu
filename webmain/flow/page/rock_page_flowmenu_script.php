//<script>

bootparams.statuschange = true;
$('#tools'+modenum+'_{rand}').find('td[tdlx="sou"]').hide();
bootparams.fanye = false;
bootparams.tree = true;
c.getbtnstr('旧版','oldban','','','right');
c.getbtnstr('加下级菜单','jiaxiaji','','disabled','right');

c.setcolumns('url',{
	type:'textarea',
	renderstyle:function(v,d){
		return 'word-wrap:break-word;word-break:break-all;white-space:normal;width:180px';
	}
});

if(ISDEMO){
	c.setcolumns('status',{
		editor:false
	});
}

c.oldban=function(){
	addtabs({'name':'菜单管理(旧版)',url:'system,menu',num:'menuold','icons':'list-ul'});
}

c.jiaxiaji=function(){
	openinput(modename,modenum,'0&def_pid='+a.changeid+'','opegs{rand}');
}

function anbtsenb(bo){
	get('btnjiaxiaji_{rand}').disabled=bo;
}

bootparams.itemclick=function(){
	anbtsenb(false);
}
bootparams.beforeload=function(){
	anbtsenb(true);
}

c.initpage=function(){
	$('#tdleft_{rand}').after('<td style="padding-right:10px"><select style="width:150px;"  class="form-control" id="modeid_{rand}" ><option value="0">请选择菜单</option></select></td>');
	$('#modeid_{rand}').change(function(){
		a.setparams({pid:this.value},true);
	});
}

c.changemodeid=function(){
	
}

var boodes = false;
c.onloadbefore=function(d){
	if(boodes)return;
	js.setselectdata(get('modeid_{rand}'),d.rows,'id');
	boodes = true;
}