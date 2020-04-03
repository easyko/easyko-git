<?php
/**
 * Controller class
 * Base class for a Imag Controller
 *
 * Controller (controllers are where you put all the actual code) Provides basic
 * functionality, such as rendering views (aka displaying templates).
 *
 * @category   Fuse
 * @package    Fuse
 * @copyright  Copyright (c) 2010-now 75.cn (http://cms.e75.cn)
 * @author Gary wang(qq:465474550,msn:wangbaogang123@hotmail.com)
 *
 */
class Fuse_Controller
{
	/**
	 * The name of the controller
	 *
	 * @var		array
	 * @access	protected
	 */
	protected $_name = null;

	/**
	 * Array of class methods
	 *
	 * @var	array
	 * @access	protected
	 */
	protected $_methods 	= null;

	/**
	 * Array of class methods to call for a given task.
	 *
	 * @var	array
	 * @access	protected
	 */
	protected $_taskMap 	= null;

	/**
	 * Current or most recent task to be performed.
	 *
	 * @var	string
	 * @access	protected
	 */
	protected $_task 		= null;

	/**
	 * The mapped task that was performed.
	 *
	 * @var	string
	 * @access	protected
	 */
	protected $_doTask 	= null;

	
	/**
	* html Template
	* @var View
	* @access	protected
	*/
	protected $_view = null;

	/**
	 * Constructor.
	 *
	 * @access	protected
	 * @param	array An optional associative array of configuration settings.
	 * Recognized key values include 'name', 'default_task', 'model_path', and
	 * 'view_path' (this list is not meant to be comprehensive).
	 */
	public function __construct( $config = array() )
	{
		//Initialize private variables
		$this->_taskMap		= array();
		$this->_methods		= array();

		// Get the methods only for the final controller class
		$thisMethods	= get_class_methods( get_class( $this ) );
		$baseMethods	= get_class_methods( 'Fuse_Controller' );
		$methods		= array_diff( $thisMethods, $baseMethods );

		// Add default display method
		$methods[] = 'display';

		// Iterate through methods and map tasks

		foreach ( $methods as $method )
		{
			if ( substr( $method, 0, 1 ) != '_' ) {
				$this->_methods[] = strtolower( $method );
				// auto register public methods as tasks
				//$this->_taskMap[strtolower( $method )] = $method; //off by gary wang 2010.11.1 去掉TASK默认加载
			}
		}

		// If the default task is set, register it as such
		if ( array_key_exists( 'default_task', $config ) ) {
			$this->registerDefaultTask( $config['default_task'] );
		} else {
			$this->registerDefaultTask( 'display' );
		}
	}

	/**
	 * Execute a task by triggering a method in the derived class.
	 *
	 * @access	public
	 * @param	string The task to perform. If no matching task is found, the
	 * '__default' task is executed, if defined.
	 * @return	mixed|false The value returned by the called method, false in
	 * error case.
	 * 
	 */
	public function execute( $task )
	{
		$this->_task = $task;

		$task = strtolower( $task );
		if (isset( $this->_taskMap[$task] )) {
			$doTask = $this->_taskMap[$task];
		} elseif (isset( $this->_taskMap['__default'] )) {
			$doTask = $this->_taskMap['__default'];
		} else {
			exit("404,Task [{$task}] not found");
		}

		$this->_doTask = $doTask;
		
		$retval = $this->$doTask();
		return $retval;

	}

	/**
	 * Typical view method for MVC based architecture
	 *
	 * This function is provide as a default implementation, in most cases
	 * you will need to override it in your own controllers.
	 *
	 * @access	public
	 * @param	string	$cachable	If true, the view output will be cached
	 * 
	 */
	public function display()
	{
		//do nothing
	}

	/**
	 * Gets the available tasks in the controller.
	 * @access	public
	 * @return	array Array[i] of task names.
	 * 
	 */
	public function getTasks()
	{
		return $this->_methods;
	}

	/**
	 * Get the last task that is or was to be performed.
	 *
	 * @access	public
	 * @return	 string The task that was or is being performed.
	 * 
	 */
	public function getTask()
	{
		return $this->_task;
	}

	/**
	 * Method to get the controller name
	 *
	 * The dispatcher name by default parsed using the classname, or it can be set
	 * by passing a $config['name'] in the class constructor
	 *
	 * @access	public
	 * @return	string The name of the dispatcher
	 * 
	 */
	public function getName()
	{
		$name = $this->_name;

		if (empty( $name ))
		{
			$r = null;
			if ( !preg_match( '/(.*)Controller/i', get_class( $this ), $r ) ) {
				throw(new Exception("500,Controller::getName() : Cannot get or parse class name."));
				exit();
			}
			$name = strtolower( $r[1] );
		}

		return $name;
	}

	/**
	 * Register (map) a task to a method in the class.
	 *
	 * @access	public
	 * @param	string	The task.
	 * @param	string	The name of the method in the derived class to perform
	 *                  for this task.
	 * @return	void
	 * 
	 */
	public function registerTask( $task, $method )
	{
		if ( in_array( strtolower( $method ), $this->_methods ) ) {
			$this->_taskMap[strtolower( $task )] = $method;
		}
	}

	/**
	 * Register the default task to perform if a mapping is not found.
	 *
	 * @access	public
	 * @param	string The name of the method in the derived class to perform if
	 * a named task is not found.
	 * @return	void
	 * 
	 */
	public function registerDefaultTask( $method )
	{
		$this->registerTask( '__default', $method );
	}

	/**
	 * Method to load and return a model object.
	 *
	 * @access	private
	 * @param	string  The name of the model.
	 * @param	string	Optional model prefix.
	 * @param	array	Configuration array for the model. Optional.
	 * @return	mixed	Model object on success; otherwise null
	 * failure.
	 * 
	 */
	public function createModel($class, $path = '', $config = array())
	{
		if (class_exists($class)){
			return new $class($config);
		}
		$class_path = explode("_",$class);
		if($class_path[0]=="Model"){
			$path = $path.DIRECTORY_SEPARATOR.implode(DIRECTORY_SEPARATOR,$class_path).".php";
			if (file_exists($path))
			{
				require_once($path);
				return new $class($config);
			}
		}
		return null;
	}

	public function createView($view='Fuse_View_Smarty',$tmplPath = null, $extraParams = array())
	{
		$result = new $view($tmplPath,$extraParams);
		return $result;

	}

}