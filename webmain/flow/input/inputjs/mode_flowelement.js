//流程模块【flowelement.表单元素管理】下录入页面自定义js页面,初始函数
function initbodys(){
	c.fieldshide('xiaoshu');
	if(mid==0){
		form('mid').value = js.request('mkid');
		if(!form('mid').value){
			js.msgerror('没有选择模块，无法新增');
			c.formdisabled();
			return;
		}
	}
	form('attr').readOnly=false;
	form('fields').readOnly=false;
	form('dev').readOnly=false;
	
	c.onselectdata['attr']=function(sav,sna,sid){
		form('attr').value = sid;
	}
	c.onselectdata['fields']=function(sav,sna,sid){
		if(sav.subname)form('name').value = sav.subname;
	}
	$(form('fieldstype')).change(function(){
		c.changetypes();
	});
}

c.onselectdatabefore=function(fid){
	if(fid=='fields')return {mkid:form('mid').value,iszb:form('iszb').value};
}
c.changetypes=function(){
	var val = form('fieldstype').value;
	if(val=='number'){
		c.fieldsshow('xiaoshu');
	}else{
		c.fieldshide('xiaoshu');
	}
}

function changesubmit(d){
	if(d.fieldstype.indexOf('change')==0){
		if(d.data=='' || d.data==d.fields)return '此字段元素类型时，数据源必须填写用来存储选择来的Id，请填写为：'+d.fields+'id';
	}
	if(d.islu=='1' && d.fields=='id')return 'id字段是不可以做录入项字段';
}

c.xuanchangs=function(){
	var val = form('fieldstype').value;
	if(val.indexOf('change')==0){
		var cans1 = {
			idobj:form('gongsi')
		};
		js.changeuser('AltS', 'deptusercheck', '选择范围', cans1);
	}else{
		js.msg('msg','元素类型不是选择人员部门的');
	}
}