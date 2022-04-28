/**
*	createname：雨中磐石
*	homeurl：http://www.rockoa.com/
*	签名调用函数
*/
function autographClass(cans){
	var me = this;
	this.fid = 'absss';
	if(cans)for(var i in cans)this[i] = cans[i];
	this.qmimgstr = '';
	this.onsuccess= function(){}
	this.create=function(){
		var w = 300,h=200;
		if(ismobile==0){w=450;h=250}
		js.tanbody('qianming','请在空白区域写上签名',w,h,{
			html:'<div style="height:'+(h-50)+'px;overflow:hidden"><iframe src="" name="qianmingiframe" width="100%" height="100%" frameborder="0"></iframe></div>',
			btn:[{text:'确定签名'},{text:'重写'}]
		});
		qianmingiframe.location.href='index.php?m=view&a=autograph&d=main&ism='+ismobile+'';
		$('#qianming_btn0').click(function(){
			me.qianmingok();
		});	
		$('#qianming_btn1').click(function(){
			me.qianmingre();
		});
	}
	this.qianmingok=function(){
		var str = qianmingiframe.autographok();
		if(str){
			this.showqian(str);
			js.tanclose('qianming');
		}
	}
	this.showqian=function(str){
		var s = '<div><img id="imgqianming_'+this.fid+'" src="'+str+'"  height="90"></div>';
		this.qmimgstr = str;
		$('#imgqianming_'+this.fid+'').parent().remove();
		$('#graphview_'+this.fid+'').append(s);
		if(form(this.fid))form(this.fid).value = str;
		this.onsuccess(str);
	}
	this.qianmingre=function(){
		qianmingiframe.autographre();
		
	}
	this.getqmimgstr=function(){
		return this.qmimgstr;
	}
	
	//引入
	this.imports=function(){
		js.msg('wait','引入中...');
		js.ajax('?a=qianyin&m=flowopt&d=flow&ajaxbool=true',{},function(a){
			if(a.success){
				js.msg('success', '引入成功');
				$('#imgqianming').remove();
				var dataUrl = a.data;
				me.showqian(dataUrl);
			}else{
				js.msg('msg', a.msg);
			}
		},'get,json',function(s){
			js.msg('msg','操作失败');
		});
	}
	
	this.clear=function(){
		$('#imgqianming_'+this.fid+'').parent().remove();
		this.qmimgstr = '';
		if(form(this.fid))form(this.fid).value = '';
	}
}
