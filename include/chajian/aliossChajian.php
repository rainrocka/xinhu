<?php
/**
*	阿里云oss服务
*/
class aliossChajian extends Chajian{
	
	private $accesskeyid;
	private $accesskeysecret;
	
	private $vendorbool=false;
	private $ossClient=false;
	
	protected function initChajian()
	{
		$this->accesskeyid 		= getconfig('alioss_keyid');
		$this->accesskeysecret 	= getconfig('alioss_keysecret');
		$this->folder 		= getconfig('alioss_folder');
		$this->bucket 		= getconfig('alioss_bucket');
		
		$path = ''.ROOT_PATH.'/include/vendor/autoload.php';
		if(file_exists($path) && $this->accesskeysecret){
			require_once($path);
			$this->vendorbool = true;
		}
	}
	
	public function isbool()
	{
		return $this->vendorbool;
	}
	

	
	private function getOssClient()
	{
		if(!$this->ossClient){
			$fq 	  = getconfig('alioss_region');
			$endpoint = 'http://oss-cn-'.$fq.'.aliyuncs.com';
			$this->ossClient = new \OSS\OssClient($this->accesskeyid, $this->accesskeysecret, $endpoint);
		}
		return $this->ossClient;
	}
	
	/**
	*	上传文件到oss
	*/
	public function uploadFile($path)
	{
		if(!$this->isbool())return returnerror('no install alioss');
		try{
			$ossClient = $this->getOssClient();
			$barr 	   = $ossClient->uploadFile($this->bucket, $this->folder.'/'.$path, ROOT_PATH.'/'.$path);
			if(is_array($barr)){
				$info  = $barr['info'];
				$carr = returnsuccess($barr);
				$carr['code']= 0;
				$carr['url'] = str_replace('http:','https:', $info['url']);
				$carr['filesize'] = $info['request_size'];
				return $carr;
			}else{
				return returnerror();
			}
		} catch(\OSS\Core\OssException $e) {
			return returnerror($e->getMessage());
		}
	}
	
	/**
	*	下载文件
	*/
	public function download($path, $dstPath)
	{
		if(!$this->isbool())return returnerror('no install alioss');
		try{
			$ossClient = $this->getOssClient();
			$localfile = $dstPath;
			$options = array(
				\OSS\OssClient::OSS_FILE_DOWNLOAD => $localfile
			);
			$ossClient->getObject($this->bucket, $path, $options);
			$carr = returnsuccess($barr);
			$carr['code']= 0;
			return $carr;
		} catch(\OSS\Core\OssException $e) {
			return returnerror($e->getMessage());
		}
	}
}