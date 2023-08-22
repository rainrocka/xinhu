var soumodeid = 0;
yy.onclickmenu=function(d){
	if(this.nowevent!=d.url)soumodeid=0;
	return true;
}
function myyingsinit(){
	var s = '<select id="modeid" style="width:100px;border:none;background:white;font-size:14px"><option value="0">选择模块</option></select>';
	$('#searsearch_bar').prepend(s);
	$('#modeid').change(function(){
		soumodeid = this.value;
		yy.search({'modeid':this.value});
	});
}
yy.onshowdata=function(da){
	if(da.modearr){
		var s = '<option value="0">选择模块</option>',len=da.modearr.length,i,csd,types='';
		for(i=0;i<len;i++){
			csd = da.modearr[i];
			if(types!=csd.type){
				if(types!='')s+='</optgroup>';
				s+='<optgroup label="'+csd.type+'">';
			}
			s+='<option value="'+csd.id+'">'+csd.name+'</option>';
			types = csd.type;
		}
		$('#modeid').html(s);
		if(soumodeid>0)get('modeid').value = soumodeid;
	}
}
myyingsinit();

yy.onclickmenu=function(d){
	if(d.num=='allty'){
		if(this.nowevent!='daib'){
			js.msg('msg','请切换到：所有待办');
			return false;
		}
		var len = this.data.length;
		if(len==0){
			js.msg('msg','没有记录');
			return false;
		}
		js.prompt('批量处理同意','请输入批量处理同意说明(选填)',function(lxbd,msg){
			if(lxbd=='yes'){
				yy.plliangso(msg);
			}
		});
		return false;
	}
	return true;
}

yy.plliangso = function(sm){
	this.plbool = true;
	this.plchusm = sm;
	this.cgshu = 0;
	this.sbshu = 0;
	js.loading('<span id="plchushumm"></span>');
	this.plliangsos(0);
}

yy.plliangsos=function(oi){
	var len = this.data.length;
	$('#plchushumm').html('批量处理中('+len+'/'+(oi+1)+')...');
	if(oi>=len){
		$('#plchushumm').html('处理完成，成功<font color=green>'+this.cgshu+'</font>条，失败<font color=red>'+this.sbshu+'</font>条');
		setTimeout('yy.reload()', 3000);
		this.plbool=false;
		return;
	}
	var d = this.data[oi];
	var cns= {sm:this.plchusm,zt:1,modenum:d.modenum,mid:d.id};
	$.ajax({
		url:js.getajaxurl('check','flowopt','flow'),
		data:cns,
		type:'post',
		dataType:'json',
		success:function(ret){
			if(ret.success){
				yy.cgshu++;
			}else{
				yy.sbshu++;
				js.msg('msg','['+d.modename+']'+ret.msg+'，不能使用批量来处理，请打开详情去处理。');
			}
			yy.plliangsos(oi+1);
		}
	});
}