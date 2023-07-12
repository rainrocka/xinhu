<?php 
/**
*	个推2023-07-06
*/
class getuiChajian extends Chajian{
	
	private $appid 	 		= '';
	private $appkey 		= '';
	private $mastersecret 	= '';
	private $apptype 		= '0'; //0所有平台,1仅安卓，2仅苹果
	

    private $pushurl 	= 'https://restapi.getui.com/v2/{appid}';

	protected function initChajian()
	{
		$this->appid 		= getconfig('getui_appid');
		$this->appkey 		= getconfig('getui_appkey');
		$this->mastersecret = getconfig('getui_mastersecret');
		$this->apptype 		= getconfig('getui_apptype','0');
	}
	
	/**
	*	获取token
	*/
	public function gettoken(){
		$url 	= str_replace('{appid}',$this->appid, $this->pushurl).'/auth';
		$token 	= c('cache')->get('getui'.$this->appid.'');
		if(isempt($token)){
			$timestamp 	=   ''.time().'000';
			$sign		= hash("sha256", $this->appkey.$timestamp.$this->mastersecret);
			$result 	= c('curl')->postcurl($url, json_encode(array( 
				"sign" 		=> $sign,
				"timestamp" => $timestamp,
				"appkey" 	=> $this->appkey, 
			)),0, array(
				'content-type' => 'application/json;charset=utf-8'
			));
			if($result){
				$barr 	= json_decode($result, true);
				if($barr['code']==0){
					$token 			= $barr['data']['token'];
					$expire_time 	= $barr['data']['expire_time'];
					c('cache')->set('getui'.$this->appid.'',$token, $expire_time * 0.001 - time());
				}else{
					echo $result;
				}
			}
		}
		return $token;
    }
	
	/**
	*	判断是否可以发送
	*/
	public function sendbool()
	{
		if(!$this->appid || !$this->appkey || !$this->mastersecret)return false;
		return true;
	}
	
	/**
	*	是否安卓的
	*/
	public function isandroid()
	{
		if(!$this->sendbool())return false;
		if($this->apptype=='2')return false;
		return true;
	}
	
	/**
	*	是否安卓的
	*/
	public function isios()
	{
		if(!$this->sendbool())return false;
		if($this->apptype=='1')return false;
		return true;
	}
	
	/**
	*	推送
	*/
	public function push($cid, $title, $cont)
	{
		if(!$this->sendbool())return 'params empty';
		$url 	= str_replace('{appid}',$this->appid, $this->pushurl).'/push/single/batch/cid';
		$token  = $this->gettoken();
		if(is_string($cid))$cid  = explode(',', $cid);
		
		$msg_list = array();
		
		foreach($cid as $_cid){
			$parr = array();
			$parr['request_id']  = 'a'.time().rand(1000,9999).'';
			//$parr['settings'] 	 = array('ttl' => '-1');
			$parr['audience']['cid'] = array($_cid);
			$parr['push_message']['notification'] = array(
				'title' => $title,
				'body' => $cont,
				'click_type' => 'startapp',
			);
			//离线厂商推送的
			$parr['push_channel']['ios'] = array(
				'type' 	  => 'notify',
				'payload' => 'notify',
				'aps' 	  => array(
					'alert' => array(
						'title' => $title,
						'body' => $cont,
					),
					'sound'=>'default',
				),
				'auto_badge' => '1'
			);
			
			$parr['push_channel']['android'] = array(
				'ups' => array(
					'notification' => array(
						'title' => $title,
						'body' => $cont,
						'click_type' => 'startapp',
						'notify_id' => rand(100,99999),
					)
				)
			);
			$msg_list[] = $parr;
		}
		$toboay  	= array(
			'is_async' => false,
			'msg_list' => $msg_list
		);
		$result 	= c('curl')->postcurl($url, json_encode($toboay),0, array(
			'content-type' => 'application/json;charset=utf-8',
			'token' => $token,
		));
		return $result;
	}
	
}