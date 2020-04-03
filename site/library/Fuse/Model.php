<?php

/**
 * Base class for a Model
 *
 * @package		Imag.Framework
 * @subpackage	Component
 * @modify by gary wang (wangbaogang123@hotmail.com)
 *
 */
class Fuse_Model
{
	/**
	 * Database Connector
	 *
	 * @var object
	 * @access	protected
	 */
	protected $db;


	/**
	 * Database charset
	 *
	 * @var string
	 * @access	protected
	 */
	protected $_charset = "utf8";


	/**
     * @var Fuse_Autoloader Singleton instance
     */
    protected static $_instance;

	/**
	 * Constructor
	 *
	 *	 
	 */
	public function __construct($config = array())
	{
		if (array_key_exists('charset', $config)){
			$this->_charset = $config["charset"];
		}
		if (array_key_exists('db', $config)){
			$this->db = $config['db'];
		} else {
			$this->db = $this->getDefaultDB();
		}
	}

	/**
	 * Method to get Database
	 *
	 *
	 * @access	public
	 * @return	string The name of the model
	 */
	public function getDefaultDB($class="Fuse_Db_Pdo_Mysql")
	{
		$options = Config_Db::toArray($this->_charset);

		if(isset($options["driver"])){
			$class = "Fuse_Db_Pdo_".ucfirst($options["driver"]);
		}
		
		$databse = new $class($options);
		return $databse;
	}

	/**
	 * Method to get Database is connected
	 *
	 *
	 * @access	public
	 * @return	boolean
	 */

	public function isConnected()
	{
		if($this->db==null){
			return false;
		}
		return $this->db->isConnected();
	}

	/**
	 * Returns a reference to the a Model object, always creating it
	 *
	 * @param	array	Configuration array for model. Optional.
	 * @return	mixed	A model object, or false on failure
	*/
	public static function getInstance( $config = array() )
	{
		if (null === self::$_instance) {
            self::$_instance = new self($config);
        }
        return self::$_instance;
	}
	
	/**
	 * Get db error
	 * @access	public
	 * @return	array	db error
	 */
	public function getError(){
		return $this->db->getConnection()->errorInfo();
	}
	
	/**
	 * Get dbo
	 * @access	public
	 * @return	array	dbo
	 */
	public function getDb(){
		return $this->db;
	}
	
	/**
	 * store
	 * @param	object	object instance
	 * @param	string	table name.
	 * @return	mixed	insertid, or false on failure
	 */
	public function store($table, array $bind){
		if(!$this->db->insert($table,$bind)){
			return false;
		}else{
			return $this->db->lastInsertId();
		}
	}
	
	 /**
	 * update
	 * @param	object	object instance
	 * @param	string	update key name.
	 * @param	string	table name.
	 * @return	boolean	
	 */
	public function update($table, array $bind, $where = ''){
		return $this->db->update($table,$bind,$where);
	}
	
	 /**
	 * getRowset
	 * @param	string	sql.
	 * @return	array	row set
	 */
	public function getRowSet($sql, $bind = array()){
		return $this->db->fetchAll($sql, $bind);
	}
	
	/**
	 * getRow
	 * @param	string	sql.
	 * @return	array	row
	 */
	public function getRow($sql, $bind = array()){
		return $this->db->fetchRow($sql,$bind);
	}

	/**
	 * query
	 * @param	string	sql.
	 * @return  mixed	insertid, or false on failure
	 */
	public function query($sql){
		return $this->db->query($sql);
	}
}
