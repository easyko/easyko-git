<?php
/**
 * File operator
 * @package 	Imag.Framework
 * @subpackage	FileSystem
 * @author      gary wang (wangbaogang123@hotmail.com)
 */
class Fuse_FileSystem{

	/**
	 * Create new folder
	 * @param	string $folder
	 * @return	boolean
	 */
	public static function creatFolder($folder){

		if(file_exists($folder)){
			return true;
		}
		return self::recursive_mkdir($folder);
	}


	public static function recursive_mkdir($path, $mode = 0777) {
	    $dirs = explode(DIRECTORY_SEPARATOR , $path);
	    $count = count($dirs);
	    $path = '';
	    $DS = '';
	    for ($i = 0; $i < $count; ++$i) {
	        $path .= $DS . $dirs[$i];
	        if(empty($DS)){
	        	$DS = DIRECTORY_SEPARATOR;
	        }

	        if (!is_dir($path) && !mkdir($path, $mode)) {
	            return false;
	        }
	    }
	    return true;
	}

	/**
	 * delete a file
	 * @param	string $filename
	 * @return	boolean
	 */
	public static function delete($filename){

		if(file_exists($filename) && filetype($filename)=="file"){
			 if(!unlink($filename)){
				return false;
			 }
		}
		return true;

	}

	/**
	 * read a file to array
	 * @param	string $file
	 * @return	array
	 */
	public static function read($file){

		$list = array();
		$handle = fopen($file, "r");
		if($handle===FALSE){
			return $list;
		}
		while (!feof($handle)) {
		  array_push($list,fgets($handle, 4096));
		}
		fclose($handle);
		return $list;

	}

	/**
	 * write content to a file
	 * @param	string $file
	 * @param	string $mycontent
	 * @return	boolean
	 */
	public static function write($file, $mycontent,$op="w"){

		$fp = fopen($file, $op);
		$issu = fwrite($fp, $mycontent);
		fclose($fp);
		return $issu;

	}

	/**
	 * get content from url
	 * @param	string $url
	 * @return	string
	 */
	public static function getHtml($url){
		return file_get_contents($url);
	}

    /**
     * get content from url by curl
	 * @param	string $url
	 * @return	string
     */
    public static function getHtmlByCurl($url) {
        $ch = curl_init();
        $timeout = 300;
        curl_setopt ($ch, CURLOPT_URL, $url);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $file_contents = curl_exec($ch);
        curl_close($ch);
        return $file_contents;
    }

    /**
	 * delete a folder to local.
	 *
	 * @param	string dir	The folder to delete.
	 * @return	boolean
	 *
	 */

   public static function rmFolder($dir) {
		    if (!file_exists($dir)) return true;
		    if (!is_dir($dir) || is_link($dir)) return unlink($dir);
		        foreach (scandir($dir) as $item) {
		            if ($item == '.' || $item == '..'){
		            	continue;
		            }
		            if (!$this->rmFolder($dir . "/" . $item)) {
		                chmod($dir . "/" . $item, 0777);
		                if (!$this->rmFolder($dir . "/" . $item)){
		                	return false;
		                }
		            };
		        }
		        return rmdir($dir);
		}

    /**
	 * Copy a folder to local.
	 *
	 * @param	string source	The path to the source folder.
	 * @param	string $target	The path to the destination folder.
	 * @return	null
	 *
	 */
    public static function copyFolder($source,$dest,$includeself = false) {

		if(!is_dir($source)){
			exit("source '{$source}' is not a directory!");
		}

		if(!file_exists($dest)){
			if(!self::creatFolder($dest)){
				exit("create $dest failed!");
			}
		}

		if(!is_writable($dest)){
			exit("target writable failed!");
		}

		$d = dir($source);

		if($includeself){
			$list = explode("/",$source);
			$source = array_pop($list);
			if(!is_dir($dest."/".$source)){
				mkdir($dest."/".$source);
				$dest = $dest."/".$source;
			}
		}

		while (false !== ($entry = $d->read())) {
			if($entry!='.' && $entry!='..') {
					if(strpos($entry,".svn")!== false){
							continue;
					}
					if(is_dir($source."/".$entry)) {
						if(!file_exists($dest."/".$entry)){
							if(!self::creatFolder($dest."/".$entry)){
								exit("create {$dest}/{$entry} failed!");
							}
						}
						self::copyFolder($source."/".$entry,$dest."/".$entry);
					} else {
						copy($source."/".$entry, $dest."/".$entry);
					}
			}
		}
		$d->close();
	}
}
?>