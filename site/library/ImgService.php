<?php
class ImgService {
	private $imgR;
	private $imgW;
	private $curl;
	private $rLinkCache;
	private $wLinkCache;
	private $imageHome;
	private $imageServer;
	CONST SERVER_LOCAL = 'LOCAL';      // 对象存储在本地环境
	CONST SERVER_OSS = 'OSS';          // 本地独立部署oss环境
	CONST SERVER_ALIYUN = 'ALIYUN';    // 第三方阿里云对象存储
	CONST SERVER_AWSOSS = 'AWSOSS';    // 第三方aws对象存储
	CONST SERVER_SDOSS = 'SDOSS';      // 第三方苏宁对象存储
	CONST SERVER_AZURE = 'AZURE';      // 第三方azure对象存储

	/**
	 * 构造函数
	 *
	 * @param Curl $curl        	
	 */
	public function __construct($curl) {
		global $global;
		$this->imgR = $global ['oss']['img_r'];
		$this->imgW = $global ['oss']['img_w'];
		$this->curl = $curl;
		$this->useLocalImage = false;
		if (defined('IMAGE_SERVER') && in_array(IMAGE_SERVER, array(self::SERVER_LOCAL,self::SERVER_OSS,self::SERVER_SDOSS,self::SERVER_ALIYUN,self::SERVER_AWSOSS,self::SERVER_AZURE))){
			$this->imageServer = IMAGE_SERVER;
		}
		else{
			$this->imageServer = self::SERVER_OSS;
		}
		if ($this->imageServer == self::SERVER_LOCAL){
			$this->imageHome = DIR_IMAGE;
		}
	}

	public function exist($filePath){
		if ($this->imageServer == self::SERVER_OSS){
			$this->resetRLinkCache ();
			$fileName = ltrim($filePath, '/');
			while ( ($link = $this->getRLink ()) !== null ) {
				$this->curl->setOpt ( CURLOPT_PORT, $link ['port'] );
				$url = 'http://' . $link ['host'] . ':' . $link ['port'] . '/' . $fileName;
				$ch = curl_init();
				$timeout = 10;
				curl_setopt ($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_HEADER, true);
				curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
				$result = curl_exec ( $ch );
				$info = curl_getinfo ( $ch );
				curl_close ( $ch );
				if ($info ['http_code'] == 404){
					return false;
				}
				else{
					return true;
				}
			}
			return false;
		}
		else if ($this->imageServer == self::SERVER_LOCAL){
			$fileName = $this->imageHome.ltrim($filePath, '/');
			return is_file($fileName);
		}
		else if ($this->imageServer == self::SERVER_ALIYUN){
			return true;
		}
		else if ($this->imageServer == self::SERVER_AWSOSS){
			return true;
		}
		else{
			return false;
		}
	}
	
	public function delImg($filePath) {
		if ($this->imageServer == self::SERVER_OSS){
			// 重置随机链接池
			$this->resetWLinkCache ();
			// 随机获取一个写文件链写入, 如果都失败, 返回失败
			$filePath = ltrim ( $filePath, '/' );
			while ( ($link = $this->getWLink ()) !== null ) {
				$fileSaveUrl = 'http://' . $link ['host'] . ':' . $link ['port'] . '/' . $filePath;
				$handle = curl_init ( $fileSaveUrl );
				curl_setopt ( $handle, CURLOPT_CUSTOMREQUEST, "DELETE" );
				curl_setopt ( $handle, CURLOPT_RETURNTRANSFER, true );
				$result = curl_exec ( $handle );
				$info = curl_getinfo ( $handle );
				curl_close ( $handle );
				if ($info ['http_code'] == 202) {
					return true;
				} else {
					return false;
				}
			}
			return false;
		}
		else if ($this->imageServer == self::SERVER_LOCAL){
			$file = $this->imageHome.ltrim ( $filePath, '/' );
			return unlink($file);
		}
		else if ($this->imageServer == self::SERVER_ALIYUN){
			return true;
		}
		else if ($this->imageServer == self::SERVER_AWSOSS){
			$filePath = ltrim ( $filePath, '/' );
			require_once DIR_VENTOR.'awsoss/AwsUpload.php';
			return AwsUpload::del($filePath);
		}
		else{
			return false;
		}
	}
	
	public function delDir($filePath) {
		if ($this->imageServer == self::SERVER_OSS){
			// 重置随机链接池
			$this->resetWLinkCache ();
			// 随机获取一个写文件链写入, 如果都失败, 返回失败
			$filePath = trim ( $filePath, '/' );
			while ( ($link = $this->getWLink ()) !== null ) {
				$fileSaveUrl = 'http://' . $link ['host'] . ':' . $link ['port'] . '/dir/delete?dir=/' . $filePath.'/&threads=10&trafficLimit=1000&pretty=y';
				$handle = curl_init ( $fileSaveUrl );
	// 			curl_setopt ( $handle, CURLOPT_CUSTOMREQUEST, "DELETE" );
				curl_setopt ( $handle, CURLOPT_RETURNTRANSFER, true );
				$result = curl_exec ( $handle );
				$info = curl_getinfo ( $handle );
				curl_close ( $handle );
				if ($info ['http_code'] == 202) {
					return true;
				} else {
					return false;
				}
			}
			return false;
		}
		else if ($this->imageServer == self::SERVER_LOCAL){
			$filePath = trim ( $filePath, '/' );
			$path = $this->imageHome.$filePath;
			$this->unlinkRecursive($path, true);
			return true;
		}
		else if ($this->imageServer == self::SERVER_ALIYUN){
			return true;
		}
		else if ($this->imageServer == self::SERVER_AWSOSS){
			$filePath = trim ( $filePath, '/' );
			require_once DIR_VENTOR.'awsoss/AwsUpload.php';
			return AwsUpload::del($filePath.'/');
		}
		else if ($this->imageServer == self::SERVER_AZURE){
		    //require_once DIR_VENTOR . 'azure.php';
		    return false;
		}
		else{
			return false;
		}
	}

	/**
	 * 存储图片
	 *
	 * @param string $filePath
	 *        	待上传文件路径
	 * @param string $saveDir
	 *        	文件存储目录
	 * @param string $fileType
	 *        	文件mime类型
	 */
	public function saveImg($filePath, $saveDir, $fileName, $fileType) {
		if ($this->imageServer == self::SERVER_OSS){
			// 重置随机链接池
			$this->resetWLinkCache ();
			$saveDir = trim ( $saveDir, '/' );
			// 随机获取一个写文件链写入, 如果都失败, 返回失败
			while ( ($link = $this->getWLink ()) !== null ) {
				$fileSaveUrl = 'http://' . $link ['host'] . ':' . $link ['port'] . '/' . $saveDir . '/';
				$res = $this->realSave ( $filePath, $fileSaveUrl, $fileName, $fileType, $link ['port'] );
				if ($res) {
					return true;
				}
			}
			return false;
		}
		else if ($this->imageServer == self::SERVER_LOCAL){
			//写入文件
			if(!is_file($filePath)){
				return false;
			}
			$saveDir = trim ( $saveDir, '/' );
			$targetDir = $this->imageHome.$saveDir;
			if (!is_dir($targetDir)){
				if (!mkdir($targetDir, 0755, true)){
					return false;
				}
			}
			return copy($filePath, $targetDir.'/'.$fileName);
		}
		else if ($this->imageServer == self::SERVER_ALIYUN){
			require_once DIR_VENTOR . 'aliyunoss/samples/OssUpload.php';
			$saveDir = trim ( $saveDir, '/' );
			return OssUpload::upload($filePath, $saveDir.'/'.$fileName);
		}
		else if ($this->imageServer == self::SERVER_AWSOSS){
			require_once DIR_VENTOR.'awsoss/AwsUpload.php';
			$saveDir = trim ( $saveDir, '/' );
			if (SYS_VERSION == 'PRD') {
			    $saveDir = BUCKET . '/' . $saveDir;
			}
			
			return AwsUpload::upload($filePath, $saveDir.'/'.$fileName);
		}
		else if ($this->imageServer == self::SERVER_SDOSS){
		    require_once DIR_VENTOR . 'SdossImgService.php';
		    $imgService = new SdossImgService();
		    //$saveDir = str_replace(DIR_REMOTE_IMG, '/', $saveDir);//上传前路径
		    return $imgService->saveImg($filePath, $saveDir."/", $fileName, $fileType);
		}
		else if ($this->imageServer == self::SERVER_AZURE){
		    //require_once DIR_VENTOR . 'azure.php';
		    return false;
		}
		else{
			return false;
		}
	}

	/**
	 * 读取图片
	 *
	 * @param string $fileName
	 *        	文件存储路径
	 */
	public function readImg($fileName) {
		if ($this->imageServer == self::SERVER_OSS){
			$this->resetRLinkCache ();
			while ( ($link = $this->getRLink ()) !== null ) {
				$this->curl->setOpt ( CURLOPT_PORT, $link ['port'] );
				$url = 'http://' . $link ['host'] . ':' . $link ['port'] . '/' . $fileName;
				$res = $this->curl->get ( $url );
				if ($res !== false) {
					return $res;
				}
			}
			return false;
		}
		else if ($this->imageServer == self::SERVER_LOCAL){
			$fileName = ltrim($fileName,'/');
			return file_get_contents($this->imageHome.$fileName);
		}
		else if ($this->imageServer == self::SERVER_ALIYUN){
			return file_get_contents($this->getImgUrl().$fileName);
		}
		else if ($this->imageServer == self::SERVER_AWSOSS){
			$fileName = ltrim($fileName,'/');
			$url = $this->getImgUrl().$fileName;
			$curl_handle = curl_init();
			curl_setopt($curl_handle, CURLOPT_URL, $url);
			curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT,2);
			curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER,1);
			curl_setopt($curl_handle, CURLOPT_FAILONERROR,1);
			$file_content = curl_exec($curl_handle);
			curl_close($curl_handle);
			return $file_content;
		}
		else if ($this->imageServer == self::SERVER_SDOSS) {
		    $fileName = rawurldecode($fileName);
		    //$fileName = rawurldecode($fileName);
		    //$readFile = $this->model_tool_file_manage->getInfo($fileName);
		    require_once DIR_VENTOR . 'SdossImgService.php';
		    $imgService = new SdossImgService();
		    return $imgService->readImg($fileName);
		}
		else if ($this->imageServer == self::SERVER_AZURE) {
		    //require_once DIR_VENTOR . 'azure.php';
		    return false;
		}
		else{
			return false;
		}
	}

	public function listFiles($dir) {
		$this->resetRLinkCache ();
		$files = array ();
		while ( ($link = $this->getRLink ()) !== null ) {
			$this->curl->setOpt ( CURLOPT_PORT, $link ['port'] );
			$cursor = '0_0';
			$limit = '500';
			$reg = '';
			do {
				$url = 'http://' . $link ['host'] . ':' . $link ['port'] . '/dir/listfiles';
				$url .= '?dir=' . $dir . '&limit=' . $limit . '&cursor=' . $cursor . '&reg=' . $reg;
				$res = $this->curl->get ( $url );
				$finished = true;
				if ($res !== false) {
					$res = json_decode ( $res, true );
					$finished = $res ['finish'];
					$cursor = $res ['cursor'];
					$files = array_merge ( $files, $res ['files'] );
				}
			} while ( $finished );
			return $files;
		}
		return false;
	}

	/**
	 * 存储文件到远程服务器
	 *
	 * @param string $filePath
	 *        	文件绝对路径
	 * @param string $fileSaveUrl
	 *        	存储的远程目标地址
	 * @param string $fileName
	 *        	存储后的文件名
	 * @param string $fileType
	 *        	文件的mime类型
	 */
	private function realSave($filePath, $fileSaveUrl, $fileName, $fileType, $port) {
		if (version_compare(PHP_VERSION, '5.5.0', '>=')) {
			$cfile = curl_file_create ( $filePath, $fileType, $fileName );
			$params = array (
					'file' => $cfile
			);
		}
		else{
			$params = array (
					$fileName => '@' . $filePath
			);
		}
		$handle = curl_init ( $fileSaveUrl . $fileName );
		curl_setopt ( $handle, CURLOPT_PORT, $port );
		curl_setopt ( $handle, CURLOPT_POST, 1 );
		curl_setopt ( $handle, CURLOPT_POSTFIELDS, $params );
		curl_setopt ( $handle, CURLOPT_RETURNTRANSFER, true );
		$result = curl_exec ( $handle );
		$info = curl_getinfo ( $handle );
		curl_close ( $handle );
		if ($info ['http_code'] == 201) {
			return true;
		} else {
			return false;
		}
	}

	private function resetRLinkCache() {
		$this->rLinkCache = $this->imgR;
	}

	private function resetWLinkCache() {
		$this->wLinkCache = $this->imgW;
	}

	private function getRLink() {
		return $this->getRandomValue ( $this->rLinkCache );
	}

	private function getWLink() {
		return $this->getRandomValue ( $this->wLinkCache );
	}

	/**
	 * 从数组中随机获取一个元素, 并删除原有元素
	 *
	 * @param &array $arr        	
	 */
	private function getRandomValue(&$arr) {
		if (empty ( $arr )) {
			return null;
		}
		$cacheNum = count ( $arr );
		if ($cacheNum == 1) {
			return array_pop ( $arr );
		}
		$target = rand ( 1, $cacheNum );
		$num = 1;
		foreach ( $arr as $key => $val ) {
			if ($num == $target) {
				$res = $val;
				unset ( $arr [$key] );
				return $res;
			}
			$num ++;
		}
		return null;
	}
	
	public function getImgUrl() {
	    if (SYS_VERSION == 'TEST') {
	        return 'https://s3-ap-southeast-1.amazonaws.com/';
	    }
	    else if ($this->imageServer == self::SERVER_OSS){
	        $this->resetRLinkCache ();
	        $rLink = $this->getRandomValue ( $this->rLinkCache );
	        return 'http://' . $rLink ['host'] . ':' . $rLink ['port'] . '/';
	    }
	    else if ($this->imageServer == self::SERVER_LOCAL){
	        if(defined('HTTP_CATALOG')){
	            return HTTP_CATALOG.'image/';
	        }
	        else{
	            return HTTP_SERVER.'image/';
	        }
	    }
	    else if ($this->imageServer == self::SERVER_ALIYUN){
	        return 'http://sntestoss.oss-cn-hongkong.aliyuncs.com/';
	    }
	    else if ($this->imageServer == self::SERVER_AWSOSS){
	        $awsProtocol = (PROTOCOL == 'https') ? 'https' : 'http';
	        $imgurls = array(
				$awsProtocol.'://hk-image.hksuning.com/',
				$awsProtocol.'://img.hksuning.com/',
				$awsProtocol.'://img2.hksuning.com/'
			);
			return $imgurls[rand(0,2)];
	    }
	    else if ($this->imageServer == self::SERVER_SDOSS){
	        require_once DIR_VENTOR . 'SdossImgService.php';
		    $imgService = new SdossImgService();
		    return $imgService->getImgUrl();
	    }
	    else if ($this->imageServer == self::SERVER_AZURE) {
	        //require_once DIR_VENTOR . 'azure.php';
	        return false;
	    }
	    else{
	        return '';
	    }
	}

	private function unlinkRecursive($dir, $deleteRootToo) {
		if (! $dh = @opendir ( $dir )) {
			return;
		}
		while ( false !== ($obj = readdir ( $dh )) ) {
			if ($obj == '.' || $obj == '..') {
				continue;
			}
			
			if (! @unlink ( $dir . '/' . $obj )) {
				unlinkRecursive ( $dir . '/' . $obj, true );
			}
		}
		
		closedir ( $dh );
		
		if ($deleteRootToo) {
			@rmdir ( $dir );
		}
		
		return;
	}
}