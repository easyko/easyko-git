<?php
/* ------------------------------------- 这个是用于批量上传图片到aws s3服务的脚本文件 ----------------------------------- */
// Include the SDK using the Composer autoloader
require 'vendor/autoload.php';

$s3 = new Aws\S3\S3Client([
    'version' => '2006-03-01',
    'region'  => 'ap-southeast-1',
        'credentials' => array(
                'key' => 'AKIAJLSO23NX5WBQMEVQ',
                'secret'  => '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',
          )
]);


// Let's get the contents of this object.

$srcDir = "/opt/www/image/hk_image/catalog";
$baseDir = "/opt/www/image/hk_image/";

$bucket = 'hkimage';
recursUpload($srcDir, $baseDir, $s3, $bucket);


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
