var kexuan = true;
function initbodys(){
	
	c.onselectdata['custname']=function(){
		form('htid').value = '0';
	}
	
	var defe = js.request('def_htid');
	if(defe && defe<0)salechange(defe);
	
	if(mid>0){
		if(data.xgid && data.xgid>0){
			form('htid').length=2;
			form('money').readOnly=true;
			$(form('money')).click(function(){
				js.alert('关联了其他单据，金额不能修改');
			})
			kexuan = false;
		}
	}else{
		if(!defe)form('htid').selectedIndex =1;
	}
	
	if(kexuan){
		$(form('htid')).change(function(){
			var val = this.value,txt='';
			salechange(val);
		});
	}
}

c.onselectdatabefore=function(fid){
	if(fid=='custname' && !kexuan)return '已关联其他单据不可选择';
}

function salechange(v){
	if(!kexuan)return;
	if(v=='' || v=='0'){
		form('custid').value='';
		return;
	}
	js.ajax(geturlact('ractchange'),{ractid:v},function(a){
		form('custid').value=a.custid;
		form('custname').value=a.custname;
		form('money').value=a.money;
		if(form('type'))form('type').value=a.type;
		form('htnum').value=a.num;
		form('dt').value=a.signdt;
	},'get,json');
}

function changesubmit(d){
	if(d.ispay=='1'){
		if(form('paytpye') && !d.paytpye)return '已收款了，收款类型不能为空';
		if(!d.paydt)return '已收款了，收款时间不能为空';
	}
}