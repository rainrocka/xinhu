/**
*	createname：信呼开发团队-雨中磐石
*	homeurl：http://www.rockoa.com/
*	Copyright (c) 2016 rainrock
*	Date:2016-01-01
*	remark：本文件页面是系统公共录入页面上主要js文件，处理录入页面上交互设计，公式计算等。
	                              xhxh
	                             xhxh
   xh       xh                 xh  xh
  xh    xhxhxhxhxh    xhxhxh       xh xh
 xhxh    xhxhxhxh     xh  xh xhxhxhxhxhxhxh
xh xh   xhxhxhxhxh    xhxhxh       xh
   xh                              xh
   xh   xhxhxhxhxh                 xh
   xh   xh      xh                 xh
   xh   xh      xh                 xh
   xh   xhxhxhxhxh               xhxh
*/

var ismobile=0,firstrs={},alldata={},isxiang=0,
	submitparams={},//要提交提交的参数
	subdataminlen=[];//子表至少行数
function initbodys(){};
function savesuccess(){};
function saveerror(){};
function eventaddsubrows(){}
function eventdelsubrows(){}
function geturlact(act,cns){
	var url=js.getajaxurl(act,'mode_'+moders.num+'|input','flow',cns);
	return url;
}
function initbody(){
	js.tanstyle = 1;
	$('body').keydown(function(et){
		var code	= et.keyCode;
		if(code==27){
			c.close();
			return false;
		}
		if(et.altKey){
			if(code == 83){
				get('AltS').click();
				return false;
			}
		}
	});
	var len = arr.length,i,fid,nfid='',flx;
	for(i=0;i<len;i++){
		fid=arr[i].fields;
		flx=arr[i].fieldstype;
		if(arr[i].islu=='1' && arr[i].iszb=='0'){
			if(flx=='checkboxall')fid+='[]';
			if(fid.indexOf('temp_')!=0 && !form(fid)){
				nfid+='\n('+fid+'.'+arr[i].name+')';
			}
			if(flx=='htmlediter')c.htmlediter(arr[i].fields);
		}
	}
	c.initsubtable();
	if(nfid==''){
		c.showdata();
	}else{
		alert('录入页面缺少必要的字段：'+nfid+'');
	}
	
	if(ismobile==1)f.fileobj = $.rockupload({
		autoup:false,
		fileview:'view_fileidview',
		allsuccess:function(){
			c.saveken();
		}
	});
	$('#view_fileidview').click(function(){c.loadicons()});
}
function changesubmit(d){};
function changesubmitbefore(){};

//函数触发
function oninputblur(name,zb,obj,zbxu,row){};

var f={
	change:function(o1){
		f.fileobj.change(o1);
	}
};

js.apiurl = function(m,a,cans){
	var url=''+apiurl+'api.php?m='+m+'&a='+a+'&adminid='+adminid+'';
	var cfrom='mweb';
	url+='&device='+device+'';
	url+='&cfrom='+cfrom+'';
	url+='&token='+token+'';
	if(!cans)cans={};
	for(var i in cans)url+='&'+i+'='+cans[i]+'';
	return url;
}

//选择人员前处理
js.changeuser_before=function(na){
	if(na=='sys_nextcoursename'){
		var fw = '',o = form('sys_nextcourseid');
		if(o){
			if(o.value==''){o.focus();return '请先选择下步处理步骤'};
			var o1= o.options[o.selectedIndex];
			fw = $(o1).attr('changerange');
			return {'changerange':fw};
		}
	}
	return c.changeuser_before(na);
}

var c={
	callback:function(cs, msg){
		var calb = js.request('callback');
		if(ismobile==1 && js.msgok)js.msgok(msg, function(){js.back()},1);
		if(!calb){
			if(ismobile==0){
				try{parent.js.msgok(msg);}catch(e){}
				try{parent.bootstableobj[moders.num].reload();}catch(e){}
				try{parent.js.tanclose('winiframe');}catch(e){}
			}
			return;
		}
		try{parent[calb](cs);}catch(e){}
		try{opener[calb](cs);}catch(e){}
		try{parent.js.msgok(msg);}catch(e){}
		try{parent.js.tanclose('winiframe');}catch(e){}
	},
	
	save:function(){
		var d = this.savesss();
		if(!d)return;
		if(ismobile==1){
			js.msg('wait','保存中...');
			get('AltS').disabled=true;
			f.fileobj.start();
		}else{
			this.saveken();
		}
	},
	saveken:function(){
		var d = this.savesss();
		if(!d)return;
		this.saveok(d);
	},
	showtx:function(msg, fid){
		js.setmsg(msg);
		if(ismobile==1)js.msg('msg', msg);
		if(fid && form(fid))form(fid).focus();
	},
	selectdatadata:{},
	onselectdata:{},
	changeuser_before:function(){},
	onselectdatabefore:function(){},
	onselectdataall:function(){},
	selectdata:function(s1,ced,fid,tit,zbis){
		if(isedit==0)return;
		if(!tit)tit='请选择...';
		if(s1.indexOf('[SQL]')==0){js.msg('msg','此元素类型的不支持数据源是SQL的');return;}
		var a1 = s1.split(','),idobj=false,acttyle='act';
		var fids = a1[1];
		if(fids){
			if(zbis>=1){//说明是子表
				var gezs = this.getxuandoi(fid);
				fids+=gezs[2];
			}
			idobj=form(fids);
		}
		var gcan,dass,i,befs
		gcan = {'act':a1[0],'acttyle':acttyle,'sysmodenum':moders.num,'sysmid':mid};
		dass = this.selectdatadata[fid];
		befs = this.onselectdatabefore(fid,zbis,s1);
		if(befs){
			if(typeof(befs)=='string'){js.msg('msg',befs);return;}
			if(typeof(befs)=='object'){
				dass=[];
				for(i in befs)gcan[i]=befs[i];
			}
		}
		$.selectdata({
			data:dass,title:tit,fid:fid,
			url:geturlact('getselectdata', gcan),
			checked:ced, nameobj:form(fid),idobj:idobj,
			onloaddata:function(a){
				c.selectdatadata[fid]=a;
			},
			onselect:function(seld,sna,sid){
				c.onselectdataall(this.fid,seld,sna,sid);
				if(c.onselectdata[this.fid])c.onselectdata[this.fid](seld,sna,sid);
			}
		});
	},
	changisturn:function(){
		var txt = '提交(S)';
		if(!get('sysisturn').checked)txt='存草稿(S)';
		get('AltS').value=txt;
	},
	savesss:function(){
		if(js.ajaxbool||isedit==0)return false;
		var len = arr.length,i,val,fid,flx,nas,j,j1,zbd,sda,zbs,zbmc,fa,sdtname,edename;
		changesubmitbefore();
		var d = js.getformdata();
		for(i=0;i<len;i++){
			if(arr[i].iszb!='0')continue;
			fa = arr[i];
			fid=fa.fields;
			flx=fa.fieldstype;
			nas=fa.name;
			if(ismobile==0 && fa.islu=='1' && flx=='htmlediter'){
				d[fid] = this.editorobj[fid].html();
			}
			val=d[fid];
			if(fa.isbt=='1'){
				if(flx=='uploadfile' && val=='0'){
					this.showtx('请选择'+nas+'');
					return false;
				}
				if(isempt(val)){
					if(form(fid))form(fid).focus();
					this.showtx(''+nas+'不能为空');
					return false;
				}
			}
			if(val && flx=='email'){
				if(!js.email(val)){
					this.showtx(''+nas+'格式不对');
					form(fid).focus();
					return false;
				}
			}
			if(fid=='startdt')sdtname=fa.name;
			if(fid=='enddt')edename=fa.name;
		}
		
		if(sdtname && edename && d.startdt && d.enddt){
			if(d.startdt>=d.enddt){
				this.showtx(''+sdtname+'必须大于'+edename+'', 'enddt');
				return false;
			}
		}
		
		//子表判断记录是不是空
		len = subfielsa.length;
		for(i=0;i<this.subcount;i++){//子表数
			zbd = this.getsubdata(i);
			zbs = subdataminlen[i];
			zbmc= zbnamearr[i];
			if(typeof(zbs)=='number' && zbs==0)continue;//不需要子表行
			if(typeof(zbs)=='number' && zbs>0 && zbd.length<zbs){
				this.showtx('['+zbmc+']至少要'+zbs+'行');
				return false;
			}
			for(j1=0;j1<zbd.length;j1++){//总行
				for(j=0;j<subfielsa.length;j++){//必填字段
					sda = subfielsa[j];
					if(sda.iszb==(i+1)){//子表要对应
						flx = sda.type;
						val = zbd[j1][sda.fields];
						fid = ''+sda.fields+''+i+'_'+zbd[j1]._hang+'';
						if(isempt(val)){
							if(form(fid))form(fid).focus();
							this.showtx('['+sda.zbname+']第'+(j1+1)+'行上'+sda.name+'不能为空');
							this.subshantiss(i, fid,0);
							return false;
						}
						if(flx=='number'&&parseFloat(val)==0){
							if(form(fid))form(fid).focus();
							this.showtx('['+sda.zbname+']第'+(j1+1)+'行上'+sda.name+'不能为0');
							this.subshantiss(i, fid,0);
							return false;
						}
					}
				}
			}
		}
		var bo = true;
		if(form('istrun') && d.istrun=='0')bo=false; //是否提交的判断
		if(get('sysisturn')){
			if(get('sysisturn').checked){
				d.istrun = 1;
			}else{
				d.istrun = 0;
				bo=false;
			}
		}
		if(firstrs.isbt==1 && bo){
			if(!d.sysnextoptid && form('sysnextopt')){
				this.showtx('请指定['+firstrs.name+']处理人');
				form('sysnextopt').focus();
				return false;
			}
		}
		
		if(form('sys_nextcourseid') && bo){
			if(!d.sys_nextcourseid){
				this.showtx('请指定下步处理步骤');
				form('sys_nextcourseid').focus();
				return false;
			}
			if(!d.sys_nextcoursenameid && this.changenextbool){
				this.showtx('请选择下步处理人');
				return false;
			}
		}
		
		if(moders.iscs=='2' && isempt(d.syschaosongid) && bo){
			this.showtx('请选择抄送对象');
			return false;
		}
		
		var s=changesubmit(d);
		if(typeof(s)=='string'&&s!=''){
			this.showtx(s);
			return false;
		}
		if(typeof(s)=='object')d=js.apply(d,s);
		d = js.apply(d,submitparams);
		d.sysmodeid=moders.id;
		d.sysmodenum=moders.num;
		return d;
	},
	changenextbool:true,
	changenextcourse:function(o,lx){
		if(lx!=4)return;
		var o1= o.options[o.selectedIndex];
		var clx = $(o1).attr('checktype');
		var dov = $('#sys_nextcoursediv1')
		if(clx=='change'){
			this.changenextbool=true;
			dov.show();
		}else{
			this.changenextbool=false;
			dov.hide();
		}
	},
	subshantiss:function(i,fid,oi){
		if(!form(fid))return;
		clearTimeout(this.subshantistime1);
		if(oi%2==0){
			$(form(fid)).parent().css('background','red');
		}else{
			$(form(fid)).parent().css('background','');
			if(oi>10)return;
		}
		this.subshantistime1 = setTimeout(function(){c.subshantiss(i,fid,oi+1);},200);
	},
	saveok:function(d){
		js.setmsg('保存中...');
		get('AltS').disabled=true;
		js.ajax(geturlact('save'),d,function(str){
			var a = js.decode(str);
			c.backsave(a, str);
		}, 'post', function(){
			get('AltS').disabled=false;
			js.setmsg('error:内部错误,可F12调试');
		});
	},
	backsave:function(a,str){
		var msg = a.msg;
		if(a.success){
			//var msgs  = (mid=='0')?'新增':'编辑'
			var sumsg = ''+a.msg+'成功';
			js.setmsg(sumsg,'green');
			js.msg('success',sumsg);
			this.formdisabled();
			$('#AltSspan').hide();
			form('id').value=a.data;
			isedit=0;
			savesuccess();
			this.callback(a.data, sumsg);
			try{
			js.sendevent('reload', 'yingyong_mode_'+moders.num+'');
			js.backla();}catch(e){}
		}else{
			if(typeof(msg)=='undefined')msg=str;
			get('AltS').disabled=false;
			this.showtx(msg);//错误提醒
			saveerror(msg);
		}
	},
	changeturn:function(){
		if(get('sysisturn').checked){
			get('AltS').value='提交';
		}else{
			get('AltS').value='保存草稿';
		}
	},
	showdata:function(){
		var smid=form('id').value;
		if(smid=='0'||smid==''){
			isedit=1;
			$('#AltSspan').show();
			c.initdatelx();
			c.initinput();
			initbodys(smid);
		}else{
			js.setmsg('加载数据中...');
			js.ajax(geturlact('getdata'),{mid:smid,flownum:moders.num},function(str){
				c.showdataback(js.decode(str));	
			},'post', function(){
				js.setmsg('error:内部错误,可F12调试');
			});
		}
	},
	//初始上传框
	filearr:{},
	uploadback:function(){},
	initinput:function(){
		var o,o1,sna,i,tsye,uptp,tdata,farr=alldata.filearr,far;
		var o = $('div[id^="filed_"]');
		for(i=0;i<o.length;i++){
			o1 = o[i];sna= $(o1).attr('tnam');tsye=$(o1).attr('tsye');tdata=$(o1).attr('tdata');
			if(isedit==1){
				uptp = 'image';
				if(tsye=='file'){
					uptp='*';
					if(!isempt(tdata))uptp=tdata;
					$('#'+sna+'_divadd').show();
				}else{
					$(o1).show();
				}
				$.rockupload({
					'inputfile':''+o1.id+'_inp',
					'initremove':false,'uptype':uptp,
					'oparams':{sname:sna,snape:tsye},
					'onsuccess':function(f,gstr){
						var sna= f.sname,tsye=f.snape,d=js.decode(gstr);
						if(tsye=='img'){
							get('imgview_'+sna+'').src = d.filepath;
							form(sna).value=d.filepath;
							c.upimages(sna,d.id,false);
						}else if(tsye=='file'){
							$('#meng_'+c.uprnd+'').remove();
							$('#up_'+c.uprnd+'').attr('upid_'+sna+'',d.id);
							c.upfbo = false;
							c.filearr['f'+d.id+''] = f;
							c.showupid(sna);//显示ID	
						}
						c.uploadback(sna, f);
					},
					'onprogress':function(f,bl){
						var sna= f.sname,tsye=f.snape;
						if(tsye=='file'){
							$('#meng_'+c.uprnd+'').html(''+bl+'%');
						}
					},
					onchange:function(f){
						var sna= f.sname,tsye=f.snape;
						if(tsye=='file'){
							var flx = js.filelxext(f.fileext);
							c.uprnd = js.getrand();
							c.upfbo = true;
							var s='<div onclick="c.clickupfile(this,\''+sna+'\')" id="up_'+c.uprnd+'" title="'+f.filename+'('+f.filesizecn+')"  class="upload_items">';
							if(f.isimg){
								s+='<img class="imgs" src="'+f.imgviewurl+'">'
							}else{
								s+='<div class="upload_items_items"><img src="web/images/fileicons/'+flx+'.gif" alian="absmiddle"> ('+f.filesizecn+')<br>'+f.filename+'</div>';
							}
							s+='<div id="meng_'+c.uprnd+'" class="upload_items_meng" style="font-size:16px">0%</div></div>';
							$('#'+sna+'_divadd').before(s);
						}else if(tsye=='img'){
							js.loading('上传中...');
						}
					}
				});
			}
			var val = form(sna).value;
			if(tsye=='img'){
				var val1 = data[''+sna+'_view'];
				if(!val1)val1=val;
				if(val1)get('imgview_'+sna+'').src=val1;
			}
			//显示上传文件信息
			if(tsye=='file' && farr && val){
				var fid,f,s,vals=','+val+',';
				for(fid in farr){
					f = farr[fid];
					if(!f || vals.indexOf(','+f.id+',')<0)continue;
					s='<div onclick="c.clickupfile(this,\''+sna+'\')" title="'+f.filename+'('+f.filesizecn+')" upid_'+sna+'="'+f.id+'" class="upload_items">';
					if(js.isimg(f.fileext)){
						s+='<img class="imgs" src="'+f.thumbpath+'">';
					}else{
						s+='<div class="upload_items_items"><img src="web/images/fileicons/'+js.filelxext(f.fileext)+'.gif" alian="absmiddle"> ('+f.filesizecn+')<br>'+f.filename+'</div>';
					}
					s+='</div>';
					$('#'+sna+'_divadd').before(s);
					this.filearr[fid] = f;
				}
				this.showupid(sna);
			}
		}
		
		if(ismobile==1){
			$('div[tmp="mobilezbiao"]').css('width',''+($(window).width()-12)+'px');
		}
	},
	upimages:function(fid,fileid,bs){
		if(!bs){
			js.loading('等待上传完成...');
			setTimeout("c.upimages('"+fid+"','"+fileid+"', true)",3000);
		}else{
			js.ajax(geturlact('upimagepath'),{fileid:fileid,fid:fid},function(ret){
				js.unloading();
				var da = ret.data;
				if(da.path)form(da.fid).value=da.path;
			},'get,json');
		}
	},
	//多文件点击上传
	uploadfilei:function(sna){
		if(isedit==0)return;
		if(this.upfbo){js.msg('msg','请等待上传完成在添加');return;}
		get('filed_'+sna+'_inp').click();
	},
	//上传完成
	showupid:function(sna){
		var os = $('div[upid_'+sna+']'),fvid='';
		for(var i=0;i<os.length;i++){
			fvid+=','+$(os[i]).attr('upid_'+sna+'')+'';
		}
		if(fvid!='')fvid=fvid.substr(1);
		form(sna).value=fvid;
	},
	
	//预览文件
	downshow:function(id, ext,pts){
		js.yulanfile(id, ext,pts);
	},
	
	//上传文件点击
	clickupfile:function(o1,sna, xs){
		this.yuobj = o1;
		var o = $(o1);
		var fid = o.attr('upid_'+sna+'');
		if(isempt(fid))return;
		var f = this.filearr['f'+fid+''];if(!f)return;
		if(isedit==0 || xs){
			js.alertclose();
			this.loadicons();
			js.fileopt(fid,0);
		}else{
			js.confirm('确定要<font color=red>删除文件</font>：'+o1.title+'吗？<a style="color:blue" href="javascript:;" onclick="js.alertclose();js.downshow('+fid+',\'abc\')">下载</a>&nbsp; <a style="color:blue" href="javascript:;" onclick="c.clickupfile(c.yuobj,\''+sna+'\', true)">预览</a>',function(jg){
				if(jg=='yes'){
					o.remove();
					c.showupid(sna);
					$.get(js.getajaxurl('delfile','upload','public',{id:fid}));
				}
			});
		}
	},
	
	showfilestr:function(d){
		var flx = js.filelxext(d.fileext);
		var s = '<img src="web/images/fileicons/'+flx+'.gif" align="absmiddle" height=16 width=16> <a href="javascript:;" onclick="js.downshow('+d.id+')">'+d.filename+'</a> ('+d.filesizecn+')';
		return s;
	},
	
	loadicons:function(){
		if(!this.loacdis){
			$('body').append('<link rel="stylesheet" type="text/css" href="web/res/fontawesome/css/font-awesome.min.css">');
			this.loacdis= true;
		}
	},
	showviews:function(o1){
		this.loadicons();
		var url = (typeof(o1)=='string')? o1 : o1.src;
		$.imgview({'url':url,'ismobile':ismobile==1});
	},
	
	initdatelx:function(){
		
	},
	subcount:3,//子表数量
	showdataback:function(a){
		if(a.success){
			var da = a.data;
			alldata= da;
			js.setmsg();
			var len = arr.length,i,fid,val,flx,ojb,j;
			data=da.data;
			for(i=0;i<len;i++){
				fid=arr[i].fields;
				flx=arr[i].fieldstype;
				if(arr[i].islu=='1' && arr[i].iszb=='0' && fid.indexOf('temp_')!=0){
					val=da.data[fid];
					if(val==null)val='';
					if(flx=='checkboxall'){
						ojb=$("input[name='"+fid+"[]']");
						val=','+val+',';
						for(j=0;j<ojb.length;j++){
							if(val.indexOf(','+ojb[j].value+',')>-1)ojb[j].checked=true;
						}
					}else if(flx=='checkbox'){
						form(fid).checked = (val=='1');
					}else if(flx=='htmlediter' && ismobile==0){
						this.editorobj[fid].html(val);
					}else if(flx.substr(0,6)=='change'){
						if(form(fid))form(fid).value=val;
						fid = arr[i].data;
						if(!isempt(fid)&&form(fid))form(fid).value=da.data[fid];
					}else{
						if(form(fid))form(fid).value=val;
					}
				}
			}
			isedit=da.isedit;
			if(form('base_name'))form('base_name').value=da.user.name;
			if(form('base_deptname'))form('base_deptname').value=da.user.deptname;
			js.downupshow(da.filers,'fileidview', (isedit==0));
			var subd = da.subdata,subds;
			for(j=0;j<this.subcount;j++){
				subds=subd['subdata'+j+''];
				if(subds)for(i=0;i<subds.length;i++){
					subds[i].sid=subds[i].id;
					if(form('xuhao'+j+'_'+i+'')){
						c.adddatarow(j,i, subds[i]);
					}else{
						c.insertrow(j, subds[i], true);
					}
				}
			}
			c.initinput();
			initbodys(form('id').value);
			if(isedit==0){
				this.formdisabled();
				js.setmsg('无权编辑，查看<a href="http://www.rockoa.com/view_wqbj.html" target="_blank" class="blue">[帮助]</a>');
			}else{
				$('#AltSspan').show();
				c.initdatelx();
			}
			if(da.isflow>0){
				$('.status').css({'color':da.statuscolor,'border-color':da.statuscolor}).show().html(da.statustext);
			}
		}else{
			get('AltS').disabled=true;
			this.formdisabled();
			js.setmsg(a.msg);
			js.msg('msg',a.msg);
		}
	},
	date:function(o1,lx){
		$(o1).rockdatepicker({view:lx,initshow:true});
	},
	close:function(){
		window.close();
	},
	formdisabled:function(){
		$('form').find('*').attr('disabled', true);
		$('#fileupaddbtn').remove();
	},
	upload:function(){
		js.upload('',{showid:'fileidview'});
	},
	changeuser:function(na, lx){
		js.changeuser(na,lx);
	},
	changeclear:function(na){
		js.changeclear(na);
	},
	editorobj:{},
	htmlediter:function(fid){
		if(ismobile==1)return;
		var cans  = {
			resizeType : 0,
			allowPreviewEmoticons : false,
			allowImageUpload : true,
			formatUploadUrl:false,
			allowFileManager:true,
			uploadJson:'?m=upload&a=upimg&d=public',
			minWidth:'300px',height:'250',
			items : [
				'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
			'removeformat','|','fontname', 'fontsize','quickformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
			'insertunorderedlist', '|','image', 'link','unlink','|','undo','source','clearhtml','fullscreen'
			]	
		};
		this.editorobj[fid] = KindEditor.create("[name='"+fid+"']", cans);
	},
	subtablefields:[],
	initsubtable:function(){
		var i,oba,j,o,nas,nle,nasa,fname,o2;
		this.subcount = $("input[name^='sub_totals']").length;
		for(i=0;i<this.subcount;i++){
			o2 = get('tablesub'+i+'');
			if(o2){
				fname=[];
				o=$('#tablesub'+i+'');
				form('sub_totals'+i+'').value=o2.rows.length-1;
				this.repaixuhao(i);
				oba = o.find('tr:eq(1)').find('[name]');
				for(j=0;j<oba.length;j++){
					nas=oba[j].name;
					nasa=nas.split('_');
					nle = nasa.length;
					nna= nasa[0];
					//if(nle>2)nna+='_'+nasa[1]+'';
					//if(nle>3)nna+='_'+nasa[2]+'';
					//不再限制下划线个数，原本只能最多3个
					for(var k=1;k<nle-1;k++){
						nna+='_'+nasa[k]+'';
					}
					fname.push(nna.substr(0,nna.length-1));
				}
				this.subtablefields[i]=fname;
			}
		}
		
		//引入公式相关的js文件
		var gongsistr='';
		for(i=0;i<gongsiarr.length;i++)gongsistr+=','+gongsiarr[i].gongsi+'';
		if(gongsistr!=''){
			if(gongsistr.indexOf('AmountInWords')>-1)js.importjs('js/rmb.js');
			if(gongsistr.indexOf('js.')>-1)js.importjs('js/jsrock.js');
		}
	},
	getsubdata:function(i){
		var d=[];
		if(!get('tablesub'+i+''))return d;
		var len=parseFloat(form('sub_totals'+i+'').value);
		var i1,ji,i2,far=this.subtablefields[i],lens=far.length,fna;
		for(i1=0;i1<len;i1++){
			var a={_hang:i1};i2=0;
			for(j1=0;j1<lens;j1++){
				fna=''+far[j1]+''+i+'_'+i1+'';
				if(form(fna)){
					a[far[j1]]=form(fna).value;
					i2++;
				}
			}
			if(i2>0)d.push(a);
		}
		return d;
	},
	delrow:function(o,xu){
		if(isedit==0){
			$(o).remove();
			return;
		}
		var o1=get('tablesub'+xu+'').rows;
		if(o1.length<=2){
			js.msg('msg','最后一行不能删除');
			return;
		}
		$(o).parent().parent().remove();
		this.repaixuhao(xu);
		this.rungongsi();
		eventdelsubrows(xu);
	},
	rungongsi:function(){
		var i,len=gongsiarr.length,d;
		for(i=0;i<len;i++){
			d = gongsiarr[i];
			if(d.iszb==0&&form(d.fields))this.inputblur(form(d.fields),0);
		}
	},
	repaixuhao:function(xu){
		var o=$('#tablesub'+xu+'').find("input[temp='xuhao']");
		for(var i=0;i<o.length;i++){
			o[i].value=(i+1);
		}
	},
	insertrow:function(xu, d, isad){
		var o2 = get('tablesub'+xu+'');
		if(!o2){
			alert('表单设计有误，请重新设计多行子表');
			return;
		}
		var o=$('#tablesub'+xu+'');
		var oi = o2.rows.length-1,i,str='',oba,nas,oj,nna,ax2,d1,nass;
		oi=1;
		var cell = o2.rows[oi].cells.length;
		for(i=0;i<cell;i++)str+='<td>'+o2.rows[oi].cells[i].innerHTML+'</td>';
		oba = o.find('tr:eq('+oi+')').find('[name]');
		oj  = parseFloat(form('sub_totals'+xu+'').value);
		var narrs=[],fasr=this.subtablefields[xu],wux=''+xu+'_'+oj+'';
		for(i=0;i<oba.length;i++){
			nas=oba[i].name;
			oi = nas.lastIndexOf('_');
			nass= nas.substr(0, oi-1);
			nna=nass+''+wux+'';
			str=str.replace(new RegExp(nas,'gi'), nna);
			narrs.push(nna);
		}
		form('sub_totals'+xu+'').value=(oj+1);
		str=str.replace(/rockdatepickerbool=\"true\"/gi,'');
		o.append('<tr>'+str+'</tr>');
		d=js.apply({sid:'0'},d);
		for(d1 in d){
			ax2=d1+wux;
			if(form(ax2))form(ax2).value=d[d1];
		}
		this.repaixuhao(xu);
		this.initdatelx();
		var nusa = [""+xu+"",""+oj+"",wux,nass,nna];
		if(!isad)eventaddsubrows(xu, oj);
		return nusa;
	},
	adddatarow:function(xu, oj, d){
		d=js.apply({sid:'0'},d);
		var fasr=this.subtablefields[xu],ans;
		for(var i=0;i<fasr.length;i++){
			ans=fasr[i]+''+xu+'_'+oj+'';
			if(form(ans)&&d[fasr[i]])form(ans).value=d[fasr[i]];
		}
	},
	//设置子表行数据
	setrowdata:function(xu, oj, d){
		var ans;
		for(var i in d){
			ans=i+''+xu+'_'+oj+'';
			if(form(ans))form(ans).value=d[i];
		}
	},
	//根据名称获取第几个子，哪一行[第几个子表，第几行]
	getxuandoi:function(fid){
		var naa = fid.substr(fid.lastIndexOf('_')-1);
		var spa = naa.split('_');
		spa[2] = naa;
		spa[3] = fid.replace(naa,'');
		spa[4] = fid;
		return spa;
	},
	addrow:function(o,xu){
		if(isedit==0){
			$(o).remove();
			return;
		}
		this.insertrow(xu);
	},
	//子表表单对象,na名称,zb第几个子表,hs第几行
	getforms:function(na,zb,hs){
		var fid = ''+na+''+zb+'_'+hs+'';
		return form(fid);
	},
	getsubtabledata:function(){
		
	},
	_getsubtabledatas:function(xu){
		var oxut=form('sub_totals'+xu+'');
		if(!oxut)return false;
		var da={},fasr,len=parseFloat(oxut.value),j,f,na;
		da['sub_totals'+xu+'']=oxut.value;
		fasr=this.subtablefields[xu];
		for(j=1;j<=len;j++){
			for(f=0;j<fasr.length;j++){
				na=fasr[f]+''+xu+'_'+j+'';
				if(form(na))da[na]=form(na).value;
			}
		};
		return da;
	},
	getsubtotals:function(fid, xu){
		var oi=0;
		if(!xu)xu='0';
		var oxut=form('sub_totals'+xu+'');
		if(!oxut)return oi;
		var len=parseFloat(oxut.value),j,na,val;
		for(j=0;j<len;j++){
			na=fid+''+xu+'_'+j+'';
			if(form(na)){
				val=form(na).value;
				if(val)oi+=parseFloat(val);
			}
		}
		return oi;
	},
	getselobj:function(fv){
		var o = form(fv);
		if(!o)return;
		var o1= o.options[o.selectedIndex];
		return o1;
	},
	getseltext:function(fv){
		var o1 = this.getselobj(fv);
		if(!o1)return '';
		return o1.text;
	},
	getselattr:function(fv,art){
		var o1 = this.getselobj(fv);
		if(!o1)return '';
		return $(o1).attr(art);
	},
	setfields:function(fid,na){
		if(ismobile==1)na=''+na+':'
		$('#div_'+fid+'').parent().prev().text(na);
	},
	fieldshide:function(fid){
		var o = $('#div_'+fid+'').parent();
		o.hide();
		o.prev().hide();
	},
	fieldsshow:function(fid){
		var o = $('#div_'+fid+'').parent();
		o.show();
		o.prev().show();
	},
	uploadimgclear:function(fid){
		get('imgview_'+fid+'').src='images/noimg.jpg';
		form(fid).value='';
	},
	
	//----强大公式计算函数处理start-----
	inputblur:function(o1,zb){
		if(isedit==0)return;
		var ans=[],nae,nae2,i,len=gongsiarr.length,d,iszb,iszbs,diszb,gongsi,gs1,gs2,bgsa,lens,blarr,j,val,nams;
		
		if(zb>0){
			ans = this.getxuandoi(o1.name);
			nae = ans[3]; //表单name名称
			nae2= ans[2]; //格式0_0
			iszb= parseFloat(ans[0]);
			iszbs = iszb+1; //第几个子表
		}else{
			nae = o1.name;
		}
		
		for(i=0;i<len;i++){
			d 		= gongsiarr[i];
			gongsi 	= d.gongsi;
			if((gongsi+d.fields).indexOf(nae)<0 || isempt(gongsi))continue;
			diszb   = parseFloat(d.iszb);
			if(diszb==0){
				//主表字段公式计算[{zb0.count}*{zb0.price}] - [{discount}]
				bgsa = this.splitgongs(gongsi);
				lens = bgsa.length;
				gongsi = bgsa[lens-1];
				for(j=0;j<lens-1;j++){
					gs2 = bgsa[j];
					gs1 = this.subtongjisd(gs2);
					if(gs1=='')gs1 = this.zhujisuags(gs2,'','',true);
					gongsi = gongsi.replace(gs2, gs1);
				}
				gongsi = gongsi.replace(/\[/g,'');
				gongsi = gongsi.replace(/\]/g,'');
				this.gongsv(d.fields, gongsi,d.gongsi);
				
			}else if(diszb==iszbs && zb>0){
				this.zhujisuags(gongsi, d.fields, nae2, false);//子表行内计算
			}
		}
		oninputblur(nae,zb, o1,ans[0],ans[1]);
	},
	splitgongs:function(gongsi){
		if(gongsi.indexOf(']')<0)gongsi = '['+gongsi+']';
		var carr = gongsi.split(']'),i,bd=[],st;
		for(i=0;i<carr.length;i++){
			st = carr[i];
			st = st.substr(st.indexOf('[')+1);
			if(st)bd.push(st);
		}
		bd.push(gongsi);
		return bd;
	},
	zhujisuags:function(gongsi, fid, nae2, blx){
		var blarr,j,nams,val,ogs;
		ogs	  = gongsi+'';
		blarr = this.pipematch(ogs);
		for(j=0;j<blarr.length;j++){
			nams	= ''+blarr[j]+''+nae2+'';
			val 	= form(nams) ? form(nams).value : '0';
			if(val==='')val='0';
			ogs = ogs.replace('{'+blarr[j]+'}', val);
		}
		if(blx)return '('+ogs+')';
		nams	= ''+fid+''+nae2+'';
		return this.gongsv(nams, ogs, gongsi);
	},
	subtongjisd:function(gongsi){
		var str = '',blarr,zb,i,dds,kes,gss,i1;
		if(gongsi.indexOf('zb0.')>-1 || gongsi.indexOf('zb1.')>-1 || gongsi.indexOf('zb2.')>-1){
			blarr = this.pipematch(gongsi);
			zb    = blarr[0].split('.')[0].replace('zb','');//哪个子表
			dds   = this.getsubdata(zb);
			for(i=0;i<dds.length;i++){
				gss = gongsi+'';
				for(i1 in dds[i])gss=gss.replace('{zb'+zb+'.'+i1+'}', dds[i][i1]);
				str+= '+('+gss+')';
			}
		}
		if(str!=''){
			str = '('+str.substr(1)+')';
		} 
		return str;
	},
	gongsv:function(ne,vlas,gongss){
		var val = '0',vals,val1;
		if(form(ne)){
			try{
				val = eval(vlas);if(!val)val='0';
				val1= 'a'+val+'';vals= val1.split('.');
				if(vals[1] && vals[1].length>2)val=js.float(val);
				form(ne).value=val;
			}catch(e){
				alert(''+ne+'计算公式设置有错误：'+gongss+'\n\n'+vlas+'');
			}
		}
		return val;
	},
	pipematch:function(str){
		var star = str.match(/\{(.*?)\}/gi),i;
		var b 	 = [];
		if(star)for(i=0;i<star.length;i++){
			b.push(star[i].substr(1, star[i].length-2));
		}
		return b;
	}
	//----公式end -----
};
