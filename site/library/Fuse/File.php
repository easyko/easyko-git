<?php
/**
 * A File handling class
 *
 * @static
 * @package 	Imag.Framework
 * @subpackage	FileSystem
 * @modify      gary wang (wangbaogang123@hotmail.com)
 */
class Fuse_File
{
	public $ftp = null;
	
	public static $instance = null;

	public static function getInstance(){
		if(self::$instance==null){
			self::$instance = new Fuse_File();
		}
		return self::$instance;
	}

	public function setFtp($ftp){
		$this->ftp = $ftp;
		return $this;
	}

	/**
	 * Gets the extension of a file name
	 *
	 * @param string $file The file name
	 * @return string The file extension
	 * 
	 */
	public function getExt($file) {
		$dot = strrpos($file, '.') + 1;
		return substr($file, $dot);
	}

	/**
	 * Strips the last extension off a file name
	 *
	 * @param string $file The file name
	 * @return string The file name without the extension
	 * 
	 */
	public function stripExt($file) {
		return preg_replace('#\.[^.]*$#', '', $file);
	}

	/**
	 * Makes file name safe to use
	 *
	 * @param string $file The name of the file [not full path]
	 * @return string The sanitised string
	 * 
	 */
	public function makeSafe($file) {
		$regex = array('#(\.){2,}#', '#[^A-Za-z0-9\.\_\- ]#', '#^\.#');
		return preg_replace($regex, '', $file);
	}

	/**
	 * Copies a file
	 *
	 * @param string $src The path to the source file
	 * @param string $dest The path to the destination file
	 * @param string $path An optional base path to prefix to the file names
	 * @return boolean True on success
	 * 
	 */
	public function copy($src, $dest, $path = null)
	{
		// Prepend a base path if it exists
		if ($path) {
			$src = Path::clean($path."/".$src);
			$dest = Path::clean($path."/".$dest);
		}

		//Check src path
		if (!is_readable($src)) {
			throw(new Exception('File::copy: ' . 'Cannot find or read file'));
			return false;
		}

		if ($this->ftp) {
			$ftp = $this->ftp;
			//Translate the destination path for the FTP account
			$dest = Fuse_Path::clean(str_replace(PATH_ROOT, $ftp->getRoot(), $dest), '/');
			if (!$ftp->store($src, $dest)) {
				// FTP connector throws an error
				return false;
			}
			$ret = true;
		} else {
			// If the parent folder doesn't exist we must create it
			if (!$this->exists(dirname($dest),false)) {
				Fuse_Folder::getInstance()->create(dirname($dest));
			}
			
			if (!@ copy($src, $dest)) {
				throw(new Exception('Copy failed! src:'.$src."dest:".$dest));
				return false;
			}
			$ret = true;
		}
		return $ret;
	}

	/**
	 * Delete a file or array of files
	 *
	 * @param mixed $file The file name or an array of file names
	 * @return boolean  True on success
	 */
	public function delete($file)
	{

		if (is_array($file)) {
			$files = $file;
		} else {
			$files[] = $file;
		}

		// Do NOT use ftp if it is not enabled
		if ($this->ftp) {
			// Connect the FTP client
			$ftp = $this->ftp;
		}

		foreach ($files as $file)
		{
			$file = Fuse_Path::clean($file);
			
			if(!$this->exists($file)){
				continue;
			}

			// Try making the file writeable first. If it's read-only, it can't be deleted
			// on Windows, even if the parent folder is writeable
			@chmod($file, 0777);

			// In case of restricted permissions we zap it one way or the other
			// as long as the owner is either the webserver or the ftp
			if (@unlink($file)) {
				// Do nothing
			} elseif ($ftpOptions['ftp_enable'] == 1) {
				$file = Fuse_Path::clean(str_replace(PATH_ROOT, $ftpOptions['ftp_root'], $file), '/');
				if (!$ftp->delete($file)) {
					// FTP connector throws an error
					return false;
				}
			} else {
				$filename	= basename($file);
				throw(new Exception('SOME_ERROR_CODE Delete failed '.$filename));
				return false;
			}
		}

		return true;
	}

	/**
	 * Moves a file
	 *
	 * @param string $src The path to the source file
	 * @param string $dest The path to the destination file
	 * @param string $path An optional base path to prefix to the file names
	 * @return boolean True on success
	 */
	public function move($src, $dest, $path = '')
	{
		if ($path) {
			$src  = Fuse_Path::clean($path.DS.$src);
			$dest = Fuse_Path::clean($path.DS.$dest);
		}

		//Check src path
		if (!$this->exists($src,false)) {
			throw(new Exception('File::move Cannot find, read or write file'.$src));
			return false;
		}

		if ($this->ftp) {
			$ftp = $this->ftp;

			//Translate path for the FTP account
			$src	= Fuse_Path::clean(str_replace(PATH_ROOT, $ftp->getRoot(), $src), '/');
			$dest	= Fuse_Path::clean(str_replace(PATH_ROOT, $ftp->getRoot(), $dest), '/');

			// Use FTP rename to simulate move
			if (!$ftp->rename($src, $dest)) {
				throw(new Exception('Rename failed'));
				return false;
			}
		} else {
			if (!@ rename($src, $dest)) {
				throw(new Exception('Rename failed'));
				return false;
			}
		}
		return true;
	}

	/**
	 * Read the contents of a file
	 *
	 * @param string $filename The full file path
	 * @param boolean $incpath Use include path
	 * @param int $amount Amount of file to read
	 * @param int $chunksize Size of chunks to read
	 * @param int $offset Offset of the file
	 * @return mixed Returns file contents or boolean False if failed
	 */
	public function read($filename, $incpath = false, $amount = 0, $chunksize = 8192, $offset = 0)
	{
		// Initialize variables
		$data = null;
		if($amount && $chunksize > $amount) { $chunksize = $amount; }
		if (false === $fh = fopen($filename, 'rb', $incpath)) {
			throw(new Exception('21,File::read: Unable to open file '.$filename));
			return false;
		}
		clearstatcache();
		if($offset) fseek($fh, $offset);
		if ($fsize = @ filesize($filename)) {
			if($amount && $fsize > $amount) {
				$data = fread($fh, $amount);
			} else {
				$data = fread($fh, $fsize);
			}
		} else {
			$data = '';
			$x = 0;
			// While its:
			// 1: Not the end of the file AND
			// 2a: No Max Amount set OR
			// 2b: The length of the data is less than the max amount we want
			while (!feof($fh) && (!$amount || strlen($data) < $amount)) {
				$data .= fread($fh, $chunksize);
			}
		}
		fclose($fh);

		return $data;
	}

	/**
	 * Write contents to a file
	 *
	 * @param string $file The full file path
	 * @param string $buffer The buffer to write
	 * @return boolean True on success
	 */
	public function write($file, $buffer)
	{

		// If the destination directory doesn't exist we need to create it
		if (!$this->exists(dirname($file),false)) {
			Fuse_Folder::getInstance()->create(dirname($file));
		}

		if ($this->ftp) {
			$ftp = $this->ftp;

			// Translate path for the FTP account and use FTP write buffer to file
			$file = Fuse_Path::clean(str_replace(PATH_ROOT, $ftp->getRoot(), $file), '/');
			$ret = $ftp->write($file, $buffer);
		} else {
			$file = Fuse_Path::clean($file);
			$ret = file_put_contents($file, $buffer);
		}
		return $ret;
	}

	/**
	 * Moves an uploaded file to a destination folder
	 *
	 * @param string $src The name of the php (temporary) uploaded file
	 * @param string $dest The path (including filename) to move the uploaded file to
	 * @return boolean True on success
	 */
	public function upload($src, $dest)
	{
	
		$ret		= false;

		// Ensure that the path is valid and clean
		$dest = Fuse_Path::clean($dest);

		// Create the destination directory if it does not exist
		$baseDir = dirname($dest);
		if (!$this->exists($baseDir,false)) {
			Fuse_Folder::getInstance()->create($baseDir);
		}

		if ($this->ftp) {
			$ftp = $this->ftp;
			//Translate path for the FTP account
			$dest = Fuse_Path::clean(str_replace(PATH_ROOT, $ftp->getRoot(), $dest), '/');

			// Copy the file to the destination directory
			if ($ftp->store($src, $dest)) {
				$ftp->chmod($dest, 0777);
				$ret = true;
			} else {
				throw(new Exception('21,WARNFS_ERR02'));
			}
		} else {
			if (is_writeable($baseDir) && move_uploaded_file($src, $dest)) { // Short circuit to prevent file permission errors
				if (Fuse_Path::setPermissions($dest)) {
					$ret = true;
				} else {
					throw(new Exception('21,WARNFS_ERR01'));
				}
			} else {
				throw(new Exception('21,WARNFS_ERR02'));
			}
		}
		return $ret;
	}

	/**
	 * Wrapper for the standard file_exists function
	 *
	 * @param string $file File path
	 * @return boolean True if path is a file
	 */
	public function exists($file,$local=true)
	{
		
		if(!$local&&$this->ftp){
			return $this->ftp->exists($file);
		}
		
		return is_file(Path::clean($file));
	}

	/**
	 * Returns the name, sans any path
	 *
	 * param string $file File path
	 * @return string filename
	 */
	public function getName($file) {
		$slash = strrpos($file, DS) + 1;
		return substr($file, $slash);
	}
}
