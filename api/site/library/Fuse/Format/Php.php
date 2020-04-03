<?php

// Check to ensure this file is within the rest of the framework

/**
 * PHP class format handler for Registry
 *
 * @package 	Imag.Framework
 * @subpackage  Registry
 * @author      gary wang (wangbaogang123@hotmail.com)
 */
class Fuse_Format_Php {

	/**
	 * Converts an object into a php class string.
	 * 	- NOTE: Only one depth level is supported.
	 *
	 * @access public
	 * @param object $object Data Source Object
	 * @param array  $param  Parameters used by the formatter
	 * @return string Config class formatted string
	 */
	public function objectToString( $object, $params=array() ) {

		// Build the object variables string
		$vars = '';
		$functions = '';

		$static = '';
		if(isset($params["static"]) && $params["static"]){
			$static = 'static ';
		}
		//is_array($object);
		if(!is_array($object)){
			$object = get_object_vars( $object );
		}
		foreach ($object as $k => $v)
		{
			if (is_scalar($v)) {
				$vars .= "\tpublic {$static}$". $k . " = '" . addslashes($v) . "';\n";
			} elseif (is_array($v)) {
				if($k=="_function"){
					foreach ($v as $key => $function)
					{
						$functions .= $function. "\n";
					}
				}else{
					$vars .= "\tpublic {$static}$". $k . " = " . $this->_getArrayString($v) . ";\n";
				}
			}
		}

		$str = "<?php\nclass ".$params['class']." {\n";
		$str .= $vars;
		$str .= $functions;
		$str .= "}\n?>";

		return $str;
		
	}
	

	/**
	 * Placeholder method
	 *
	 * @access public
	 * @return boolean True
	 * 
	 */
	public function stringToObject( $data, $namespace='' ) {
		return true;
	}

	public function _getArrayString($a)
	{
		$s = 'array(';
		$i = 0;
		foreach ($a as $k => $v)
		{
			$s .= ($i) ? ', ' : '';
			$s .= '"'.$k.'" => ';
			if (is_array($v)) {
				$s .= $this->_getArrayString($v);
			} else {
				$s .= '"'.addslashes($v).'"';
			}
			$i++;
		}
		$s .= ')';
		return $s;
	}
}