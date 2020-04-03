<?php
require_once 'Smarty/SmartyBC.class.php';

class Fuse_View_Smarty
{
    /**
     * Smarty object
     * @var Smarty
     */
    protected $_smarty;

    /**
     * Constructor
     *
     * @param string $tmplPath
     * @param array $extraParams
     * @return void
     */
    public function __construct($tmplPath = null, $extraParams = array())
    {
        $this->_smarty = new SmartyBC();
		
		if(null == $tmplPath){
			 $this->setScriptPath(FUSE_VIEW_PATH);
		}else{
			 $this->setScriptPath($tmplPath);
		}

        foreach ($extraParams as $key => $value) {
            $this->_smarty->$key = $value;
        }
        
        if (!array_key_exists('use_sub_dirs', $extraParams )){
        	 $this->_smarty->use_sub_dirs = false;
        }
    }

    /**
     * Return the template engine object
     *
     * @return Smarty
     */
    public function getEngine()
    {
        return $this->_smarty;
    }

    /**
     * Set the path to the templates
     *
     * @param string $path The directory to set as the path.
     * @return void
     */
    public function setScriptPath($path)
    {
        if (is_readable($path)) {
            $this->_smarty->template_dir = $path.DIRECTORY_SEPARATOR."view";
            $this->_smarty->compile_dir  = $path.DIRECTORY_SEPARATOR."compile";
            $this->_smarty->cache_dir    = $path.DIRECTORY_SEPARATOR."cache";
            $this->_smarty->use_sub_dirs = false;      
			if(defined("ROOTURL")){
				 $this->rooturl = ROOTURL;
			}else{
				 $this->rooturl = HOMEURL;
			}
			$this->homeurl = HOMEURL;
            $this->homedir = HOMEDIR;
			
//			$this->_smarty->allow_php_tag = true;
			
            $policy = new Smarty_Security($this->_smarty);
			$secure_dirs = array(); 
            $secure_dirs[] = HOMEDIR.DIRECTORY_SEPARATOR."site".DIRECTORY_SEPARATOR."template".DIRECTORY_SEPARATOR."view";
            $secure_dirs[] = $path.DIRECTORY_SEPARATOR."view";
			$secure_dirs   = array_unique($secure_dirs);
            $policy->secure_dir = $secure_dirs;
			$policy->trusted_dir = array($secure_dirs[0].DIRECTORY_SEPARATOR."include");
			$this->_smarty->enableSecurity($policy);
            return;
        }

        throw new Exception('Invalid path provided');
    }

    /**
     * Retrieve the current template directory
     *
     * @return string
     */
    public function getScriptPaths()
    {
        return array($this->_smarty->template_dir);
    }

    /**
     * Alias for setScriptPath
     *
     * @param string $path
     * @param string $prefix Unused
     * @return void
     */
    public function setBasePath($path, $prefix = 'Fuse_View')
    {
        return $this->setScriptPath($path);
    }

    /**
     * Alias for setScriptPath
     *
     * @param string $path
     * @param string $prefix Unused
     * @return void
     */
    public function addBasePath($path, $prefix = 'Fuse_View')
    {
        return $this->setScriptPath($path);
    }

    /**
     * Assign a variable to the template
     *
     * @param string $key The variable name.
     * @param mixed $val The variable value.
     * @return void
     */
    public function __set($key, $val)
    {
        $this->_smarty->assign($key, $val);
    }

    /**
     * Retrieve an assigned variable
     *
     * @param string $key The variable name.
     * @return mixed The variable value.
     */
    public function __get($key)
    {
        return $this->_smarty->get_template_vars($key);
    }

    /**
     * Allows testing with empty() and isset() to work
     *
     * @param string $key
     * @return boolean
     */
    public function __isset($key)
    {
         return (null !== $this->_smarty->get_template_vars($key));
    }

    /**
     * Allows unset() on object properties to work
     *
     * @param string $key
     * @return void
     */
    public function __unset($key)
    {
        $this->_smarty->clear_assign($key);
    }

    /**
     * Assign variables to the template
     *
     * Allows setting a specific key to the specified value, OR passing an array
     * of key => value pairs to set en masse.
     *
     * @see __set()
     * @param string|array $spec The assignment strategy to use (key or array of key
     * => value pairs)
     * @param mixed $value (Optional) If assigning a named variable, use this
     * as the value.
     * @return void
     */
    public function assign($spec, $value = null)
    {
        if (is_array($spec)) {
            $this->_smarty->assign($spec);
            return;
        }

        $this->_smarty->assign($spec, $value);
    }

    /**
     * Clear all assigned variables
     *
     * Clears all variables assigned to Zend_View either via {@link assign()} or
     * property overloading ({@link __get()}/{@link __set()}).
     *
     * @return void
     */
    public function clearVars()
    {
        $this->_smarty->clear_all_assign();
    }

    /**
     * Processes a template and returns the output.
     *
     * @param string $name The template to process.
     * @return string The output.
     */
    public function render($name)
    {
        return $this->_smarty->fetch($name);
    }
    
     /**
     * Processes a template and display the output.
     *
     * @param string $name The template to process.
     * @return void.
     */
    public function display($name)
    {
    	$this->_smarty->display($name);
    }
	
	/**
	* 
	* @return void.
	*/
	public function cacheDisplay($name,$lefttime=0)
	{
		if(empty($lefttime) && defined("CACHE_LEFTTIME")){
			$lefttime = CACHE_LEFTTIME;
		}

		$this->_smarty->caching        = true;
		$this->_smarty->cache_lifetime = $lefttime;

		if($this->_smarty->isCached($name)) {
			$this->display($name);
			exit();
		}

	}
}
?>
