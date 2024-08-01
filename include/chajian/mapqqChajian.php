<?php 
class mapqqChajian extends Chajian{
	
	private $mapqq_key 		= '';
	
	protected function initChajian()
	{
		$this->getkey();
	}
	
	public function getkey()
	{
		$key = getconfig('qqmapkey');
		if(!$key){
			$key = $this->rock->jm->base64decode('NTVRQlotSkdZTE8tQkFMV1gtU1pFNEgtNVNWNUstSkNGVjc:');
		}else{
			$this->mapqq_key = $key;
		}
		//$this->mapqq_key = $key;
		return $key;
	}
	
	private function mapqqerr($msg=''){
		if(!$msg)$msg = '无法访问腾讯地图接口';
		return '{"status":201,"message":"'.$msg.'"}';;
	}
	
	//获取位置
	public function gcoder($lat, $lng)
	{
		if(!$this->mapqq_key){
			$barr = c('xinhuapi')->getdata('mapqq','gcoder', array(
				'lat' => $lat,
				'lng' => $lng,
			));
			if(!$barr['success'])return $this->mapqqerr($barr['msg']);
			return $barr['data'];
		}else{
			$url = 'https://apis.map.qq.com/ws/geocoder/v1?key='.$this->mapqq_key.'';
			$url.= '&get_poi=0';//不返回周边位置
			$url.= '&location='.$lat.','.$lng.'';
			$url.= '&poi_options=radius=200';
			$result = c('curl')->getcurl($url);
			if(!$result)$result = $this->mapqqerr();
			return $result;
		}
	}
	
	//转坐标
	public function translate($lat, $lng, $type)
	{
		if(!$this->mapqq_key){
			$barr = c('xinhuapi')->getdata('mapqq','translate', array(
				'lat' => $lat,
				'lng' => $lng,
				'type'=> $type
			));
			if(!$barr['success'])return $this->mapqqerr($barr['msg']);
			return $barr['data'];
		}else{
			$url = 'https://apis.map.qq.com/ws/coord/v1/translate?key='.$this->mapqq_key.'';
			$url.= '&locations='.$lat.','.$lng.'';
			$url.= '&type='.$type.'';
			$result = c('curl')->getcurl($url);
			if(!$result)$result = $this->mapqqerr();
			return $result;
		}
	}
	
	//搜索$key 是base64
	public function suggestion($keyword)
	{
		if(!$this->mapqq_key){
			$barr = c('xinhuapi')->getdata('mapqq','suggestion', array(
				'keyword' => $keyword,
			));
			if(!$barr['success'])return $this->mapqqerr($barr['msg']);
			return $barr['data'];
		}else{
			$keyword = $this->rock->jm->base64decode($keyword);
			$keyarr  = explode(' ', $keyword);
			$url = 'https://apis.map.qq.com/ws/place/v1/suggestion?key='.$this->mapqq_key.'';
			$url	.= '&keyword='.$keyarr[0].'';
			if(isset($keyarr[1]))$url.= '&region='.$keyarr[1].'';
			$result = c('curl')->getcurl($url);
			if(!$result)$result = $this->mapqqerr();
			return $result;
		}
	}
}