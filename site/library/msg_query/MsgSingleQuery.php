<?php

/** 
 * @author 14020102
 * 
 */
class MsgSingleQuery {
	private static $instance = null;
	private $redis;
	private $curQuery;
	const QUERY_PRD_IMPORT = 'hkb2c.q.prdimport';
	const QUERY_PRD_IMPORT_ORDER = 'hkb2c.q.ordimport';
	const QUERY_SECKILL_ORDER = 'seckill.q.order';

	/**
	 * 将当前query切换到指定query
	 *
	 * @param string $query        	
	 */
	public function selectQuery($query) {
		$this->curQuery = $query;
	}

	/**
	 * 获取一个query实例
	 * @param redis $redis
	 * @param string $query
	 * @return MsgSingleQuery
	 */
	public static function getInstance($redis, $query) {
		if (null == self::$instance) {
			self::$instance = new MsgSingleQuery ( $redis, $query );
		}
		if (self::$instance->curQuery != $query) {
			self::$instance->selectQuery ( $query );
		}
		return self::$instance;
	}

	/**
	 * 添加消息到消息缓冲区
	 *
	 * @param int $score
	 *        	优先级(1-5)
	 * @param string $msg
	 *        	消息
	 */
	public function add($msg) {
		$cacheData = array (
			'msg' => $msg 
		);
		// 被添加到集合中的新元素的数量，不包括被忽略的元素，故重复添加返回false
		return $this->redis->rPush ( $this->curQuery, serialize ( $cacheData ) );
	}

	/**
	 * 从队列阻塞式出栈一个最高优先级消息
	 *
	 * @return string msg
	 */
	public function bPop($outTime = 0) {
		$msg = $this->redis->blPop ( $this->curQuery, $outTime );
		$msg = unserialize($msg[1]);
		return $msg['msg'];
	}
	
	public function getCurQuery(){
		return $this->curQuery;
	}

	private function __construct($redis, $query) {
		$this->redis = $redis;
// 		$this->redis->connect ();
// 		self::$MAX_SCORE = self::MIN_SCORE + self::SCORE_NUM - 1;
		$this->selectQuery ( $query );
	}

	public function __destruct() {
        if ($this->redis) 	$this->redis->close ();
	}
}

?>