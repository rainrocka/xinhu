//流程模块【company.公司单位】下录入页面自定义js页面,初始函数
function initbodys(){
	if(form('yuming'))$(form('yuming')).blur(bluryuming);
}

function bluryuming(){
	var val = this.value;
	if(val){
		val = strreplace(val);
		val = val.replace('https://','');
		val = val.replace('http://','');
		if(val.indexOf('/')>-1){
			val = val.substr(0, val.indexOf('/'));
		}
		this.value = val;
	}
}