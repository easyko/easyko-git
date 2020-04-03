 <?php
 class Fuse_SocketClient{
	public static function send($cmd,$ip="",$port=0)
	{
		if(empty($ip)){
			$ip = Config_Proxy::$ip;
		}
		if(empty($port)){
			$port = Config_Proxy::$port;
		}
		$socket=socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
		socket_connect($socket, $ip, $port);
		socket_write($socket,$cmd,strlen($cmd));
		socket_close($socket);
	}

	public static function sendto($cmd,$param=array(),$ip="",$port=0)
	{
		$cmd .= " ".urlencode(json_encode($param));
		var_dump($cmd);
		self::send($cmd,$ip,$port);

	}
 }
 ?>