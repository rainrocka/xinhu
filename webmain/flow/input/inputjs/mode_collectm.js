//流程模块【collectm.信息收集】下录入页面自定义js页面,初始函数
function initbodys(){
	
}

function changesubmit(d){
	if(d.fenlei=='0' && !d.runren)return '请选择收集对象';
}

js.changeuser_before=function(na){
	if(na=='runren' && form('fenlei').value=='1')return '外部收集不需要选择人';
}