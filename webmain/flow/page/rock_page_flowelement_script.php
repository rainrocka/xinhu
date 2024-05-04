//<script>

c.optalign = 'left';
c.opttype = '<button type="button" class="btn btn-default" style="padding:3px 5px;font-size:12px">操作</button>';
var editarr = ['islu','isbt','iszs','islb','ispx','issou','isonly','isdr','istj'],mkid=0,mknum=modenum;
for(var i=0;i<editarr.length;i++)c.setcolumns(editarr[i],{type:'checkbox'});
bootparams.fanye = false;

c.initpage=function(){
	$('#tdleft_{rand}').after('<td ><select style="width:180px;"  class="form-control" id="modeid_{rand}" ></select></td>');
	$('#modeid_{rand}').change(function(){
		c.changemodeid(this);
	});
}

c.onloadbefore=function(d){
	if(d.modearr){
		var s = '<option value="0">-选择模块-</option>',len=d.modearr.length,i,csd,types='';
		for(i=0;i<len;i++){
			csd = d.modearr[i];
			if(types!=csd.type){
				if(types!='')s+='</optgroup>';
				s+='<optgroup label="'+csd.type+'">';
			}
			s+='<option value="'+csd.id+'">'+csd.name+'</option>';
			types = csd.type;
		}
		s+='</optgroup>';
		$('#modeid_{rand}').html(s);
	}
	if(d.mkrs)mknum = d.mkrs.num;
}
c.changemodeid=function(o1){
	var val = o1.value;
	if(val=='0')return;
	mkid = val;
	a.setparams({mkid:mkid},true);
}
c.xuanmoxbo = function(){
	if(mkid=='0'){
		js.msgerror('请先选择模块');
		return false;
	}
	return true;
}

c.clickwin=function(o1,lx){
	if(!this.xuanmoxbo())return;
	openinput(modename,modenum,'0&mkid='+mkid+'','opegs{rand}');
} 

$('#tools'+modenum+'_{rand}').find('td[tdlx="sou"]').hide();

var strss = '<div class="btn-group" style="margin-right:10px">';
strss+='<button class="btn btn-default" click="inputs,0" type="button">PC端录入页布局</button>';
strss+='<button class="btn btn-default" click="zhanshi,0" type="button">PC端展示</button>';
strss+='<button class="btn btn-default" click="zhanshi,1" type="button">手机展示</button>';
strss+='<button class="btn btn-default" click="zhanshi,2" type="button">打印布局</button>';
strss+='<button class="btn btn-default" click="lulu,0" type="button">PC录入页</button>';
strss+='<button class="btn btn-default" click="lulu,1" type="button">手机录入页</button>';
strss+='<button class="btn btn-default" click="changelieb" type="button">生成列表页</button>';
strss+='</div>';

$('#tdcenter_{rand}').prepend(''+strss+'');

c.inputs=function(){
	if(!this.xuanmoxbo())return;
	var url='?m=flow&d=main&a=input&setid='+mkid+'&atype=0';
	js.open(url,980,530);
}

c.zhanshi=function(o1,lx){
	if(!this.xuanmoxbo())return;
	var url='?m=flow&d=main&a=inputzs&setid='+mkid+'&atype='+lx+'';
	js.open(url,980,530);
}
c.lulu=function(o1,lx){
	if(!this.xuanmoxbo())return;
	if(lx==1){
		var url = js.getajaxurl('@lum','input','flow',{num:mknum});
		js.open(url, 380,500);
	}else{
		var url = js.getajaxurl('@lu','input','flow',{num:mknum});
		js.open(url, 800,450);
	}
}
c.changelieb=function(){
	if(!this.xuanmoxbo())return;
	js.ajax(js.getajaxurl('changelieb','flow','main'),{modeid:mkid},function(s){
		js.msg('success','生成成功路径：'+s+'');
	},'get','','生成中...,生成成功');
}
c.getbtnstr('旧版','oldban','','','right');
c.getbtnstr('刷新排序','rexuhao','','','right');
c.rexuhao=function(){
	if(!this.xuanmoxbo())return;
	js.ajax(js.getajaxurl('rexuhao','flow','main'),{modeid:mkid},function(){
		a.reload();
	},'get','','刷新中...,刷新成功');
}
c.oldban=function(){
	addtabs({'name':'表单元素管理(旧版)',url:'main,flow,element',num:'flowelementold','icons':'check'});
}