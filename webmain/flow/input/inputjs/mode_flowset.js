//流程模块【flowset.流程模块列表】下录入页面自定义js页面,初始函数
function initbodys(){
	
}

function changesubmit(d){
	if(d.tables){
		var arr = d.tables.split(',');
		if(arr.length>10)return '最多只能10个子表，太多子表建议使用多个模块';
	}
}