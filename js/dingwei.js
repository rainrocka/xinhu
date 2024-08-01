/**
*	定位文件
*	创建人：雨中磐石(rainrock)
*/

//jssdk回调过来的
js.jssdkcall  = function(bo){
	js.dw.start();//开始定位
}
var openfrom = '';
function initApp(){
	js.dw.start();
}
js.dw = {
	
	//开始定位
	init:function(isgzh){
		var dws = navigator.userAgent;
		if(dws.indexOf('REIMPLAT')>0)return;
		if(openfrom=='nppandroid' || openfrom=='nppios')return;
		if(isgzh==1){
			js.jssdkwxgzh();
		}else{
			js.jssdkwixin();
		}
	},

	dwbool:false,
	dwtimeer:false,
	ondwcall:function(){},
	ondwstart:function(){},
	ondwerr:function(){},
	successbo:false,
	ondwwait:function(){return false},
	
	start:function(){
		if(this.dwbool)return;
		this.successbo = false;
		this.dwbool = true;
		this.chaoshi();
		this.ondwstart(js.jssdkstate);
		if(js.jssdkstate != 1){
			this.htmldingw(0);
		}else{
			this.wxdingw();
		}
	},
	
	//定位等待
	wait:function(msg){
		var bo = this.ondwwait(msg);
		if(!bo)js.msg('wait',msg);
	},
	
	chaoshi:function(){
		clearTimeout(this.dwtimeer);
		this.dwtimeer = setTimeout(function(){
			var msg = '定位超时，请重新定位';
			js.msg('msg', msg);
			js.dw.ondwerr(msg);
			js.jssdkstate = 2;
			js.dw.dwbool=false;
		},20*1000);
	},
	clearchao:function(){
		clearTimeout(this.dwtimeer);
		this.dwbool = false;
	},
	
	//html5定位
	htmldingw:function(lx){
		var msg;
		if(appobj1('startLocation','appbacklocation')){
			this.wait('原生app定位中...');
			return;
		}
		if(window['api'] && api.startLocation){
			js.msg();
			if(api.systemType=='ios'){
				this.wait(''+api.systemType+'APP定位中...');
				api.startLocation({},function(ret,err){
					js.dw.appLocationSuc(ret,err);
				});
				return;
			}else if(lx==0){
				this.wait(''+api.systemType+'百度地图定位中...');
				if(!this.baiduLocation)this.baiduLocation = api.require('baiduLocation');
				if(this.baiduLocation){
					this.baiduLocation.startLocation({
						autoStop: false
					}, function(ret, err) {
						js.dw.baiduLocationSuc(ret,err);
					});
				}else{
					if(!this.bmLocation)this.bmLocation = api.require('bmLocation');
					if(this.bmLocation){
						this.bmLocation.configManager({
							coordinateType:'BMK09LL',accuracy:'hight_accuracy'
						});
						this.bmLocation.singleLocation({reGeocode:false},function(ret,err){
							var dtes = {};
							dtes.status = ret.status;
							if(ret.status){
								dtes.longitude = ret.location.longitude;
								dtes.latitude = ret.location.latitude;
							}
							js.dw.baiduLocationSuc(dtes,err);
							js.dw.bmLocation.stopLocation();
						});
						
					}
				}
				return;
			}
		}
		
		if(!navigator.geolocation){
			msg = '不支持浏览器定位';
			js.msg('msg',msg);
			this.clearchao();
			js.dw.ondwerr(msg);
		}else{
			this.wait('浏览器定位中...');
			//本地虚拟定位
			if(HOST=='127.0.0.1'){this.showPosition({coords:{latitude:24.51036967,longitude:118.178837299,accuracy:100}});return;}
			navigator.geolocation.getCurrentPosition(this.showPosition,this.showError,{
				enableHighAccuracy: true,
				timeout: 19000,
				maximumAge: 3000
			});
		}
	},
	
	
	//微信定位
	wxdingw:function(){
		var msg = '微信定位中...';
		if(js.isqywx)msg='企业微信定位中...';
		this.wait(msg);
		wx.getLocation({
			type: 'gcj02',
			success: function (res,err){
				js.dw.dwsuccess(res,err);
			},
			error:function(){
				js.jssdkstate = 2;
				js.dw.dwbool=false;
				js.dw.start(); 
			}
		});
	},
	appLocationSuc:function(ret,err){
		if(ret.status){
			if(!ret.accuracy)ret.accuracy = 200;
			this.dwsuccess(ret);
		}else{
			this.dwshibai(err.msg);
		}
	},
	
	baiduLocationSuc:function(ret,err){
		if(ret.status && ret.latitude){
			this.wait('百度定位成功，获取位置信息...');
			if(!ret.accuracy)ret.accuracy = 200;
			this.translate(ret.latitude, ret.longitude, ret.accuracy, 3);
		}else{
			this.dwshibai('定位失败，检查是否给APP开定位权限');
		}
	},
	dwshibai:function(msg){
		this.clearchao();
		js.setmsg('');
		js.msg('msg', msg);
		this.ondwerr(msg);
	},
	dwsuccess:function(res){
		this.wait('定位成功，获取位置信息...');
		this.clearchao();
		var lat 	= parseFloat(res.latitude); // 纬度，浮点数，范围为90 ~ -90
        var lng 	= parseFloat(res.longitude); // 经度，浮点数，范围为180 ~ -180。
        var jid 	= parseFloat(res.accuracy); // 位置精度
		this.geocoder(lat,lng, jid);
	},
		
	showError:function (error){
		js.dw.clearchao();
		js.setmsg('');
		var msg='无法定位';
		switch(error.code){
		case error.PERMISSION_DENIED:
			msg="用户拒绝对获取地理位置的请求。"
			break;
		case error.POSITION_UNAVAILABLE:
			msg="位置信息是不可用的。"
			break;
		case error.TIMEOUT:
			msg="请求用户地理位置超时。"
			break;
		case error.UNKNOWN_ERROR:
			msg="未知错误。"
			break;
		}
		if(NOWURL.substr(0,5)!='https')msg+='必须使用https访问';
		js.dw.timeerrbo = setTimeout(function(){
			if(!js.dw.successbo){
				js.msg('msg', msg);
				js.dw.ondwerr(msg);	
			}else{
				js.msg();
			}
		},1000);
	},
	
	showPosition:function(position){
		js.dw.successbo = true;
		clearTimeout(js.dw.timeerrbo);
		js.msg();
		var res 		= position.coords;
		var latitude 	= res.latitude;
		var longitude 	= res.longitude;
		var accuracy 	= parseFloat(res.accuracy);
		js.dw.translate(latitude,longitude, accuracy, 1);
	},
	
	//坐标转化type1原始
	translate:function(lat, lng,juli, type){
		$.ajax({
			url:'api.php?m=kaoqin&a=translate',
			data:{
				lat:lat,
				lng:lng,
				type:type
			},
			dataType:'json',
			success:function(ret){
				if(ret.status==0){
					js.dw.dwsuccess({
						latitude:ret.locations[0].lat,
						longitude:ret.locations[0].lng,
						accuracy:juli
					});
				}else{
					js.dw.dwshibai('无法转化坐标('+lat+','+lng+'),'+type+'<br>'+ret.status+','+ret.message+'');
				}
			},
			error:function(){
				js.dw.dwshibai('无法转化坐标'+type+'');
			}
		});
	},
	
	//搜索位置,2024-07-19改
	geocoder:function(lat,lng, jid){
		var errcan  = {
			latitude:lat,
			longitude:lng,
			accuracy:jid,
			address:'未知位置',
			addressinfo:'定位成功未知位置',
			detail:'未知位置'
		}
		$.ajax({
			url:'api.php?m=kaoqin&a=gcoder',
			data:{
				lat:lat,
				lng:lng,
			},
			dataType:'json',
			success:function(ret){
				if(ret.status==0 && ret.result){
					var result = ret.result,addressinfo;
					var address= result.formatted_addresses.recommend;
					if(!address)address = result.address;
					addressinfo = ''+address;
					if(jid>0)addressinfo+='(精确'+js.float(jid,1)+'米)';
					js.msg();
					errcan.address = address;
					errcan.addressinfo = addressinfo;
					errcan.detail = result;
					js.dw.ondwcall(errcan);
				}else{
					if(ret.message)js.msg('msg', ret.status+':'+ret.message);
					js.dw.ondwcall(errcan);
				}
			},
			error:function(){
				js.dw.ondwcall(errcan);
			}
		});
	},
	
	//计算距离,old
	matrix:function(lat,lng, kqarr, funs){
		var fromstr = ''+lat+','+lng+'',tostr='';
		for(var i=0;i<kqarr.length;i++){
			if(i>0)tostr+=';';
			tostr +=''+kqarr[i].location_x+','+kqarr[i].location_y+'';
		}
		if(fromstr && tostr){
			$.ajax({
				url:'api.php?m=kaoqin&a=matrix',
				data:{
					fromstr:fromstr,
					tostr:tostr,
				},
				dataType:'json',
				success:function(ret){
					if(ret.status==0){
						var rows = ret.result.rows[0].elements;
						for(var j=0;j<rows.length;j++)kqarr[j].kqjuli = rows[j].distance;
						funs(kqarr);
					}else{
						alert('计算距离('+ret.status+'):'+ret.message);
						funs(kqarr);
					}
				},
				error:function(e){
					alert('接口出错无法计算距离');
					funs(kqarr);
				}
			});
		}else{
			funs(kqarr);
		}
	},
	//计算距离
	julisuan:function(lat,lng, kqarr, funs){
		var startPoint = new TMap.LatLng(lat, lng);
		for(var i=0;i<kqarr.length;i++){
			var path = [startPoint , new TMap.LatLng(parseFloat(kqarr[i].location_x), parseFloat(kqarr[i].location_y))];
			var distance = TMap.geometry.computeDistance(path);
			kqarr[i].kqjuli = parseFloat(distance);
		}
		funs(kqarr);
	}
};

//原生app定位中
appbacklocation=function(res){
	var latitude 	= res.latitude;
	var longitude 	= res.longitude;
	var accuracy 	= parseFloat(res.accuracy);
	js.dw.dwsuccess({
		latitude:latitude,
		longitude:longitude,
		accuracy:accuracy
	});
}