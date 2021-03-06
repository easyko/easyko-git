<?php
require 'vendor/autoload.php';

$s3 = new Aws\S3\S3Client([
    'version' => '2006-03-01',
    'region'  => 'ap-southeast-1',
    'credentials' => array(
        'key' => 'AKIAJLSO23NX5WBQMEVQ',
        'secret'  => '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',
    )
]);

// bucket名称
$bucket = 'hk-image';

/************************************************  S3文件上传接口使用示例  - stat ***********************************************/
// 准备上传的文件地址
$uploadFile = '/opt/www/hkbtoc/test/image/hk_image/catalog/placeholder.png';

// 上传至对象存储服务器后的新文件名
$objectFile = 'catalog/placeholder.png';

// 单个文件上传
uploadFile($s3, $bucket, $objectFile, $uploadFile);

// 多个文件上传
$srcDir = "/opt/www/image/hk_image/catalog";
$baseDir = "/opt/www/image/hk_image/";

$bucket = 'hkimage';
recursUpload($srcDir, $baseDir, $s3, $bucket);
/************************************************  S3文件上传接口使用示例  - end ***********************************************/

/************************************************  S3文件获取接口使用示例  - stat ***********************************************/
$s3->registerStreamWrapper();

// 获取远程文件的路径
$readFile = "";

echo "Downloading that same object:\n";
$data = file_get_contents("s3://{$bucket}/{$readFile}");
echo "\n---BEGIN---\n";
echo $data;
echo "\n----END----\n\n";
/************************************************  S3文件获取接口使用示例  - end ***********************************************/

/************************************************  S3文件删除接口使用示例  - stat ***********************************************/
/************************************************  S3文件删除接口使用示例  - end ***********************************************/

/*
 * 上传接口方法
 */
function uploadFile($s3, $bucket, $key, $filePath) {
    $s3->registerStreamWrapper();
    echo "Creating a second object with key {$key} using stream wrappers\n";
    $body = file_get_contents($filePath);

    file_put_contents("s3://{$bucket}/{$key}", $body);
}

/*
 * 该方法循环遍历至最后目录文件，用递归调用的方法，遍历baseDir下的所有文件
 */
function recursUpload($srcDir, $baseDir, $s3, $bucket){
    $fso = opendir($srcDir);
    while($flist = readdir($fso)){
        $filePath = $srcDir . '/' . $flist;
        $object = substr($filePath, strlen($baseDir));
        if($flist!="." && $flist!=".."){
            echo $filePath;echo '<br>';
            echo $object;echo '<hr>';
            if(!is_dir($filePath)){//判断读取出的是否是目录
                uploadFile($s3, $bucket, $object, $filePath);
            }else{
                recursUpload($filePath, $baseDir, $s3, $bucket);// 递归调用
            }
        }
        //else echo "是当前目录或跟目录";
    }
}
?>