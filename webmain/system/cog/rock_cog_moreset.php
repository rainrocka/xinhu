<?php if(!defined('HOST'))die('not access');?>
<script>
$(document).ready(function(){
	
	var c = {
		init:function(){
			
		},
		tesgs:function(o1,lx){
			$('#tagsl{rand}').find('li').removeClass('active');
			o1.className='active';
			$('#tablstal0{rand}').hide();
			$('#tablstal1{rand}').hide();
			$('#tablstal2{rand}').hide();
			$('#tablstal3{rand}').hide();
			$('#tablstal'+lx+'{rand}').show();
			if(lx==1)this.showmode();
		},
		savecog:function(o1,lx){
			var msgid = 'msgview'+lx+'_{rand}';
			var da = js.getformdata('form'+lx+'_{rand}');
			js.setmsg('保存中...','', msgid);
			da.stype = lx;
			js.ajax(js.getajaxurl('savemoreset','{mode}','{dir}'), da, function(s){
				if(s=='ok'){
					js.setmsg('保存成功','green', msgid);
				}else{
					js.setmsg(s,'', msgid);
				}
			},'post');
		},
		showmode:function(){
			if(this.showmodebool)return;
			this.showmodebool=true;
			js.ajax(js.getajaxurl('getmode','{mode}','{dir}'),{},function(ret){
				c.showmodedata(ret);
			},'get,json');
		},
		showmodedata:function(ret){
			var da = ret.modearr;
			var i,len=da.length,str='',typs='',typarr={};
			for(i=0;i<len;i++){
				if(!typarr[da[i].type])typarr[da[i].type]=[];
				typarr[da[i].type].push(da[i]);
			}
			this.xuannum = [];
			var xues = ',gong,vcard,remind,tovoid,';
			var less = ',公文,进销存,客户,工程,考勤,物品,车辆,学校,物业,';
			
			var d,i1,i2=0,i3=0,dis='',bh='',zs=0;
			for(i in typarr){
				dis='';
				d = typarr[i];
				bh='abc';
				zs=0;
				for(i1=0;i1<d.length;i1++)if(d[i1].status=='1')zs++;
				if(zs==d.length)dis='checked';
				if(i=='系统'){
					dis='disabled checked';
				}
				if(i=='客户')bh='crm';
				if(i=='物品')bh='wupin';
				if(i=='公文')bh='gongwen';
				if(i=='车辆')bh='cheliang';
				if(i=='考勤')bh='kaoqin';
				if(i=='工程')bh='gongcheng';
	
				i3++;
				str+='<div><label><input name="mknums{rand}" '+dis+' lexing="'+i3+'" onclick="xuan{rand}.xuanlx(this,'+i3+')" type="checkbox" value="'+bh+'">'+i+'('+d.length+')</label></div><div style="border-bottom:1px #cccccc solid;padding:5px;margin-bottom:10px"><table><tr>';
				i2=0;
				for(i1=0;i1<d.length;i1++){
					dis='';
	
					if(d[i1].status=='1'){
						dis='checked';
					}
					if(i=='系统' || xues.indexOf(','+d[i1].num+',')>-1){
						dis+=' disabled';
						this.xuannum.push(d[i1].num);
					}
					
					if(less.indexOf(','+i+',')>-1)dis+=' onclick="xuan{rand}.xuanlx(this,'+i3+')"';
					
					i2++;
					str+='<td width="25%" style="padding:5px"><label style="font-weight:normal"><input '+dis+' lexing="'+i3+'" name="mknums{rand}" type="checkbox" value="'+d[i1].num+'">'+d[i1].name+'</label></td>';
					if(i2%4==0)str+='</tr><tr>';
				}
				str+='</tr></table></div>';
			}
			$('#createmodel{rand}').html(str);
		},
		xuanlx:function(o1,i3){
			var obj = $('input[lexing="'+i3+'"]');
			for(var i=0;i<obj.length;i++){
				if(!obj[i].disabled)obj[i].checked = o1.checked;
			}
		},
		createok:function(o1){
			var sids = js.getchecked('mknums{rand}');
			var abs  = this.xuannum.join(',');
			if(sids)abs+=','+sids+'';
			js.loading('处理中...');
			o1.disabled=true;
			js.ajax(js.getajaxurl('savemode','{mode}','{dir}'),{allnum:abs},function(res){
				if(res.indexOf('成功')>-1){
					js.msgok(res);
				}else{
					js.msgerror(res);
				}
				o1.disabled=false;
			},'post');
		}
	};
	js.initbtn(c);
	c.init();
	xuan{rand} = c;
});
</script>
<div style="padding:10px 30px">
	
	<ul id="tagsl{rand}" class="nav nav-tabs">
	  
	  <li click="tesgs,0" class="active">
		<a style="TEXT-DECORATION:none"><i class="icon-cog"></i> 基本设置</a>
	  </li>
	  <li  click="tesgs,1">
		<a style="TEXT-DECORATION:none">模块启用停用</a>
	  </li>

	</ul>

	<div style="padding-top:20px">
		
		<form  id="tablstal0{rand}" name="form0_{rand}">
			<table cellspacing="0"  border="0" cellpadding="0">
			
			
			<tr><td align="right" style="color:gray">系统标题：</td><td><input name="title" type="text" value="<?=getconfig('title')?>" style="width:300px" class="form-control"></td></tr>
			<tr><td height="10"></td></tr>
			
		
			
			<tr><td align="right" style="color:gray">图片压缩尺寸：</td><td><input name="imgcompress" type="text" value="<?=getconfig('imgcompress')?>" style="width:200px" placeholder="不设置不压缩" class="form-control">
			<span style="color:#aaaaaa;">仅对jpg文件压缩格式：宽x高，如800x1000</span>
			</td></tr>
			<tr><td height="10"></td></tr>
			
			<tr><td align="right" style="color:gray">详情页水印：</td><td><select style="width:200px" name="watertype" class="form-control"><option value="">默认没有开启</option><option <?php if(getconfig('watertype')=='1')echo 'selected';?> value="1">开启</option></select>
			<span style="color:#aaaaaa;"><a target="_blank" href="<?=URLY?>view_shuiyin.html">帮助</a>说明</span></td></tr>
			<tr><td height="10"></td></tr>
			
			<tr><td align="right" style="color:gray">APP音视频通话：</td><td><select style="width:200px" name="video_bool" class="form-control"><option value="0">关闭</option><option <?php if(getconfig('video_bool'))echo 'selected';?> value="1">开启</option></select>
			<span style="color:#aaaaaa;">开启后需要到[系统→系统工具→插件模块]安装音视频通话的插件</span></td></tr>
			<tr><td height="10"></td></tr>
			
			<tr><td align="right" style="color:gray">人员审批撤回时间：</td><td><input name="flowchehuitime" type="number" value="<?=getconfig('flowchehuitime')?>" onfocus="js.focusval=this.value" onblur="js.number(this)" min="0" style="width:200px" placeholder="默认是2小时" class="form-control"></select>
			<span style="color:#aaaaaa;">默认2小时，已完成审批不支持撤回</span></td></tr>
			<tr><td height="10"></td></tr>
			
			<tr><td align="right" style="color:gray">SAAS多单位模式：</td><td><select style="width:200px" name="saasmode" class="form-control"><option value="">默认没有开启</option><option <?php if(getconfig('saasmode')=='1')echo 'selected';?> value="1">开启(用不到不要开启)</option></select>
			<span style="color:#aaaaaa;">授权版使用，<a target="_blank" href="<?=URLY?>view_xinhuduo.html">帮助</a>说明，每个单位一个访问地址数据库分开。</span></td></tr>
			<tr><td height="10"></td></tr>
			
			<tr><td align="right" style="color:gray">PC端首页登录超时：</td><td><input name="hoemtimeout" type="number" value="<?=getconfig('hoemtimeout')?>" onfocus="js.focusval=this.value" onblur="js.number(this)" min="0" style="width:200px" placeholder="单位分钟" class="form-control"></select>
			<span style="color:#aaaaaa;">单位分钟，默认是0没有限制</span></td></tr>
			<tr><td height="10"></td></tr>
			
			<tr><td align="right" style="color:gray">读取人员本地缓存方式：</td><td><select style="width:200px" name="usercache" class="form-control"><option value="">默认本地浏览器缓存</option><option <?php if(getconfig('usercache')=='1')echo 'selected';?> value="1">不缓存本地浏览器</option></select></td></tr>
			<tr><td height="10"></td></tr>
			
			<tr>
				<td  align="right"></td>
				<td align="left"><button click="savecog,0" class="btn btn-success" type="button"><i class="icon-save"></i>&nbsp;保存</button>&nbsp;<span id="msgview0_{rand}"></span>
			</td>
			</tr>
		
			</table>
		</form>
	
	
		<div id="tablstal1{rand}" style="display:none">
			<div id="createmodel{rand}"></div>
			<div>
				<button type="button" click="createok" class="btn btn-success"> 确定 </button>
			</div>
		</div>
	

	</div>
</div>