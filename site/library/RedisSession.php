<?php
/**
 * Redis之SESSION存储机制
 * 		将session数据从服务器上存储到redis中
 * 
 * @auth jerry.cao(14033184)
 * @date 2016-02-19
 */

//if (SYS_VERSION == 'SIT') return;

class RedisSessionHandler implements SessionHandlerInterface
{
    // 初始化
	public $sessionIdPrefix = 'SESSION';       // sessionId前缀
	public $sessionId       = '';              // sessionId
	public $cookieName      = 'sso_session';   // cookie的名字

    private $redis          = array();         // redis连接(读、写)
	private $driver         = array();         // 读写配置
	private $act            = array('w', 'r'); // 读写操作
	private $lifeTime       = 3600;            // 过期时间
	
    public function __construct($configW = array(), $configR = array())
    {
		foreach ($this->act as $value) {
			$dsn = $value == "w" ? $configW : $configR;
			$var = $this->switchDsn($dsn);
            $this->driver[$value]['host'] = $var['host'];
            $this->driver[$value]['port'] = $var['port'];
        }  
    }

    public function open($savePath, $sessionName)
    {
        // 获取session-lifeTime
        $this->lifeTime = ini_get('session.gc_maxlifetime') ? ini_get('session.gc_maxlifetime') : $this->lifeTime;

        // 获取cookieName
        // $this->cookieName = ini_get("session.name") ? ini_get("session.name") : $this->cookieName;

        // 若是cookie已经存在则以它为session的id
        if (isset($_COOKIE[$this->cookieName])) {
             $this->sessionId = $_COOKIE[$this->cookieName];
        } else {
            // 在IE6下的iframe无法获取到cookie,于是使用了get方式传递了cookie的名字
            if (isset($_GET[$this->cookieName])) {
                $this->sessionId = $_GET[$this->cookieName];
            } else {
                $this->sessionId = $this->sessionIdPrefix . '_' . $this->getSessionId();
            }   
        }
        
        // 存储在COOKIE中
        setcookie($this->cookieName, $this->sessionId, time()+($this->lifeTime), '/');

        // 连接redis，读写分离
        $this->redis['w'] = new Redis();
        $this->redis['w']->connect($this->driver['w']['host'], $this->driver['w']['port']);
        if(SYS_VERSION == 'AZURE') {
            $this->redis['w']->auth(AZURE_REDIS_PASSWORD);
        }
        $resultW = $this->redis['w']->info();
        if ($resultW['role'] != 'master') {
        	unset($this->redis['w']);
        	$this->redis['w'] = new Redis();
        	$this->redis['w']->connect($this->driver['r']['host'], $this->driver['r']['port']);
        	if(SYS_VERSION == 'AZURE') {
        	    $this->redis['w']->auth(AZURE_REDIS_PASSWORD);
        	}
        }

        $this->redis['r'] = new Redis();
        $this->redis['r']->connect($this->driver['r']['host'], $this->driver['r']['port']);
        if(SYS_VERSION == 'AZURE') {
            $this->redis['r']->auth(AZURE_REDIS_PASSWORD);
        }
        $resultR = $this->redis['r']->info();
        if ($resultR['role'] != 'slave') {
        	unset($this->redis['r']);
        	$this->redis['r'] = new Redis();
        	$this->redis['r']->connect($this->driver['w']['host'], $this->driver['w']['port']);
        	if(SYS_VERSION == 'AZURE') {
        	    $this->redis['r']->auth(AZURE_REDIS_PASSWORD);
        	}
        }
        
        // 连接服务
        if (!$this->redis['w'] || !$this->redis['r']) {
            return false;
        }

        return true; 
    }

    public function close()
    {
        if ($this->redis['w']) $this->redis['w']->close();
        if ($this->redis['r']) $this->redis['r']->close();
        return true;
    }
    
    public function read($key='')
    {
        if (empty($key)) {
            return $this->redis['r']->hGetAll($this->sessionId); // 取所有值
        } else {
            return $this->redis['r']->hGet($this->sessionId, $key);
        }
    }
    
    public function write($key, $value)
    {
        $expireAt = time() + $this->lifeTime;
        $result = $this->redis['w']->hSet($this->sessionId, $key, $value);
        $this->redis['w']->expireAt($this->sessionId, $expireAt);

        return $result;
    }
    
    public function destroy($key)
    {
        return $this->redis['w']->hDel($this->sessionId, $key); 
    }
    
    public function gc($maxlifetime)
    {
        return true;
    }
     
    /**
     * 选择DSN
     * 
     * @param   array   $dsn
     * @return  array
     */
    private function switchDsn($dsn)
    {
		$hh = hash('md5', time());
        $c = count($dsn);
        $h = 0;
        if ($c>1) {
            $h = bin2hex(substr($hh, -2))%$c;
        }

        return $dsn[$h];
    }

    /**
     * 生成sessionId
     * 
     * @param   void
     * @return  void
     */
    private function getSessionId()
    {
        $uid  = uniqid("", true);
        $data = microtime();
        !empty($_SERVER['REQUEST_TIME']) && $data .= $_SERVER['REQUEST_TIME'];
        !empty($_SERVER['HTTP_USER_AGENT']) && $data .= $_SERVER['HTTP_USER_AGENT'];
        !empty($_SERVER['SERVER_ADDR']) && $data .= $_SERVER['SERVER_ADDR'];
        !empty($_SERVER['SERVER_PORT']) && $data .= $_SERVER['SERVER_PORT'];
        !empty($_SERVER['REMOTE_ADDR']) && $data .= $_SERVER['REMOTE_ADDR'];
        !empty($_SERVER['REMOTE_PORT']) && $data .= $_SERVER['REMOTE_PORT'];
        $hash = strtoupper(hash('ripemd128', $uid . md5($data)));
        
        return substr($hash, 0, 32);
    }
}

// 实例化以下实例便可正常使用SESSION了
global $global;
$redis = new RedisSessionHandler($global['redis_session_w'], $global['redis_session_r']);
session_set_save_handler($redis, true);
session_start();
