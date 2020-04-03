<?php

/**
 * Do not use or reference this directly from your client-side code.
 * Instead, this should be required via the endpoint.php or endpoint-cors.php
 * file(s).
 */

class UploadHandler {

    public $allowedExtensions = array();
    public $notAllowedExtensions = array();
    public $sizeLimit = null;
    public $inputName = 'qqfile';
    public $chunksFolder = 'chunks';

    public $chunksCleanupProbability = 0.001; // Once in 1000 requests on avg
    public $chunksExpireIn = 604800; // One week

    protected $uploadName;
    
    public function getInputName() {
		return $this->inputName;
	}

    /**
     * Get the original filename
     */
    public function getName() {
        if (isset($_REQUEST['qqfilename']))
            return $_REQUEST['qqfilename'];

        if (isset($_FILES[$this->inputName]))
            return $_FILES[$this->inputName]['name'];
    }

    public function getInitialFiles() {
        $initialFiles = array();

        for ($i = 0; $i < 5000; $i++) {
            array_push($initialFiles, array("name" => "name" + $i, uuid => "uuid" + $i, thumbnailUrl => "init_file.png"));
        }

        return $initialFiles;
    }

    /**
     * Get the name of the uploaded file
     */
    public function getUploadName(){
        return $this->uploadName;
    }

    public function combineChunks($uploadDirectory) {
        $uuid = $_POST['qquuid'];
        $name = $this->getName();
        $targetFolder = $this->chunksFolder.DIRECTORY_SEPARATOR.$uuid;
        $totalParts = isset($_REQUEST['qqtotalparts']) ? (int)$_REQUEST['qqtotalparts'] : 1;

        $targetPath = join(DIRECTORY_SEPARATOR, array($uploadDirectory, $uuid, $name));
        $this->uploadName = $name;

        if (!file_exists($targetPath)){
            mkdir(dirname($targetPath));
        }
        $target = fopen($targetPath, 'wb');

        for ($i=0; $i<$totalParts; $i++){
            $chunk = fopen($targetFolder.DIRECTORY_SEPARATOR.$i, "rb");
            stream_copy_to_stream($chunk, $target);
            fclose($chunk);
        }

        // Success
        fclose($target);

        for ($i=0; $i<$totalParts; $i++){
            unlink($targetFolder.DIRECTORY_SEPARATOR.$i);
        }

        rmdir($targetFolder);

        if (!is_null($this->sizeLimit) && filesize($targetPath) > $this->sizeLimit) {
            unlink($targetPath);
            http_response_code(413);
            return array("success" => false, "uuid" => $uuid, "preventRetry" => true);
        }

        return array("success" => true, "uuid" => $uuid);
    }

    /**
     * Process the upload.
     * @param string $uploadDirectory Target directory.
     * @param string $name Overwrites the name of the file.
     */
    public function handleUpload($uploadDirectory, $name = null){
        if (is_writable($this->chunksFolder) &&
            1 == mt_rand(1, 1/$this->chunksCleanupProbability)){

            // Run garbage collection
            $this->cleanupChunks();
        }

        // Check that the max upload size specified in class configuration does not
        // exceed size allowed by server config
        if ($this->toBytes(ini_get('post_max_size')) < $this->sizeLimit ||
            $this->toBytes(ini_get('upload_max_filesize')) < $this->sizeLimit){
            $neededRequestSize = max(1, $this->sizeLimit / 1024 / 1024) . 'M';
            return array('error' => '服务器错误,请将系统参数post_max_size和upload_max_filesize修改为' . $neededRequestSize);
        }

        if ($this->isInaccessible($uploadDirectory)){
            return array('error' => '服务器错误,上传目录不可写');
        }

        $type = $_SERVER['CONTENT_TYPE'];
        if (isset($_SERVER['HTTP_CONTENT_TYPE'])) {
            $type = $_SERVER['HTTP_CONTENT_TYPE'];
        }

        if(!isset($type)) {
            return array('error' => '没有上传的文件');
        } else if (strpos(strtolower($type), 'multipart/') !== 0){
            return array('error' => '服务器错误,非断点续传请求,请将forceMultipart设置为默认值(true)');
        }

        // Get size and name
        $file = isset($_FILES[$this->inputName]) ? $_FILES[$this->inputName] : '';
        if (empty($file)) {
			return array('error' => '无上传文件');
		}

        $size = $file['size'];
        if (isset($_REQUEST['qqtotalfilesize'])) {
            $size = $_REQUEST['qqtotalfilesize'];
        }

        // Validate file size
        if ($size == 0){
            return array('error' => '上传文件为空');
        }

        if (!is_null($this->sizeLimit) && $size > $this->sizeLimit) {
            return array('error' => '上传文件太大', 'preventRetry' => true);
        }

        // Validate name
        if (!isset($file['name']) || $file['name'] === ''){
            return array('error' => '文件名称为空');
        }
        
        // Validate file extension
        $pathinfo = pathinfo($file['name']);
        $ext = isset($pathinfo['extension']) ? $pathinfo['extension'] : '';

		if ($name === null){
            $name = $this->getName();
        } else {
			$name .= '.' . $ext;
		}
        $name = iconv('UTF-8', 'GBK', $name);

        if($this->allowedExtensions && !in_array(strtolower($ext), array_map('strtolower', $this->allowedExtensions))){
            $these = implode(', ', $this->allowedExtensions);
            return array('error' => '上传文件扩展名无效,应是'. $these . '中的一个');
        }
		
		if($this->notAllowedExtensions && in_array(strtolower($ext), array_map('strtolower', $this->notAllowedExtensions))){
            $these = implode(', ', $this->notAllowedExtensions);
            return array('error' => '上传文件扩展名非法');
        }

        // Save a chunk
        $totalParts = isset($_REQUEST['qqtotalparts']) ? (int)$_REQUEST['qqtotalparts'] : 1;

        $uuid = isset($_REQUEST['qquuid']) ? $_REQUEST['qquuid'] : time();
        if ($totalParts > 1){
        # chunked upload

            $chunksFolder = $this->chunksFolder;
            $partIndex = isset($_REQUEST['qqpartindex']) ? (int)$_REQUEST['qqpartindex'] : rand();

            if (!is_writable($chunksFolder) && !is_executable($uploadDirectory)){
                return array('error' => '服务器错误,目录不可写或不可执行');
            }

            // $targetFolder = $this->chunksFolder.DIRECTORY_SEPARATOR.$uuid;
            $targetFolder = $this->chunksFolder;

            if (!file_exists($targetFolder)){
                mkdir($targetFolder);
            }

            $target = $targetFolder.'/'.$partIndex;
            $success = move_uploaded_file($_FILES[$this->inputName]['tmp_name'], $target);

            return array("success" => true, "uuid" => $uuid);

        }
        else {
        # non-chunked upload

            // $target = join(DIRECTORY_SEPARATOR, array($uploadDirectory, $uuid, $name));
            $target = join(DIRECTORY_SEPARATOR, array($uploadDirectory, $name));
            if ($target){
				if (file_exists($target)) {
					return array('success' => false, 'error'=> '已存在同名文件，请修改文件名');
				}

                $this->uploadName = $_FILES[$this->inputName]['name']; // basename($target);

                if (!is_dir(dirname($target))){
                    mkdir(dirname($target));
                }
                if (move_uploaded_file($file['tmp_name'], $target)){
                    return array('success'=> true, 'uuid' => $uuid, 'name' => $name, 'size' => $file['size']);
                }
            }

            return array('error'=> '无法保存上载的文件,上载被取消或遇到服务器错误');
        }
    }

    /**
     * Process a delete.
     * @param string $uploadDirectory Target directory.
     * @params string $name Overwrites the name of the file.
     *
     */
    public function handleDelete($uploadDirectory, $name=null)
    {
		if (file_exists($uploadDirectory)) {
			@unlink($uploadDirectory);
		}
		$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $tokens = explode('/', $url);
        $uuid = $tokens[sizeof($tokens)-1];
		return array("success" => true, "uuid" => $uuid);

		// ========================================

        if ($this->isInaccessible($uploadDirectory)) {
            return array('error' => "Server error. Uploads directory isn't writable" . ((!$this->isWindows()) ? " or executable." : "."));
        }

        $targetFolder = $uploadDirectory;
        $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $tokens = explode('/', $url);
        $uuid = $tokens[sizeof($tokens)-1];

        // $target = join(DIRECTORY_SEPARATOR, array($targetFolder, $uuid));
        $target = join(DIRECTORY_SEPARATOR, array($targetFolder));

        if (is_dir($target)){
            $this->removeDir($target);
            return array("success" => true, "uuid" => $uuid);
        } else {
            return array("success" => false,
                "error" => "File not found! Unable to delete.".$url,
                "path" => $uuid
            );
        }

    }

    /**
     * Returns a path to use with this upload. Check that the name does not exist,
     * and appends a suffix otherwise.
     * @param string $uploadDirectory Target directory
     * @param string $filename The name of the file to use.
     */
    protected function getUniqueTargetPath($uploadDirectory, $filename)
    {
        // Allow only one process at the time to get a unique file name, otherwise
        // if multiple people would upload a file with the same name at the same time
        // only the latest would be saved.

        if (function_exists('sem_acquire')){
            $lock = sem_get(ftok(__FILE__, 'u'));
            sem_acquire($lock);
        }

        $pathinfo = pathinfo($filename);
        $base = $pathinfo['filename'];
        $ext = isset($pathinfo['extension']) ? $pathinfo['extension'] : '';
        $ext = $ext == '' ? $ext : '.' . $ext;

        $unique = $base;
        $suffix = 0;

        // Get unique file name for the file, by appending random suffix.

        while (file_exists($uploadDirectory . DIRECTORY_SEPARATOR . $unique . $ext)){
            $suffix += rand(1, 999);
            $unique = $base.'-'.$suffix;
        }

        $result =  $uploadDirectory . DIRECTORY_SEPARATOR . $unique . $ext;

        // Create an empty target file
        if (!touch($result)){
            // Failed
            $result = false;
        }

        if (function_exists('sem_acquire')){
            sem_release($lock);
        }

        return $result;
    }

    /**
     * Deletes all file parts in the chunks folder for files uploaded
     * more than chunksExpireIn seconds ago
     */
    protected function cleanupChunks(){
        foreach (scandir($this->chunksFolder) as $item){
            if ($item == "." || $item == "..")
                continue;

            $path = $this->chunksFolder.DIRECTORY_SEPARATOR.$item;

            if (!is_dir($path))
                continue;

            if (time() - filemtime($path) > $this->chunksExpireIn){
                $this->removeDir($path);
            }
        }
    }

    /**
     * Removes a directory and all files contained inside
     * @param string $dir
     */
    protected function removeDir($dir){
        foreach (scandir($dir) as $item){
            if ($item == "." || $item == "..")
                continue;

            if (is_dir($item)){
                $this->removeDir($item);
            } else {
                unlink(join(DIRECTORY_SEPARATOR, array($dir, $item)));
            }

        }
        rmdir($dir);
    }

    /**
     * Converts a given size with units to bytes.
     * @param string $str
     */
    protected function toBytes($str){
        $val = trim($str);
        $last = strtolower($str[strlen($str)-1]);
        switch($last) {
            case 'g': $val *= 1024;
            case 'm': $val *= 1024;
            case 'k': $val *= 1024;
        }
        return $val;
    }

    /**
     * Determines whether a directory can be accessed.
     *
     * is_executable() is not reliable on Windows prior PHP 5.0.0
     *  (http://www.php.net/manual/en/function.is-executable.php)
     * The following tests if the current OS is Windows and if so, merely
     * checks if the folder is writable;
     * otherwise, it checks additionally for executable status (like before).
     *
     * @param string $directory The target directory to test access
     */
    protected function isInaccessible($directory) {
        $isWin = $this->isWindows();
        $folderInaccessible = ($isWin) ? !is_writable($directory) : ( !is_writable($directory) && !is_executable($directory) );
        return $folderInaccessible;
    }

    /**
     * Determines is the OS is Windows or not
     *
     * @return boolean
     */

    protected function isWindows() {
    	$isWin = (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN');
    	return $isWin;
    }

}
