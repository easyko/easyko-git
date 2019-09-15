<?php
/**
 *
 * Controller for ProjectReport
 *
 * @desc	项目总结
 * @author	jerry.cao (caowlong163@163.com)
 * $date    2016-06-16 21:01:45
 */

@session_start();

class ProjectReportController extends CommonController
{
	/**
	 * Constructor
	 *
	 * @params	array	Controller configuration array
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);

		$this->registerTask('index', 'index');
		$this->registerTask('del', 'del');
		$this->registerTask('upload', 'upload');

		$this->languageId = Fuse_Cookie::getInstance()->language_id;
		$this->languageId = !in_array($this->languageId, array('1', '2')) ? '1' : $this->languageId;

		$this->userId = Fuse_Cookie::getInstance()->user_id;
		$this->roleId = Fuse_Cookie::getInstance()->role_id;

		$this->fileDir = Config_App::rootdir() . '/work_summary/' . $this->userId . '/';
	}

	/**
	 * 列表
	 */
	public function index()
	{
		$userId    = Fuse_Request::getVar('user_id');
		$startDate = Fuse_Request::getVar('start_date');
		$endDate   = Fuse_Request::getVar('end_date');

        $page = Fuse_Request::getVar('page');
        if(empty($page)){$page=1;}

		$model = $this->createModel('Model_Member', dirname( __FILE__ ));
		$managerList = $model->getMemberList('2');

		$model = $this->createModel('Model_ProjectReport', dirname( __FILE__ ));

		$where = ' 1 ';
		$perpage = 20;

		if (in_array($this->roleId, array('0', '1'))) {
			if ($userId != '') {
				$where .= " AND w.`user_id` = '{$userId}'";
			}

			if ($startDate != '' && $endDate != '') {
				$where .= " AND (w.`time` >= '{$startDate} 00:00:00' AND w.`time` <= '{$endDate} 23:59:59')";
			}
		}

		$totalitems = $model->getTotal($where);
		$totalPage = ceil($totalitems / $perpage);
		if ($page > $totalPage) $page = $totalPage;

		$paginator = new Fuse_Paginator($totalitems, $page, $perpage, 10);
		$limit     = $paginator->getLimit();
		$itemList  = $model->getList($limit['start'], $limit['offset'], $where);

		$itemTitle = array(
			'fileName' => '项目总结文档（点击下载查看）'
		);
        if ($this->roleId != 2) {
			$itemTitle['managerName'] = '项目经理';
		}
        $itemTitle['date'] = '上传时间';
        
		$data = array(
			'pageInfo' => array(
				'page'  => $page,
				'total' => $totalPage,
				'size'  => $perpage
			),
			'itemTitle' 	  => $itemTitle,
			'itemList' 		  => $itemList,
			'managerList' 	  => $managerList // 项目经理
        );
        
        die(json_encode(array('code'=> '0000', 'message' => '成功', 'data' => $data)));
	}

	public function upload()
	{
		if (!is_dir($this->fileDir)) {
			@mkdir($this->fileDir, 0777, true);
			@chmod($this->fileDir, 0777);
		}

		require_once "handler.php";
		$uploader = new UploadHandler();
		$uploader->sizeLimit = 1000 * (1024 * 1024);
		$uploader->inputName = "qqfile";

		$method = $_SERVER["REQUEST_METHOD"];
		if ($method == "POST") {
			$result = $uploader->handleUpload($this->fileDir);
			if ($result["success"]) {
				$result["uploadName"] = $uploader->getUploadName();
				@chmod($this->fileDir . '/' . $result["uploadName"], 0777);

				$model = new Fuse_Model();
				$object = array(
					'name' 	  => $result["uploadName"],
					'user_id' => $this->userId,
					'ip'   	  => Config_App::getIP(),
					'time' 	  => Config_App::getTime()
				);
				if (!$model->store('project_report', $object)) {
					$result["uploadName"] = iconv("UTF-8", "GBK", $result["uploadName"]);
					$result = $uploader->handleDelete($this->fileDir . '/' . $result["uploadName"]);
					echo json_encode($result);
					exit();
				}
			}
			echo json_encode($result);
		} else {
			header("HTTP/1.0 405 Method Not Allowed");
		}
		exit();
	}

	/**
	 * 项目总结文档删除
	 */
	function del()
	{
		$reportId  = intval(Fuse_Request::getVar('report_id'));
		$userId    = intval(Fuse_Request::getVar('user_id'));
		$startDate = Fuse_Request::getVar('start_date');
		$endDate   = Fuse_Request::getVar('end_date');

		$model = $this->createModel('Model_ProjectReport', dirname( __FILE__ ));
		$list = $model->getReportById($reportId);
		if (empty($list)) {
			die(json_encode(array('code'=> '1111', 'message' => '参数错误，删除失败', 'data' => '')));
		}

		$model->delReportById($reportId);

		$list['name'] = iconv("UTF-8", "GBK", $list['name']);
		Fuse_FileSystem::delete($this->fileDir . $list['name']);

		die(json_encode(array('code'=> '0000', 'message' => '删除成功', 'data' => array('url' => $url))));
	}
}
