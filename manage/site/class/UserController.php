<?php
/**
 *
 * Controller for User
 *
 * @desc	执行人员
 * @author	jerry.cao (caowlong163@163.com)
 * $date    2016-03-30 22:59:45
 */

@session_start();

class UserController extends CommonController
{
	private $url = 'user.php';

	/**
	 * Constructor
	 *
	 * @params	array	Controller configuration array
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);

		$this->userId   = Fuse_Cookie::getInstance()->user_id;
		$this->roleId   = Fuse_Cookie::getInstance()->role_id;
		$this->username = Fuse_Cookie::getInstance()->username;

		if (empty($this->userId) || empty($this->roleId)) {
			Fuse_Response::redirect('/');
		}

		$this->registerTask('index', 'index');
		$this->registerTask('upload', 'upload');
		$this->registerTask('finished', 'finished');
		$this->registerTask('read', 'read');
		$this->registerTask('completed', 'completed');

		// 配置文件
		include_once(dirname(__file__) . '/config.php');

		// 任务类型
		$this->typeList = $typeList;
	}

	/**
	 * 人员列表
	 */
	public function index()
	{
		// Fuse_Cookie::getInstance()->userList = '';
		$_SESSION['userList'] = '';
		$model = $this->createModel('Model_User', dirname( __FILE__ ));

		$itemList = $model->getList($this->userId, $this->typeList);
        
		$data = array(
			'pageInfo' => array(
				'page'  => $page,
				'total' => $totalPage,
				'size'  => $perpage
			),
			'itemList' => $itemList
        );
        
        die(json_encode(array('code'=> '0000', 'message' => '成功', 'data' => $data)));
	}

	public function upload()
	{
		set_time_limit(0);

		$jobNo = Fuse_Request::getVar('no');
		if (empty($jobNo)) {
			die(json_encode(array('success'=> false, 'msg' => '工单号参数丢失')));
		}

		$model = new Fuse_Model();
		$info = $model->getRow("SELECT pl.`project_no` FROM `project_exec_users` as peu
								LEFT JOIN `project_list` as pl
								ON peu.`project_id` = pl.`project_id`
								WHERE peu.`user_id` = '{$this->userId}'
								AND peu.`job_no` = '{$jobNo}'");
		if (empty($info)) {
			die(json_encode(array('success'=> false, 'msg' => '工单号参数错误')));
		}

		$rootDir = Config_App::rootdir();
		$saveDir = $rootDir . '/' . $info['project_no'] . '/' . $jobNo;
		if (!is_dir($saveDir)) {
			@mkdir($saveDir, 0777, true);
			@chmod($saveDir, 0777);
		}

		require_once "handler.php";
		$uploader = new UploadHandler();
		$uploader->sizeLimit = 1000 * (1024 * 1024);
		$uploader->inputName = "qqfile";

		$method = $_SERVER["REQUEST_METHOD"];
		if ($method == "POST") {
			$result = $uploader->handleUpload($saveDir);
			$result["uploadName"] = $uploader->getUploadName();
			//$this->saveAttach($result["uploadName"]);
			echo json_encode($result);
		} else if ($method == "DELETE") {
			$file = Fuse_Request::getVar('uuid');
			$file = str_replace('/', '', $file);
			$file1 = iconv('UTF-8', 'GBK', $file);
			$delDir = $saveDir . '/' . $file1;
			$result = $uploader->handleDelete($delDir);
			//if ($result['success']) {
			//	$this->delAttach($file);
			//}
			echo json_encode($result);
		} else {
			header("HTTP/1.0 405 Method Not Allowed");
		}
		exit();
	}

	private function saveAttach($file)
	{
		/*$list = Fuse_Cookie::getInstance()->userList;
		if (!empty($list)) {
			$list = unserialize($list);
			$list[] = $file;
		} else {
			$list = array($file);
		}

		$list = array_unique($list);
		Fuse_Cookie::getInstance()->userList = serialize($list);*/


		if (!empty($_SESSION['userList'])) {
			$_SESSION['userList'][] = $file;
		} else {
			$_SESSION['userList'] = array($file);
		}
	}

	private function delAttach($file)
	{
		/*$list = Fuse_Cookie::getInstance()->userList;
		if (!empty($list)) {
			$list = unserialize($list);
			$newFile = array();
			foreach ($list as $value) {
				if ($value != $file) {
					$newFile[] = $value;
				}
			}
			unset($list);
			Fuse_Cookie::getInstance()->userList = serialize($newFile);
		}*/

		if (!empty($_SESSION['userList'])) {
			$newFile = array();
			foreach ($_SESSION['userList'] as $value) {
				if ($value != $file) {
					$newFile[] = $value;
				}
			}
			unset($_SESSION['userList']);
			$_SESSION['userList'] = $newFile;
		}
	}

	public function finished()
	{
		$jobNo = Fuse_Request::getVar('job_no', 'post');
		$formhash = Fuse_Request::getVar('formhash', 'post');

		$checkFormhash = Config_App::formhash('user');
		if ($checkFormhash != $formhash) {
			die(json_encode(array('code'=> '1111', 'message' => '非法提交', 'data' => '')));
		}

		$model = new Fuse_Model();
		$info = $model->getRow("SELECT peu.`execuser_id`,peu.`job_no`,pl.`project_no` FROM `project_exec_users` as peu
								LEFT JOIN `project_list` as pl
								ON peu.`project_id` = pl.`project_id`
								WHERE `user_id` = '{$this->userId}'
								AND `job_no` = '{$jobNo}'");
		if (empty($info)) {
			die(json_encode(array('code'=> '2222', 'message' => '参数错误', 'data' => '')));
		}

		include(dirname(__file__) . '/config.php');

		$pNo = $info['project_no'];
		$jNo = $info['job_no'];

		// 检测官网、非官网文件是否上传
		//if ($webUrl == $_SERVER['HTTP_HOST']) {
		//	$checkDir = "D:\\projects\\" . $pNo . "\\" . $jNo;
		//} else {
			$rootDir = Config_App::rootdir();
			$checkDir = $rootDir . '/' . $pNo . '/' . $jNo;
		//}

		$userList = $this->getDir($checkDir);
		if (empty($userList)){
			die(json_encode(array('code'=> '3333', 'message' => '请上传文件', 'data' => '')));
		}

		// $userList = Fuse_Cookie::getInstance()->userList;
		/*$userList = $_SESSION['userList'];
		if (empty($userList)){
			die(json_encode(array('status'=> 'FILE_ERROR', 'msg' => '请上传文件')));
		}*/

		$object = array(
			'attachment'	=> serialize($userList),
			'finished_time' => Config_App::getTime()
		);
		$model->update('project_exec_users', $object, " `job_no` = '{$jobNo}' ");

		// Fuse_Cookie::getInstance()->userList = '';
		$_SESSION['userList'] = '';
		
		die(json_encode(array('code'=> '0000', 'message' => '提交成功', 'data' => $data)));
	}

	/**
	 * 判断文件夹下有没有文件
	 *
	 */
	public function dirIsEmpty($dir){
		if ($handle = opendir("$dir")) {
			while($item = readdir($handle)){
				if ($item != "." && $item != "..")
				return false;
			}
		}
		return true;
	}

	/**
	 * 读取文件夹文件
	 *
	 */
	public function getDir($dir)
	{
		$files = array();
		 if ($handle = opendir($dir)) {
			 while (($file = readdir($handle)) !== false) {
				 if ($file != ".." && $file != ".") {
					 if (is_dir($dir . "/" . $file)) {
						 $files[$file] = scandir($dir . "/" . $file);
					 } else {
						 $file = iconv('GBK', 'UTF-8', $file);
						 $files[] = $file;
					 }
				 }
			 }

			 closedir($handle);
			 return $files;
		 }
	}

	public function read()
	{
		$jobNo = Fuse_Request::getVar('job_no', 'post');
		$formhash = Fuse_Request::getVar('formhash', 'post');

		if ($jobNo == '') {
			die(json_encode(array('code'=> '1111', 'message' => '工单号丢失', 'data' => '')));
		}

		$checkFormhash = Config_App::formhash('user');
		if ($checkFormhash != $formhash) {
			die(json_encode(array('code'=> '2222', 'message' => '非法提交', 'data' => '')));
		}

		// $jobNoCookieId = 'jobNo' . $jobNo;
		// $jobNoCookieName = Fuse_Cookie::getInstance()->$jobNoCookieId;
		// if (!isset($jobNoCookieName) || empty($jobNoCookieName)) {
			$model = $this->createModel('Model_User', dirname( __FILE__ ));
			$model->setReadByJobNo($jobNo, $this->userId);
		// }

		// Fuse_Cookie::getInstance()->$jobNoCookieId = '1';
		
		die(json_encode(array('code'=> '0000', 'message' => '成功', 'data' => '')));
	}

	public function completed()
	{
		$date = Fuse_Request::getVar('date', 'get');
        $page = Fuse_Request::getVar('p', 'get');
        if(empty($page)){$page=1;}

		$dateList = array();
		$startDateList = strtotime('2016-03');
		$endDateList = strtotime('-1 month', time());
		while ($startDateList < $endDateList) {
			$startDateList = date('Y-m', strtotime('+1 month', $startDateList));
			$str2 = str_replace('-' ,'年', $startDateList) . '日';
			$dateList[$startDateList] = $str2;
			$startDateList = strtotime($startDateList);
		}
		arsort($dateList);

		$where = " ul.`user_id` = '{$this->userId}'";
		$perpage = 20;

		if ($date == '') {
			$date = date('Y-m');
		}

		// 显示当前指定月的所有完成任务、所有时间的未完成任务
		$where .= " AND (LEFT(pe.`finished_time`, 7) = '{$date}' OR pe.`finished_time` = '0000-00-00 00:00:00')";

		$model = $this->createModel('Model_Performance', dirname( __FILE__ ));

		$totalitems = $model->getTotal($where);
		$totalPage = ceil($totalitems / $perpage);
		if ($page > $totalPage) $page = $totalPage;

		$paginator = new Fuse_Paginator($totalitems, $page, $perpage, 10);
		$limit     = $paginator->getLimit();
		// $itemList  = $model->getList($limit['start'], $limit['offset'], $where, $this->typeList);
		$itemList  = $model->getList(0, 0, $where, $this->typeList);

		$itemTitle = array(
			'projectNo'    => '项目编号',
			'projectName'  => '项目名称',
			'jobNo' 	   => '工单号',
			'type'    	   => '任务类型',
			'startTime'    => '计划开始时间',
			'endTime' 	   => '计划结束时间',
			'finishedTime' => '实际完成时间',
			'attachment'   => '提交文件',
			'workUnit' 	   => '工作单元',
			'planScore'    => '计划分值',
			'realScore'    => '实际分值'
		);
        
		$data = array(
			'pageInfo' => array(
				'page'  => $page,
				'total' => $totalPage,
				'size'  => $perpage
			),
			'itemTitle' => $itemTitle,
			'itemList'  => $itemList,
			'dateList' 	=> $dateList
        );
        
        die(json_encode(array('code'=> '0000', 'message' => '成功', 'data' => $data)));
	}
}
?>
