//流程模块【planm.工作计划】下录入页面自定义js页面,初始函数
function initbodys(){
	if(mid==0)$(form('leixing')).change(function(){
		changeleixing(this)
	});
}

function changeleixing(o1){
	if(!form('name'))return;
	js.ajax(geturlact('chageleixing'),{lx:o1.value}, function(ret){
		form('name').value = ret.data.name;
	},'get,json');
}

function changesubmit(d){
	if(form('name')){
		var str = form('name').value;
		if(str.indexOf('{?}')>-1)return '请完整输入计划名称';
	}
}

function submittijiao(o2){
	var obj = $('input[name^=zhixing_]');
	var da  = {},o1,na,naa;
	for(var i=0;i<obj.length;i++){
		o1 = obj[i];
		na = o1.name;
		naa= na.split('_');
		if(!da[naa[2]])da[naa[2]] = {};
		da[naa[2]][naa[1]] = o1.value;
	}
	var str = JSON.stringify(da);
	js.loading('保存中...');
	o2.disabled = true;
	js.ajax(geturlact('savezhixing'),{str:str,mid:mid,ztstate:get('ztstate').value}, function(ret){
		js.msgok('保存完成');
	},'post,json');
}