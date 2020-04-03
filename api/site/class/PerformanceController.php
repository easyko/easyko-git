<?php
/**
 *
 * Controller for Performance
 *
 * @desc	绩效考核
 * @author	jerry.cao (caowlong163@163.com)
 * $date    2019-07-27 11:27:52
 */

class PerformanceController extends CommonController
{
	/**
	 * Constructor
	 *
	 * @params	array	Controller configuration array
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);
		parent::checkLoginValid();

		$this->registerTask('index', 'index');
		$this->registerTask('update', 'update');

		$this->languageId = Fuse_Cookie::getInstance()->language_id;
		$this->languageId = !in_array($this->languageId, array('1', '2')) ? '1' : $this->languageId;

		$this->userId = Fuse_Cookie::getInstance()->user_id;
		$this->roleId = Fuse_Cookie::getInstance()->role_id;

		$this->fileDir = Config_App::rootdir() . '/weekly_report/' . $this->userId . '/';

		// 配置文件
		include_once(dirname(__file__) . '/config.php');

		// 任务类型
		$this->typeList = $typeList;

		// 项目状态
		$this->projectStatList = $projectStatList;

		$this->title = '绩效考核';
	}

	/**
	 * 列表
	 */
	public function index()
	{
		$userId = Fuse_Request::getVar('user_id');
		$date   = Fuse_Request::getVar('date');
		$size   = intval(Fuse_Request::getVar('size'));

        $page = Fuse_Request::getVar('page');
        if (empty($page)) { $page = 1; }
		$where = ' 1 ';
		$perpage = !empty($size) ? $size : 10;

		$dateList = array();
		$startDateList = strtotime('2016-03');
		$endDateList = strtotime('-1 month', time());
		while ($startDateList < $endDateList) {
			$startDateList = date('Y-m', strtotime('+1 month', $startDateList));
			$str2 = str_replace('-' ,'年', $startDateList) . '月';
			$dateList[$startDateList] = $str2;
			$startDateList = strtotime($startDateList);
		}

		$model = $this->createModel('Model_Performance', dirname( __FILE__ ));

		$model1 = $this->createModel('Model_Member', dirname( __FILE__ ));
		$userList = $model1->getMemberList('3');

		if ($userId != '') {
			$where .= " AND pe.`user_id` = '{$userId}'";
		} else if (!empty($userList)) {
			$where .= " AND pe.`user_id` = '{$userList['0']['user_id']}'";
		}

		if ($date != '') {
			$where .= " AND LEFT(pe.`finished_time`, 7) = '{$date}'";
		} else {
			$date = date('Y-m');
			$where .= " AND LEFT(pe.`finished_time`, 7) = '{$date}'";
		}

		if (isset($_GET['export'])) {
			$itemList = $model->getList(0, 0, $where, $this->typeList, false, true);//print_r($itemList);die;
			$this->export($itemList);
		}

		$totalitems = $model->getTotal($where);
		$totalPage = ceil($totalitems / $perpage);
		if ($page > $totalPage) $page = $totalPage;

		$paginator = new Fuse_Paginator($totalitems, $page, $perpage, 10);
		$limit     = $paginator->getLimit();
		// $itemList  = $model->getList($limit['start'], $limit['offset'], $where, $this->typeList);
		$itemList  = $model->getList(0, 0, $where, $this->typeList);
		$totalList = $model->getList(0, 0, $where, $this->typeList, true);

		$html = 'performance_list.html';
        
        $itemTitle = array(
			'username'     => '姓名',
			'projectNo'    => '项目编号',
            'projectName'  => '项目名称',
            'jobNo'		   => '工单号',
            'type'		   => '任务类型',
			'startTime'    => '计划开始时间',
            'endTime'  	   => '计划结束时间',
            'finishedTime' => '实际完成时间',
            'attachment'   => '提交文件',
            'workUnit' 	   => '工作单元',
            'plan_score'   => '计划分值',
            'realScore'    => '实际分值'
        );
        
		$data = array(
			'pageInfo' => array(
				'page'  => $page,
				'total' => $totalPage,
				'size'  => $perpage
			),
			'itemTitle'   => $itemTitle,
			'itemList' 	  => $itemList,
			'dateList'    => $dateList,
			'userList'    => $userList,
			'totalList'   => $totalList,
			'date' 		  => $date,
			'roleId' 	  => $this->roleId,
			'userId' 	  => $userId,
			'projectStat' => $this->projectStatList
        );
        
        die(json_encode(array('code'=> '0000', 'message' => '成功', 'data' => $data)));
	}

	public function update()
	{
		$jobNo 	   = Fuse_Request::getVar('job_no');
		$realScore = Fuse_Request::getVar('real_score');

		if ($realScore == '') {
			die(json_encode(array('code'=> '1111', 'message' => '请填写实际分值', 'data' => '')));
		}

		if (!is_numeric($realScore)) {
			die(json_encode(array('code'=> '2222', 'message' => '实际分值请填写数字', 'data' => '')));
		}

		$realScore = $realScore * 1;

		$model = $this->createModel('Model_Performance', dirname( __FILE__ ));
		$row = $model->getRowByJobNo($jobNo);
		if ($row['real_score'] == $realScore) {
			die(json_encode(array('code'=> '0000', 'message' => '编辑成功', 'data' => '')));
		}

		$object = array(
			'real_score' => $realScore
		);
		$sql = " `job_no` = '{$jobNo}' ";
		if (!$model->update('project_exec_users', $object, $sql)) {
			die(json_encode(array('code'=> '3333', 'message' => '编辑失败', 'data' => '')));
		}

		die(json_encode(array('code'=> '0000', 'message' => '编辑成功', 'data' => '')));
	}

	public function export($itemList)
	{
		ini_set('memory_limit', '500M');
		set_time_limit(0);

		include_once('Excel/PHPExcel.php');

		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getActiveSheet()->setCellValue('A1', '姓名');
		$objPHPExcel->getActiveSheet()->setCellValue('B1', '项目编号');
		$objPHPExcel->getActiveSheet()->setCellValue('C1', '项目名称');
		$objPHPExcel->getActiveSheet()->setCellValue('D1', '工单号');
		$objPHPExcel->getActiveSheet()->setCellValue('E1', '任务类型');
		$objPHPExcel->getActiveSheet()->setCellValue('F1', '计划开始时间');
		$objPHPExcel->getActiveSheet()->setCellValue('G1', '计划结束时间');
		$objPHPExcel->getActiveSheet()->setCellValue('H1', '实际完成时间');
		$objPHPExcel->getActiveSheet()->setCellValue('I1', '工作单元');
		$objPHPExcel->getActiveSheet()->setCellValue('J1', '计划分值');
		$objPHPExcel->getActiveSheet()->setCellValue('K1', '实际分值');

		// 加粗
		$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('E1')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('F1')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('G1')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('H1')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('I1')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('J1')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('K1')->getFont()->setBold(true);

		$objPHPExcel->getActiveSheet()->setTitle($this->title);
		$objPHPExcel->setActiveSheetIndex(0);

		$objPHPExcel->getActiveSheet()->setCellValue('A1', '姓名');
		$objPHPExcel->getActiveSheet()->setCellValue('B1', '项目编号');
		$objPHPExcel->getActiveSheet()->setCellValue('C1', '项目名称');
		$objPHPExcel->getActiveSheet()->setCellValue('D1', '工单号');
		$objPHPExcel->getActiveSheet()->setCellValue('E1', '任务类型');
		$objPHPExcel->getActiveSheet()->setCellValue('F1', '计划开始时间');
		$objPHPExcel->getActiveSheet()->setCellValue('G1', '计划结束时间');
		$objPHPExcel->getActiveSheet()->setCellValue('H1', '实际完成时间');
		$objPHPExcel->getActiveSheet()->setCellValue('I1', '工作单元');
		$objPHPExcel->getActiveSheet()->setCellValue('J1', '计划分值');
		$objPHPExcel->getActiveSheet()->setCellValue('K1', '实际分值');

		foreach ($itemList['list'] as $key => $info) {
			$row = $key + 2;

			$info['start_time'] = !empty($info['start_time']) ? $info['start_time'] : '';
			$info['end_time']   = !empty($info['end_time']) ? $info['end_time'] : '';

			$objPHPExcel->getActiveSheet()->setCellValue('A'.$row, $info['username']);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$row, $info['project_no']);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$row, $info['project_name']);
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$row, $info['job_no']);
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$row, $info['type']);
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$row, $info['start_time']);
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$row, $info['end_time']);
			$objPHPExcel->getActiveSheet()->setCellValue('H'.$row, $info['finished_time']);
			$objPHPExcel->getActiveSheet()->setCellValue('I'.$row, $info['work_unit']);
			$objPHPExcel->getActiveSheet()->setCellValue('J'.$row, $info['plan_score']);
			$objPHPExcel->getActiveSheet()->setCellValue('K'.$row, $info['real_score']);
		}

		// 保存
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		// Excel5=>.xls、Excel2007=>xlsx
		$outputFileName = $this->title . '-' . date('Ymd') . '.xls';
		header('Content-type:application/vnd.ms-excel');
		header('Content-Disposition:attachment;filename=' . $outputFileName);
		// $objWriter->save($outputFileName); // 保存到指定目录
		$objWriter->save('php://output');	  // 保存到浏览器输出
	}
}
