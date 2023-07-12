<?php
/**
*	语言包
*/
class langChajian extends Chajian{
	
	//支持的语言包
	public $langArray	= array('zh-CN','zh-FT','en-US');
	public $langArraycn	= array('简体中文','繁体中文','英文');
	public $locallang	= 'zh-CN'; //默认的语言包
	
	/**
	*	初始化语言包
	*/
	public function initLang()
	{
		$moren= getconfig('locallang', $this->locallang);
		$lang = $this->rock->get('locallang', $moren);
		if(!in_array($lang, $this->langArray))$lang = $moren;
		if(!defined('LANG'))define('LANG', $lang);
		$xuhao= 0;
		foreach($this->langArray as $k=>$v){
			if($v==$lang)$xuhao = $k;
		}
		$GLOBALS['langdata'] = array(
			'lang' 		=> $lang,
			'xuhao' 	=> $xuhao
		);
	}
	
	public function getLocal()
	{
		return array(
			'arr' 	=> $this->langArray,
			'arrcn' => $this->langArraycn,
		);
	}
	
	/**
	*	生成语言包文件
	*/
	public function createlocal()
	{
		$bar = glob('include/langlocal/langtxt/*.txt');
		$path= 'include/langlocal/langphp/lang.php';
		$pats= 'include/langlocal/langphp/langjs.php';
		$sss = $ssb = '';
		if(is_array($bar))foreach($bar as $k=>$fil1){
			$str = $this->getcontarr($fil1);
			if($str){
				$isph = 1;
				$isjs = 0;
				if(contain($fil1,'_onlyjs')){
					$isph = 0;
					$isjs = 1;
				}
				if(contain($fil1,'_onlyphp')){
					$isph = 0;
				}
				if($isph==1){
					if($sss)$sss.=',';
					$sss.=''.$str.'';
				}
				if($isjs==1){
					if($ssb)$ssb.=',';
					$ssb.=''.$str.'';
				}
				if($isph==0 && $isjs==0){
					$fname = str_replace('_onlyphp.txt','.php',str_replace('include/langlocal/langtxt/','',$fil1));
					$spath = 'include/langlocal/langphp/'.$fname.'';
					$this->rock->createtxt($spath, '<?php'.chr(10).'return array('.$str.');');
				}
			}
		}
		$str = '<?php'.chr(10).'return array('.$sss.');';
		$this->rock->createtxt($path, $str);
		
		
		$str = '<?php'.chr(10).'return array('.$ssb.');';
		$this->rock->createtxt($pats, $str);
		
		$nrs = require($pats);
		$nrs = 'var rocklang = \'\',rocklangxu=0,langdata = '.json_encode($nrs).';';
		$ss1 = '';
		foreach($this->langArray as $k=>$v){
			$ss1.='if(rocklang==\''.$v.'\')rocklangxu='.$k.';';
		}
$nrs.="
function lang(ky){
	if(!rocklang){rocklang = $('html').attr('lang');if(!rocklang)rocklang='".$this->locallang."';".$ss1."}
	var d = langdata[ky];
	if(!d)return ky;
	var str = d[rocklangxu];
	if(!str)str = ky;
	return str;
}";
		$this->rock->createtxt('js/lang.js', $nrs);
		unlink($pats);
		return returnsuccess();
	}
	
	public function getcontarr($file,$lx=0)
	{
		if(!file_exists($file))return '';
		$cont = file_get_contents($file);
		$arra = explode("\n", $cont);
		$str  = '';
		$ssb  = '';
		foreach($arra as $k=>$strb){
			if(!$strb)continue;
			$strb = str_replace(array('^M',"\n","\r"),'', $strb);
			$arrx = explode('|', $strb);
			if($k>0){
				$str.=',';
				$ssb.=',';
			}
			$keys= $arrx[0];
			$v001= '';
			if(contain($keys,'::')){
				$arr1 = explode('::', $keys);
				$keys = $arr1[0];
				$v001 = $arr1[1];
			}
			$str.='\''.$keys.'\'=>array(\''.$v001.'\'';
			$ssb.='"'.$keys.'":["'.$v001.'"';
			$len = count($arrx);
			foreach($arrx as $k1=>$v1)if($k1>0){
				$str.=',\''.$v1.'\'';
				$ssb.=',"'.$v1.'"';
			}
			$str.=')';
			$ssb.=']';
		}
		if($lx==0)return $str;
		if($lx==1)return $ssb;
	}
}