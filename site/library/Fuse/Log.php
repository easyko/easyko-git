<?php

/**
 * Fuse Log
 *
 */

class Fuse_Log {
    protected static $_instance = null;
	private $handle;

    /**
     * Get instance
     *
     * @return Fuse_Log  	$this
     */
    public static function getInstance($filename)
    {
        if (null === self::$_instance) {
            self::$_instance = new self($filename);
        }
        return self::$_instance;
    }

	public function __construct($filename) {
		$this->handle = fopen(DIR_LOGS . $filename, 'a');
	}

	public function write($message) {
		fwrite($this->handle, date('Y-m-d H:i:s') . ' - ' . print_r($message, true) . "\n");
	}

	public function __destruct() {
		fclose($this->handle);
	}
}