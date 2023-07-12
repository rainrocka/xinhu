var rocklang = '',rocklangxu=0,langdata = {"\u7f16\u8f91":["","\u7de8\u8f2f","edit"],"\u5220\u9664":["","\u522a\u9664","delete"]};
function lang(ky){
	if(!rocklang){rocklang = $('html').attr('lang');if(!rocklang)rocklang='zh-CN';if(rocklang=='zh-CN')rocklangxu=0;if(rocklang=='zh-FT')rocklangxu=1;if(rocklang=='en-US')rocklangxu=2;}
	var d = langdata[ky];
	if(!d)return ky;
	var str = d[rocklangxu];
	if(!str)str = ky;
	return str;
}