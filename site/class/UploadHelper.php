<?php
/**
 * Upload tool
 *
 * @package		classes
 * @subpackage	tool
 * @author 		jerry.cao (caowlong163@163.com)
 */
include_once(dirname(__file__) . '/gdextend.php');
include_once(dirname(__file__) . '/imagethumb.php');

ini_set('memory_limit', '500M');
set_time_limit(0);

class UploadHelper
{
	public static $uploadRoot = '';
	public static $extensions = array();

	public function __construct()
	{
		// 上传目录
		if (self::$uploadRoot == '') {
			self::$fileUrl = '/upload/';
			self::$uploadRoot = Config_App::rootdir().self::fileUrl;
		}
	}

	public static function setDir($dir)
	{
		self::$uploadRoot = $dir;
	}

	public static function upload()
	{
		$file = current($_FILES);
		if ($file['error'] != 0) {
			return '';
		}

		try {
			// 上传LOGO
			$uploader = new Fuse_Image_Uploader($file, self::$uploadRoot);
			if (!empty(self::$extensions)) {
				if (!$uploader->checkExtension(self::$extensions)) {
					return array('status' => 'fail', 'msg' => '不支持此扩展名的文件');
				}
			}
			$uploader->toUpload();
			$fileName = $uploader->getFile();
		} catch (Exception $e) {
			// var_dump($e->getMessage());
			// exit();
			return array('status' => 'fail', 'msg' => '上传失败');
		}

		return array('status' => 'success', 'fileName' => $fileName);
	}

	public static function setExt($ext)
	{
		self::$extensions = $ext;
	}
}
?>