<?php
/**
 * Application class autoload .
 * 
 * @category   Fuse
 * @package    Fuse
 * @copyright  Copyright (c) 2010-now 75.cn (http://cms.e75.cn)
 * @author Gary wang(qq:465474550,msn:wangbaogang123@hotmail.com)
 */
 
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

class Fuse_Application
{
	/**
     * @var Fuse_Autoloader Singleton instance
     */
    protected static $_instance;

	/**
     * @var String document root
     */
    protected $_documentroot = "";

	/**
     * @var array Supported namespaces 'Fuse' by default.
     */
    protected $_namespaces = array(
        'Fuse'  => "library"
    );

	/**
     * @var String document root
     */
    protected $_controller_root = "class";

	/**
	 * @var String package
	 */
	private $_package  = "";
	
	/**
	 * @var String script
	 */
	private $_script  = "";
	
	/**
	 * @var String task
	 */
	private $_task    = "";

	/**
     * Retrieve singleton instance
     *
     * @return Zend_Loader_Autoloader
     */
    public static function getInstance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

	/**
     * Register a namespace to autoload
     *
     * @param  ..
     * @return Fuse_Autoloader
     */
    public function registerNamespace()
    {
		$namespaces = func_get_args();
		foreach($namespaces as $ns){
			if (!is_array($ns)) {
				throw new Exception('Invalid namespace provided');
			}
			if (!isset($this->_namespaces[key($ns)])) {
				$this->_namespaces[key($ns)] = current($ns);
			}
		}
        return $this;
    }

	/**
     * Get a list of registered autoload namespaces
     *
     * @return array
     */
    public function getRegisteredNamespaces()
    {
        return $this->_namespaces;
    }

	/**
     * Set the document root
     * 
     * @param  string $documentroot
     * @return Fuse_Autoloader
     */
    public function setDocumentRoot($documentroot)
    {
        $this->_documentroot = $documentroot;
        return $this;
    }

	/**
     * Autoload a class
     *
     * @param  string $class
     * @return bool
     */
    public static function autoload($class)
    {
		$self = self::getInstance();
        $namespaces = $self->getRegisteredNamespaces();
		$class_path = explode("_",$class);
		if(isset($namespaces[$class_path[0]])){

			$path = realpath($self->_documentroot.DIRECTORY_SEPARATOR.$namespaces[$class_path[0]]).DIRECTORY_SEPARATOR.implode(DIRECTORY_SEPARATOR,$class_path).".php";
			if (!file_exists($path))
			{
				return false;
			}
			require_once($path);
			return true;
		}
        return false;
    }

	/**
     * Set router
     * 
     * @param  Array $routerConfig
     * @return Fuse_Autoloader
     */
	public function setRouter($routerConfig = array())
	{
		if(isset($routerConfig["package"])){
			$this->_package = $routerConfig['package'];
		}

		if(isset($routerConfig["script"])){
			$this->_script = $routerConfig['script'];
		}

		if(isset($routerConfig["task"])){
			$this->_task = $routerConfig['task'];
		}
		
		//if(empty($this->_task)){
			$this->setDefaultTask();
		//}
		return $this;
	}

	
	/**
     * Set the controller root
     * 
     * @param  string $controller_root
     * @return Fuse_Autoloader
     */
    public function setControllerRoot($controller_root)
    {
        $this->_controller_root = $controller_root;
        return $this;
    }
	
	
	/**
     * Constructor
     *
     * Registers instance with spl_autoload stack
     *
     * @return void
     */
    protected function __construct()
    {
        spl_autoload_register(array(__CLASS__, 'autoload'));
    }

	/**
     * Set default router
     * 
     * @return void
     */
	protected function setDefaultTask()
	{
		if(isset($_SERVER["PHP_SELF"])){
			$uri = parse_url($_SERVER["PHP_SELF"]);
			if(isset($uri["path"])){
				$paths = explode("/",$uri["path"]);
				$path  = $paths[count($paths)-1];
				$pos   = strpos($path,".php");
				if(empty($this->_task)){
					$this->_task = substr($path,0,$pos);
				}
			}
			//if(empty($this->_task)){
				if(isset($_REQUEST["task"])){
					$this->_task = $_REQUEST["task"];
				}
			//}
		}
	}

	/**
	 * run
	 * @return	void
	 */
	public function run(){
		$class = ucfirst($this->_script).'Controller';
		if (!class_exists($class))
		{
			$path = $this->_controller_root;

			if(!empty($this->_package)){
				$path .= DIRECTORY_SEPARATOR.$this->_package;
			}

			$path .= DIRECTORY_SEPARATOR.$class.".php";
	
			if (!file_exists($path))
			{
				throw new Exception('File not exists!'.$path);
				exit();
			}
			require_once($path);
		}
		$controller	= new $class();
		$controller->execute($this->_task);
	}
}
?>