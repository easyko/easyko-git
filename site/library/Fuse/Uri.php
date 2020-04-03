<?php
/**
 * URI Class
 *
 * This class serves two purposes.  First to parse a URI and provide a common interface
 * for the Imag Framework to access and manipulate a URI.  Second to attain the URI of
 * the current executing script from the server regardless of server.
 *
 * @package		Imag.Framework
 * @subpackage	Environment
 * @modify by   gary wang (wangbaogang123@hotmail.com)
 */
class Fuse_Uri
{
	/**
	 * Original URI
	 *
	 * @var		string
	 */
	private $_uri = null;

	/**
	 * Protocol
	 *
	 * @var		string
	 */
	private $_scheme = null;

	/**
	 * Host
	 *
	 * @var		string
	 */
	private $_host = null;

	/**
	 * Port
	 *
	 * @var		integer
	 */
	private $_port = null;

	/**
	 * Username
	 *
	 * @var		string
	 */
	private $_user = null;

	/**
	 * Password
	 *
	 * @var		string
	 */
	private $_pass = null;

	/**
	 * Path
	 *
	 * @var		string
	 */
	private $_path = null;

	/**
	 * Query
	 *
	 * @var		string
	 */
	private $_query = null;

	/**
	 * Anchor
	 *
	 * @var		string
	 */
	private $_fragment = null;

	/**
	 * Query variable hash
	 *
	 * @var		array
	 */
	private $_vars = array ();

	/**
	 * Constructor.
	 * You can pass a URI string to the constructor to initialize a specific URI.
	 *
	 * @param	string $uri The optional URI string
	 */
	public function __construct($uri = null)
	{
		if ($uri !== null) {
			$this->parse($uri);
		}
	}

	/**
	 * Returns a reference to a global URI object, only creating it
	 * if it doesn't already exist.
	 *
	 * This method must be invoked as:
	 * 		<pre>  $uri =& Fuse_Uri::getInstance([$uri]);</pre>
	 *
	 * @static
	 * @param	string $uri The URI to parse.  [optional: if null uses script URI]
	 * @return	URI  The URI object.
	 */
	public static function getInstance($uri = 'SERVER')
	{
		static $instances = array();

		if (!isset ($instances[$uri]))
		{
			// Are we obtaining the URI from the server?
			if ($uri == 'SERVER')
			{
				// Determine if the request was over SSL (HTTPS)
				if (isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) != 'off')) {
					$https = 's://';
				} else {
					$https = '://';
				}

				/*
				 * Since we are assigning the URI from the server variables, we first need
				 * to determine if we are running on apache or IIS.  If PHP_SELF and REQUEST_URI
				 * are present, we will assume we are running on apache.
				 */
				if (!empty ($_SERVER['PHP_SELF']) && !empty ($_SERVER['REQUEST_URI'])) {

					/*
					 * To build the entire URI we need to prepend the protocol, and the http host
					 * to the URI string.
					 */
					$theURI = 'http' . $https . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

				/*
				 * Since we do not have REQUEST_URI to work with, we will assume we are
				 * running on IIS and will therefore need to work some magic with the SCRIPT_NAME and
				 * QUERY_STRING environment variables.
				 */
				}
				 else
				 {
					// IIS uses the SCRIPT_NAME variable instead of a REQUEST_URI variable... thanks, MS
					$theURI = 'http' . $https . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'];

					// If the query string exists append it to the URI string
					if (isset($_SERVER['QUERY_STRING']) && !empty($_SERVER['QUERY_STRING'])) {
						$theURI .= '?' . $_SERVER['QUERY_STRING'];
					}
				}

				// Now we need to clean what we got since we can't trust the server var
				$theURI = urldecode($theURI);
				$theURI = str_replace('"', '&quot;',$theURI);
				$theURI = str_replace('<', '&lt;',$theURI);
				$theURI = str_replace('>', '&gt;',$theURI);
				$theURI = preg_replace('/eval\((.*)\)/', '', $theURI);
				$theURI = preg_replace('/[\\\"\\\'][\\s]*javascript:(.*)[\\\"\\\']/', '""', $theURI);
			}
			else
			{
				// We were given a URI
				$theURI = $uri;
			}

			// Create the new URI instance
			$instances[$uri] = new Fuse_Uri($theURI);
		}
		return $instances[$uri];
	}

	/**
	 * Returns the base URI for the request.
	 *
	 * @access	public
	 * @static
	 * @param	boolean $pathonly If false, prepend the scheme, host and port information. Default is false.
	 * @return	string	The base URI string
	 */
	public static function base($pathonly = false)
	{
		static $base;

		// Get the base request path
		if (!isset($base))
		{
		
			$uri	         = self::getInstance();
			$base['prefix'] = $uri->toString( array('scheme', 'host', 'port'));

			if (strpos(php_sapi_name(), 'cgi') !== false && !empty($_SERVER['REQUEST_URI'])) {
				//Apache CGI
				$base['path'] =  rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
			} else {
				//Others
				$base['path'] =  rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
			}

		}

		return $pathonly === false ? $base['prefix'].$base['path'].'/' : $base['path'];
	}

	/**
	 * Returns the root URI for the request.
	 *
	 * @access	public
	 * @static
	 * @param	boolean $pathonly If false, prepend the scheme, host and port information. Default is false.
	 * @return	string	The root URI string
	 */
	function root($pathonly = false, $path = null)
	{
		static $root;

		// Get the scheme
		if(!isset($root))
		{
			$uri	        = self::getInstance(self::base());
			$root['prefix'] = $uri->toString( array('scheme', 'host', 'port') );
			$root['path']   = rtrim($uri->toString( array('path') ), '/\\');
		}

		// Get the scheme
		if(isset($path)) {
			$root['path']    = $path;
		}

		return $pathonly === false ? $root['prefix'].$root['path'].'/' : $root['path'];
	}

	/**
	 * Returns the URL for the request, minus the query
	 *
	 * @access	public
	 * @return	string
	 */
	public function current()
	{
		static $current;

		// Get the current URL
		if (!isset($current))
		{
			$uri	 =  self::getInstance();
			$current = $uri->toString( array('scheme', 'host', 'port', 'path'));
		}

		return $current;
	}

	/**
	 * Parse a given URI and populate the class fields
	 *
	 * @access	public
	 * @param	string $uri The URI string to parse
	 * @return	boolean True on success
	 */
	public function parse($uri)
	{
		//Initialize variables
		$retval = false;

		// Set the original URI to fall back on
		$this->_uri = $uri;

		/*
		 * Parse the URI and populate the object fields.  If URI is parsed properly,
		 * set method return value to true.
		 */
		if ($_parts = $this->_parseURL($uri)) {
			$retval = true;
		}

		//We need to replace &amp; with & for parse_str to work right...
		if(isset ($_parts['query']) && strpos($_parts['query'], '&amp;')) {
			$_parts['query'] = str_replace('&amp;', '&', $_parts['query']);
		}

		$this->_scheme = isset ($_parts['scheme']) ? $_parts['scheme'] : null;
		$this->_user = isset ($_parts['user']) ? $_parts['user'] : null;
		$this->_pass = isset ($_parts['pass']) ? $_parts['pass'] : null;
		$this->_host = isset ($_parts['host']) ? $_parts['host'] : null;
		$this->_port = isset ($_parts['port']) ? $_parts['port'] : null;
		$this->_path = isset ($_parts['path']) ? $_parts['path'] : null;
		$this->_query = isset ($_parts['query'])? $_parts['query'] : null;
		$this->_fragment = isset ($_parts['fragment']) ? $_parts['fragment'] : null;

		//parse the query

		if(isset ($_parts['query'])) parse_str($_parts['query'], $this->_vars);
		return $retval;
	}

	/**
	 * Returns full uri string
	 *
	 * @access	public
	 * @param	array $parts An array specifying the parts to render
	 * @return	string The rendered URI string
	 */
	public function toString($parts = array('scheme', 'user', 'pass', 'host', 'port', 'path', 'query', 'fragment'))
	{
		$query = $this->getQuery(); //make sure the query is created

		$uri = '';
		$uri .= in_array('scheme', $parts)  ? (!empty($this->_scheme) ? $this->_scheme.'://' : '') : '';
		$uri .= in_array('user', $parts)	? $this->_user : '';
		$uri .= in_array('pass', $parts)	? (!empty ($this->_pass) ? ':' : '') .$this->_pass. (!empty ($this->_user) ? '@' : '') : '';
		$uri .= in_array('host', $parts)	? $this->_host : '';
		$uri .= in_array('port', $parts)	? (!empty ($this->_port) ? ':' : '').$this->_port : '';
		$uri .= in_array('path', $parts)	? $this->_path : '';
		$uri .= in_array('query', $parts)	? (!empty ($query) ? '?'.$query : '') : '';
		$uri .= in_array('fragment', $parts)? (!empty ($this->_fragment) ? '#'.$this->_fragment : '') : '';

		return $uri;
	}

	/**
	 * Adds a query variable and value, replacing the value if it
	 * already exists and returning the old value.
	 *
	 * @access	public
	 * @param	string $name Name of the query variable to set
	 * @param	string $value Value of the query variable
	 * @return	string Previous value for the query variable
	 */
	public function setVar($name, $value)
	{
		$tmp = @$this->_vars[$name];
		$this->_vars[$name] = $value;

		//empty the query
		$this->_query = null;

		return $tmp;
	}

	/**
	 * Returns a query variable by name
	 *
	 * @access	public
	 * @param	string $name Name of the query variable to get
	 * @return	array Query variables
	 */
	public function getVar($name = null, $default=null)
	{
		if(isset($this->_vars[$name])) {
			return $this->_vars[$name];
		}
		return $default;
	}

	/**
	 * Removes an item from the query string variables if it exists
	 *
	 * @access	public
	 * @param	string $name Name of variable to remove
	 */
	public function delVar($name)
	{
		if (in_array($name, array_keys($this->_vars)))
		{
			unset ($this->_vars[$name]);

			//empty the query
			$this->_query = null;
		}
	}

	/**
	 * Sets the query to a supplied string in format:
	 * 		foo=bar&x=y
	 *
	 * @access	public
	 * @param	mixed (array|string) $query The query string
	 */
	public function setQuery($query)
	{
		if(!is_array($query)) {
			if(strpos($query, '&amp;') !== false)
			{
			   $query = str_replace('&amp;','&',$query);
			}
			parse_str($query, $this->_vars);
		}

		if(is_array($query)) {
			$this->_vars = $query;
		}

		//empty the query
		$this->_query = null;
	}

	/**
	 * Returns flat query string
	 *
	 * @access	public
	 * @return	string Query string
	 */
	public function getQuery($toArray = false)
	{
		if($toArray) {
			return $this->_vars;
		}

		//If the query is empty build it first
		if(is_null($this->_query)) {
			$this->_query = $this->buildQuery($this->_vars);
		}

		return $this->_query;
	}

	/**
	 * Build a query from a array (reverse of the PHP parse_str())
	 *
	 * @access	public
	 * @return	string The resulting query string
	 * @see	parse_str()
	 */
	public function buildQuery ($params, $akey = null)
	{
		if ( !is_array($params) || count($params) == 0 ) {
			return false;
		}

		$out = array();

		//reset in case we are looping
		if( !isset($akey) && !count($out) )  {
			unset($out);
			$out = array();
		}

		foreach ( $params as $key => $val )
		{
			if ( is_array($val) ) {
				$out[] = self::buildQuery($val,$key);
				continue;
			}

			$thekey = ( !$akey ) ? $key : $akey.'['.$key.']';
			$out[] = $thekey."=".urlencode($val);
		}

		return implode("&",$out);
	}

	/**
	 * Get URI scheme (protocol)
	 * 		ie. http, https, ftp, etc...
	 *
	 * @access	public
	 * @return	string The URI scheme
	 */
	public function getScheme() {
		return $this->_scheme;
	}

	/**
	 * Set URI scheme (protocol)
	 * 		ie. http, https, ftp, etc...
	 *
	 * @access	public
	 * @param	string $scheme The URI scheme
	 */
	public function setScheme($scheme) {
		$this->_scheme = $scheme;
	}

	/**
	 * Get URI username
	 * 		returns the username, or null if no username was specified
	 *
	 * @access	public
	 * @return	string The URI username
	 */
	public function getUser() {
		return $this->_user;
	}

	/**
	 * Set URI username
	 *
	 * @access	public
	 * @param	string $user The URI username
	 */
	public function setUser($user) {
		$this->_user = $user;
	}

	/**
	 * Get URI password
	 * 		returns the password, or null if no password was specified
	 *
	 * @access	public
	 * @return	string The URI password
	 */
	public function getPass() {
		return $this->_pass;
	}

	/**
	 * Set URI password
	 *
	 * @access	public
	 * @param	string $pass The URI password
	 */
	public function setPass($pass) {
		$this->_pass = $pass;
	}

	/**
	 * Get URI host
	 * 		returns the hostname/ip, or null if no hostname/ip was specified
	 *
	 * @access	public
	 * @return	string The URI host
	 */
	public function getHost() {
		return $this->_host;
	}

	/**
	 * Set URI host
	 *
	 * @access	public
	 * @param	string $host The URI host
	 */
	public function setHost($host) {
		$this->_host = $host;
	}

	/**
	 * Get URI port
	 * 		returns the port number, or null if no port was specified
	 *
	 * @access	public
	 * @return	int The URI port number
	 */
	public function getPort() {
		return (isset ($this->_port)) ? $this->_port : null;
	}

	/**
	 * Set URI port
	 *
	 * @access	public
	 * @param	int $port The URI port number
	 */
	public function setPort($port) {
		$this->_port = $port;
	}

	/**
	 * Gets the URI path string
	 *
	 * @access	public
	 * @return	string The URI path string
	 */
	public function getPath() {
		return $this->_path;
	}

	/**
	 * Set the URI path string
	 *
	 * @access	public
	 * @param	string $path The URI path string
	 */
	public function setPath($path) {
		$this->_path = $this->cleanPath($path);
	}

	/**
	 * Get the URI archor string
	 * 		everything after the "#"
	 *
	 * @access	public
	 * @return	string The URI anchor string
	 */
	public function getFragment() {
		return $this->_fragment;
	}

	/**
	 * Set the URI anchor string
	 * 		everything after the "#"
	 *
	 * @access	public
	 * @param	string $anchor The URI anchor string
	 */
	public function setFragment($anchor) {
		$this->_fragment = $anchor;
	}

	/**
	 * Checks whether the current URI is using HTTPS
	 *
	 * @access	public
	 * @return	boolean True if using SSL via HTTPS
	 */
	public function isSSL() {
		return $this->getScheme() == 'https' ? true : false;
	}

	/** 
	 * Checks if the supplied URL is internal
	 *
	 * @access	public
	 * @param 	string $url The URL to check
	 * @return	boolean True if Internal
	 */
	public function isInternal($url) {
		$uri = self::getInstance($url);
		$base = $uri->toString(array('scheme', 'host', 'port', 'path'));
		$host = $uri->toString(array('scheme', 'host', 'port'));
		if(stripos($base, self::base()) !== 0 && !empty($host)) {
			return false;
		}
		return true;
	}

	/**
	 * Resolves //, ../ and ./ from a path and returns
	 * the result. Eg:
	 *
	 * /foo/bar/../boo.php	=> /foo/boo.php
	 * /foo/bar/../../boo.php => /boo.php
	 * /foo/bar/.././/boo.php => /foo/boo.php
	 *
	 * @access	public
	 * @param	string $uri The URI path to clean
	 * @return	string Cleaned and resolved URI path
	 */
	public function cleanPath($path)
	{
		$path = explode('/', preg_replace('#(/+)#', '/', $path));

		for ($i = 0; $i < count($path); $i ++) {
			if ($path[$i] == '.') {
				unset ($path[$i]);
				$path = array_values($path);
				$i --;

			}
			elseif ($path[$i] == '..' AND ($i > 1 OR ($i == 1 AND $path[0] != ''))) {
				unset ($path[$i]);
				unset ($path[$i -1]);
				$path = array_values($path);
				$i -= 2;

			}
			elseif ($path[$i] == '..' AND $i == 1 AND $path[0] == '') {
				unset ($path[$i]);
				$path = array_values($path);
				$i --;

			} else {
				continue;
			}
		}

		return implode('/', $path);
	}

	/**
	 * Backwards compatibility function for parse_url function
	 *
	 * This function solves different bugs in PHP versions lower then
	 * 4.4, will be deprecated in future versions.
	 *
	 * @access	private
	 * @return	array Associative array containing the URL parts
	 * @see parse_url()
	 */
	private function _parseURL($uri)
	{
		$parts = array();
		
		$parts = @parse_url($uri);
		
		return $parts;
	}
	
	/**
	* unparse_url
	* @param	array	$url	parse url
	* @return	string	uri
	*/
	private function unparse_url($url){
		$result = "";
		if(array_key_exists( 'scheme', $url )){
			$result .= $url["scheme"]."://";
		}
		if(array_key_exists( 'host', $url )){
			$result .= $url["host"];
		}
		if(array_key_exists( 'path', $url )){
			$result .= $url["path"];
		}
		return $result;
	}

	/**
	* relative uri to absolute uri
	* @param	string	url		relative uri
	* @param	string	base	this uri
	* @return	string	absolute uri
	*/
	public function resolve_url($url,$base='') {
			
			if(!strpos($url,"http://")!==FALSE){
				return $url;
			}
			
			//if (!strlen($base)) return $url;
			if(empty($base)){
				$base = $_SERVER['REQUEST_URI'];
			}
			/*throw at command line*/
			if(empty($base)){
				throw(new Exception("Base uri is empty!"));
			}
			
			$url  = $this->cleanPath($url);
			$base = $this->cleanPath($base);
			
			// Step 2
			if (!strlen($url)) return $base;
			// Step 3
			if (preg_match('!^[a-z]+:!i', $url)) return $url;
			$base = parse_url($base);
			if ($url{0} == "#") {
					// Step 2 (fragment)
					return "#";
					//$base['fragment'] = substr($url, 1);
					//return $this->unparse_url($base);
			}
			unset($base['fragment']);
			unset($base['query']);
			if (substr($url, 0, 2) == "//") {
					// Step 4
					return $this->unparse_url(array(
							'scheme'=>$base['scheme'],
							'path'=>$url,
					));
			} else if ($url{0} == "/") {
					// Step 5
					$base['path'] = $url;
			} else {
					// Step 6
					$path = explode('/', $base['path']);
					$url_path = explode('/', $url);
					// Step 6a: drop file from base
					array_pop($path);
					// Step 6b, 6c, 6e: append url while removing "." and ".." from
					// the directory portion
					$end = array_pop($url_path);
					foreach ($url_path as $segment) {
							if ($segment == '.') {
									// skip
							} else if ($segment == '..' && $path && $path[sizeof($path)-1] != '..') {
									array_pop($path);
							} else {
									$path[] = $segment;
							}
					}
					// Step 6d, 6f: remove "." and ".." from file portion
					if ($end == '.') {
							$path[] = '';
					} else if ($end == '..' && $path && $path[sizeof($path)-1] != '..') {
							$path[sizeof($path)-1] = '';
					} else {
							$path[] = $end;
					}
					// Step 6h
					$base['path'] = join('/', $path);

			}
			// Step 7
			return $this->unparse_url($base);
	}


}
