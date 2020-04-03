<?php
class SdossImgService {
	private $host;
	private $port;
	private $protocol;
	private $sercretKey;
	private $account;
	private $bucket;
	private $keyId;
	CONST HTTP_TYPE_GET = 'GET';
	CONST HTTP_TYPE_POST = "POST";
	CONST HTTP_TYPE_PUT = "PUT";
	CONST HTTP_TYPE_DELETE = "DELETE";

	public function __construct() {
		global $global;
		$config = $global ['sdoss'];
		$this->host = $config ['host'];
		$this->port = $config ['port'];
		$this->protocol = $config['protocol'];
		$this->sercretKey = $config ['secret'];
		$this->account = $config['account_name'];
		$this->bucket = $config['bucket'];
		$this->keyId = $config['account_key_id'];
	}

	/**
	 * 获取sdoss请求主域名
	 *
	 * @return string
	 */
	public function getImgUrl() {
		return $this->protocol . "://" . $this->host . ":" . $this->port . '/'.$this->account.'/'.$this->bucket.'/';
	}

	public function readImg($fileName) {
		$url = $this->getImgUrl().ltrim($fileName, '/');
		$handle = curl_init ($url);
		curl_setopt ( $handle, CURLOPT_PORT, $this->port );
		curl_setopt ( $handle, CURLOPT_RETURNTRANSFER, true );
		$res = curl_exec ( $handle );
		if (curl_errno ( $handle ) != 0) {
			$res = false;
		}
		curl_close ( $handle );
		return $res;
	}

	public function saveImg($filePath, $saveDir, $fileName, $fileType) {
		if (version_compare(PHP_VERSION, '5.5.0', '>=')) {
			$cfile = curl_file_create ( $filePath, $fileType, $fileName );
			$params = array (
					'file' => $cfile
			);
		} else {
			$params = array (
					$fileName => '@' . $filePath
			);
		}
		//$savePath = trim ( $saveDir, '/' ) . '/' . $fileName;
		$resPath = trim ( $saveDir, '/' ) . '/' . $fileName;
		$url = $this->protocol . '://' . $this->host .'/'.$this->account.'/'.$this->bucket.'/' . $resPath;
		$handle = curl_init ( $url );
		$httpHead = $this->getHttpHeader ( self::HTTP_TYPE_POST, $resPath );
		curl_setopt ( $handle, CURLOPT_PORT, $this->port );
		curl_setopt ( $handle, CURLOPT_POST, true );
		curl_setopt ( $handle, CURLOPT_HTTPHEADER, $httpHead );
		curl_setopt ( $handle, CURLOPT_POSTFIELDS, $params );
		curl_setopt ( $handle, CURLOPT_RETURNTRANSFER, true );
		$res = curl_exec ( $handle );
		if (curl_errno ( $handle ) != 0) {
			$res = false;
		}
		curl_close ( $handle );
		$res = json_decode($res, true);
		return $res['Key'];
	}

	/**
	 * 获取请求sdoss所需的http头
	 *
	 * @param string $requestType
	 *        	POST|GET|DELETE|PUT
	 * @param string $data
	 *        	请求的资源目录
	 * @return array
	 */
	private function getHttpHeader($requestType, $savePath) {
		$VERB = $requestType;
		$ContentMD5 = "";
		$ContentType = "";
		$Date = gmdate ( 'D, d M Y H:i:s T' ); // Sun, 22 Nov 2015 08:16:38 GMT
		$CanonicalizedSDOSSHeaders = '';
		$CanonicalizedResource = '/'.$this->bucket.'/'.$savePath;
		$string_to_sign = $VERB . "\n" . $ContentMD5 . "\n" . $ContentType . "\n" . $Date . "\n" . $CanonicalizedSDOSSHeaders . $CanonicalizedResource;
		$Access_Key_secret = $this->sercretKey;
		$Signature = base64_encode ( hash_hmac ( "SHA1", $string_to_sign, $Access_Key_secret, true ) );
		return array (
				"Authorization:SDOSS ".$this->keyId.":{$Signature}",
				"Date:{$Date}" 
		);
	}
}