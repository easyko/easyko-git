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

		// 文件配置类型
		$this->fileUploadList = $fileUploadList;
		
		$this->modelProject = $this->createModel('Model_Project', dirname( __FILE__ ));
		
		// 任务类型
		$this->modelProject->setProjectTypeList($projectTypeList);
		
		// 项目状态
		$this->modelProject->setProjectStatusList($projectStatusList);
		
		$this->tempDirName = '/temp';
		$this->rootDir = Config_App::webdir();
		
		// 上传插件
		require_once dirname(__FILE__) . '/upload/handler.php';
		$this->uploader = new UploadHandler();
		$this->uploader->sizeLimit = 2 * (1024 * 1024);
		$this->uploader->inputName = 'qqfile';
		$this->uploader->notAllowedExtensions = array('exe', 'js', 'css', 'sql');
	}

	private function getFileTypeByName($name)
	{
		$type = 0;
		// 类型 1项目 2合同 3提案资料 4会议纪要 5任务单
		switch ($name) {
			case 'projectFile':
				$type = 1;
				break;
			case 'projectContractFile':
				$type = 2;
				break;
			case 'projectProposalFile':
				$type = 3;
				break;
			case 'projectMeetingNoteFile':
				$type = 4;
				break;
			case 'projectTaskFile':
				$type = 5;
				break;
		}
		
		return $type;
	}
	
	private function checkFileTypeValid($name)
	{
		if ($name == '') {
			return false;
		}
		
		return in_array($name, $this->fileUploadList);
	}

	/**
	 * 上传文件
	 */
	public function index()
	{
		$projectNo = 'M1910GV2zYTgR';//Fuse_Request::getFormatVar($this->params, 'projectNo');
		$taskNo = '10191023';//Fuse_Request::getFormatVar($this->params, 'taskNo');
		$fileType = Fuse_Request::getFormatVar($this->params, 'type');

		if (empty($projectNo)) {
			die(json_encode(array('code'=> '1111', 'message' => '参数项目编号缺失', 'data' => '')));
		}

		// 项目编号和任务单号都不为空，看成上传任务单号文件
		if ($taskNo != '') {
			$fileType = 'projectTaskFile';
		}

		if (!$this->checkFileTypeValid($fileType)) {
			die(json_encode(array('code'=> '2222', 'message' => '非指定模块类型文件', 'data' => '')));
		}

		// 查询项目信息
		/*$projectTaskList = $this->modelProject->getProjectTaskList($this->companyId, $projectNo, $taskNo);
		print_r($projectTaskList);die;
		if (empty($projectTaskList)) {
			die(json_encode(array('code'=> '3333', 'message' => '项目信息查询失败', 'data' => '')));
		}*/

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
			$result['uploadFile'] = Config_App::homeurl() . $fileDir . '/' . $result['name'];

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
			$result = $uploader->handleDelete($delDir);
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
		$model = $this->createModel('Model_Upload', dirname( __FILE__ ));
		
		$type = $this->getFileTypeByName($fileType);
		
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

		$returnId = $model->store($model->getTableFileName(), $object);
		
		if (!$returnId) {
			die(json_encode(array('code'=> '1001', 'message' => '保存文件记录失败')));
		}
		
		die(json_encode($result));
	}
}
