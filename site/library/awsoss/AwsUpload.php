<?php
require_once __DIR__.'/vendor/autoload.php';
class AwsUpload{
	public static function upload($localPath, $destPath){
		global $global;
		if (!file_exists($localPath)){
			return false;
		}
		$s3 = new Aws\S3\S3Client($global['awsoss']);
		$s3->registerStreamWrapper();
		$res = file_put_contents("s3://{$destPath}", file_get_contents($localPath));
		return !($res === 0 || $res === false);
	}
	
	public static function read($destPath){
		global $global;
		$s3 = new Aws\S3\S3Client($global['awsoss']);
		$s3->registerStreamWrapper();
		$content = file_get_contents("s3://{$destPath}");
		$fileName = substr($destPath, 1+strrpos($destPath, '/'));
		file_put_contents(DIR_IMAGE.'test/'.$fileName, $content);
		return $content;
	}
	
	public static function del($destPath){
		global $global;
		$s3 = new Aws\S3\S3Client($global['awsoss']);
		$bucket = trim(DIR_REMOTE_IMG,'/');
		$prefix = ltrim($destPath, $bucket.'/');
		$listObjectsParams = ['Bucket' => $bucket, 'Prefix' => $prefix];
		$delete = Aws\S3\BatchDelete::fromListObjects($s3, $listObjectsParams);
		try {
			$delete->delete();
			return true;			
		}
		catch (DeleteMultipleObjectsException $e){
			return false;
		}
	}
}