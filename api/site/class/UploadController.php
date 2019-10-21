<?php
/**
 *
 * Controller for Upload
 *
 * @desc	上传
 * @author	jerry.cao (caowlong163@163.com)
 * $date    2019-10-20 11:27:52
 */

class UploadController extends CommonController
{
    private $tempDirName = '';
    private $rootDir = '';

    /**
     * Constructor
     *
     * @params	array	Controller configuration array
     */
    public function __construct($config = array())
    {
        parent::__construct($config);
        //parent::checkLoginValid();

        $this->registerTask('index', 'index');

        // ---------------------------------------

        // 配置文件
        include_once(dirname(__file__) . '/config.php');

        $this->modelProject = $this->createModel('Model_Project', dirname( __FILE__ ));
        $this->modelUpload = $this->createModel('Model_Upload', dirname( __FILE__ ));

        // 任务类型
        $this->modelProject->setProjectTypeList($projectTypeList);
        // 项目状态
        $this->modelProject->setProjectStatusList($projectStatusList);
        // 文件模块类型
        $this->modelUpload->setFileUploadTypeList($fileUploadTypeList);
        // 文件上传临时目录
        $this->tempDirName = $uploadTempDirName;

        $this->rootDir = Config_App::webdir();
        $this->homeDir = Config_App::homeurl() . '/easyku';

        // 上传插件
        require_once dirname(__FILE__) . '/upload/handler.php';
        $this->uploader = new UploadHandler();
        $this->uploader->sizeLimit = 2 * (1024 * 1024);
        $this->uploader->inputName = 'qqfile';
        $this->uploader->notAllowedExtensions = array('exe', 'js', 'css', 'sql');
    }



    /**
     * 上传文件
     */
    public function index()
    {
        $projectNo = Fuse_Request::getFormatVar($this->params, 'projectNo');
        $taskNo = Fuse_Request::getFormatVar($this->params, 'taskNo');
        $fileType = Fuse_Request::getFormatVar($this->params, 'name');

        if (empty($projectNo)) {
            die(json_encode(array('code'=> '1111', 'message' => '参数项目编号缺失', 'data' => '')));
        }

        // 项目编号和任务单号都不为空，即上传任务单下面的文件
        if ($taskNo != '') {
            $fileType = 'projectTaskFile';
        }

        if (!$this->modelUpload->checkFileUploadTypeValid($fileType)) {
            die(json_encode(array('code'=> '2222', 'message' => '非指定模块类型文件', 'data' => '')));
        }

        // 文件保存目录
        $fileDir = $this->tempDirName . '/upload/' . $projectNo . '/';
        if ($taskNo != '') {
            $fileDir .= $taskNo;
        } else {
            $fileDir .= $fileType;
        }
        $saveDir = $this->rootDir . $fileDir;
        $saveDir = iconv('GB2312', 'UTF-8', $saveDir);

        if (!is_dir($saveDir)) {
            @mkdir($saveDir, 0777, true);
            @chmod($saveDir, 0777);
        }

        $method = $_SERVER['REQUEST_METHOD'];
        if ($method != 'POST') {
            die(json_encode(array('code'=> '4444', 'message' => '上传方式错误', 'data' => '')));
        }

        // 文件名，扩张名方法里补充完整
        $filename = Fuse_Tool::getRandStr(10);

        if (!isset($_POST['uuid'])) {
            $result = $this->uploader->handleUpload($saveDir, $filename);
            if (isset($result['error'])) {
                die(json_encode(array('code'=> '5555', 'message' => $result['error'])));
            }

            $result['code'] = '0000';
            $result['uploadName'] = $this->uploader->getUploadName();
            @chmod($saveDir . '/' . $result['uploadName'], 0777);
            $result['uploadFile'] = $this->homeDir . $fileDir . '/' . $result['name'];

            // 保存文件
            $this->saveFileAction($result, $fileType, $projectNo, $taskNo);
        } else if (isset($_POST['uuid'])) {
            $file = Fuse_Request::getFormatVar($this->params, 'uuid');
            $file = Fuse_Tool::strToUtf8($file);
            if (strpos($saveDir, '.') !== false || strpos($saveDir, '..') !== false) {
                die(json_encode(array('code'=> '2222', 'message' => '文件非法', 'uuid' => $file)));
            }

            // 判断越权
            $keyExists = false;
            if ($fileKey != '') {
                $keyList = explode(',', $fileKey);
                foreach ($keyList as $key) {
                    $key = Fuse_Tool::strToUtf8($key);
                    if (strpos($key, $file) == FALSE) {
                        $keyExists = true;
                        break;
                    }
                }

                if (!$keyExists) {
                    die(json_encode(array('code'=> '3333', 'message' => '文件不存在')));
                }
            }

            $file = str_replace('/', '', $file);
            $file1 = iconv('UTF-8', 'GBK', $file);
            $delDir = $saveDir . $file1;
            $result = $this->uploader->handleDelete($delDir);
            if (isset($result['error'])) {
                die(json_encode(array('code'=> '4444', 'message' => '删除失败')));
            }
            $result['code'] = '0000';
            echo json_encode($result);
        } else {
            header('HTTP/1.0 405 Method Not Allowed');
        }
        exit();
    }

    /**
     * 保存文件
     */
    private function saveFileAction($result, $fileType, $projectNo, $taskNo = '')
    {
        $type = $this->modelUpload->getFileTypeByName($fileType);

        $object = array(
            'type' => $type,
            'company_id' => $this->companyId,
            'project_no' => $projectNo,
            'task_no' => $taskNo,
            'filename_en'  => $result['name'],
            'filename' => $result['uploadName'],
            'file_url' => $result['uploadFile'],
            'file_size' => $result['size'],
            'create_time' => Config_App::getTime(),
            'update_time' => Config_App::getTime(),
            'ip' => Config_App::getIP(),
            'valid' => 1,
            'status' => 0
        );

        $returnId = $this->modelUpload->store($this->modelUpload->getTableFileName(), $object);

        if (!$returnId) {
            die(json_encode(array('code'=> '1001', 'message' => '保存文件记录失败')));
        }

        die(json_encode($result));
    }
}
