<?php
/**
 *
 * Controller for Statistics
 *
 * @desc	统计相关
 * @author	jerry.cao (caowlong163@163.com)
 * $date    2019-08-01 23:05:45
 */

class StatisticsController extends CommonController
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

		$this->registerTask('projectStatistics', 'projectStatistics');
		$this->registerTask('projectStatisticsTotal', 'projectStatisticsTotal');
		$this->registerTask('taskStatisticsTotal', 'taskStatisticsTotal');
		$this->registerTask('taskStatistics', 'taskStatistics');
		$this->registerTask('progressStatistics', 'progressStatistics');
		$this->registerTask('financialReportTotal', 'financialReportTotal');
		$this->registerTask('financialReport', 'financialReport');

		$this->modelProject = $this->createModel('Model_Project', dirname( __FILE__ ));
		
		$this->startDate = Fuse_Request::getFormatVar($this->params, 'startDate');
		$this->endDate   = Fuse_Request::getFormatVar($this->params, 'endDate');
		if (empty($this->startDate)) {
			$this->startDate = Config_App::getDate();
		}
		if (empty($this->endDate)) {
			$this->endDate = date('Y-m-d', time() + 30 * 60 * 60 * 24);
		}
	}

	/**
	 * 项目统计数量
	 */
	public function projectStatisticsTotal()
	{
		$where = " `company_id` = '{$this->companyId}' AND `valid` = '1' 
				AND `time` > '{$this->startDate}' AND `time` <= '{$this->endDate}' ";
        $statisticsList = $this->modelProject->projectStatisticsTotal($where);
        $data = array(
			'statisticsList' => $statisticsList, // 统计列表
        );
        
        die(json_encode(array('code'=> '0000', 'message' => '成功', 'data' => $data)));
	}

	/**
	 * 项目统计
	 */
	public function projectStatistics()
	{
		$size = Fuse_Request::getFormatVar($this->params, 'size', '1');

        $page = Fuse_Request::getFormatVar($this->params, 'page', '1');
        if (empty($page)) { $page = 1; }
		$where = " `company_id` = '{$this->companyId}' AND `valid` = '1' 
				AND `time` > '{$this->startDate}' AND `time` <= '{$this->endDate}' ";
		$perpage = !empty($size) ? $size : 10;

		$totalitems = $this->modelProject->getTotalProject($where);
		$totalPage = ceil($totalitems / $perpage);
		if ($page > $totalPage && $totalPage > 0) $page = $totalPage;

		$paginator = new Fuse_Paginator($totalitems, $page, $perpage, 10);
		$limit     = $paginator->getLimit();
		$itemList  = $this->modelProject->getListProject($limit['start'], $limit['offset'], $where);
     
        $itemTitle = array(
			'projectNo'    => '项目编号',
			'projectName'  => '项目名称',
			'customerName' => '客户',
			'managerName'  => '项目经理',
			'execUsersIds' => '执行人员',
			'createDate'   => '创建日期',
			'workDate'     => '项目时长',
			'workTime'     => '项目工时',
			'status' 	   => '项目状态'
		);
        
         $data = array(
			'pageInfo' => array(
				'page'  => $page,
				'total' => $totalPage,
				'size'  => $perpage
			),
			'itemTitle' => $itemTitle,
			'itemList'  => $itemList
        );

        die(json_encode(array('code'=> '0000', 'message' => '成功', 'data' => $data)));
	}
	

	/**
	 * 任务统计数量
	 */
	public function taskStatisticsTotal()
	{       
        $where = " `company_id` = '{$this->companyId}' AND `valid` = '1' 
				AND `time` > '{$this->startDate}' AND `time` <= '{$this->endDate}' ";

        $statisticsList = $this->modelProject->taskStatisticsTotal($where, $this->companyId);
        $data = array(
			'statisticsList' => $statisticsList, // 统计列表
        );

        die(json_encode(array('code'=> '0000', 'message' => '成功', 'data' => $data)));
	}
	
	/**
	 * 任务统计
	 */
	public function taskStatistics()
	{
        $data = array(
			'date' => '', // 日期 
			'info' => array(
				'0' => array(
					'name' => '新增',
					'value' => '', // 新增
				),
				'1' => array(
					'name' => '完成',
					'value' => ''  // 完成
				)
			)
		);
		
		$currWeekList = Fuse_Tool::getCurrWeek();
		$row = 0;
		foreach ($currWeekList as $dateStr) {
			$data['date'] .= ($row == 0 ? '' : ',') . substr($dateStr, 5);

			// 当天任务统计明细
			$where = " `company_id` = '{$this->companyId}' AND `valid` = '1' 
				AND LEFT(`time`, 10) = '{$dateStr}' ";
			$itemList = $this->modelProject->taskStatisticsTotal($where, $this->companyId);

			$data['info']['0']['value'] .= ($row == 0 ? '' : ',') . $itemList['created'];
			$data['info']['1']['value'] .= ($row == 0 ? '' : ',') . $itemList['completed'];
			
			$row++;
		}

        die(json_encode(array('code'=> '0000', 'message' => '成功', 'data' => $data)));
	}
	
	/**
	 * 成员进度统计
	 */
	public function progressStatistics()
	{        
        $size = Fuse_Request::getFormatVar($this->params, 'size', '1');

        $page = Fuse_Request::getFormatVar($this->params, 'page', '1');
        if (empty($page)) { $page = 1; }
		$where = array(
			'companyId' => $this->companyId,
			'startDate' => $this->startDate,
			'endDate'   => $this->endDate
		);
		$perpage = !empty($size) ? $size : 10;

		$totalitems = $this->modelProject->getTotalProgressStatistics($where);
		$totalPage = ceil($totalitems / $perpage);
		if ($page > $totalPage && $totalPage > 0) $page = $totalPage;

		$where['page'] = $page;
		$paginator = new Fuse_Paginator($totalitems, $page, $perpage, 10);
		$limit     = $paginator->getLimit();
		$itemList  = $this->modelProject->getListProgressStatistics($limit['start'], $limit['offset'], $where);
        
		$itemTitle = array(
			'itemNo'	   => '序号',
			'member' 	   => '成员',
			'joinNum'      => '参与项目数',
			'joiningNum'   => '进行中任务',
			'finishedNum'  => '已完成任务',
			'delayedNum'   => '已延期任务',
			'finishedRate' => '任务完成率',
			'delayedRate'  => '任务延期率'
        );
        
        $data = array(
			'pageInfo' => array(
				'page'  => $page,
				'total' => $totalPage,
				'size'  => $perpage
			),
			'itemTitle' => $itemTitle,
			'itemList'  => $itemList
        );

        die(json_encode(array('code'=> '0000', 'message' => '成功', 'data' => $data)));
	}
	
	/**
	 * 项目财报数量统计
	 */
	public function financialReportTotal()
	{
        $statisticsList = array(
			'contractTotalAmount' => '1111', // 项目合同金额
			'realIncomeAmount'     => '2222', // 项目实收金额
			'outsourceAmount'  	   => '33333' // 项目外包金额
        );
        
         $data = array(
			'statisticsList' => $statisticsList, // 统计列表
        );

        die(json_encode(array('code'=> '0000', 'message' => '成功', 'data' => $data)));
	}
	
	/**
	 * 项目财报
	 */
	public function financialReport()
	{
		$itemTitle = array(
			'projectNo' 	   => '项目编号',
			'projectName'      => '项目名称',
			'customerName'     => '客户名称',
			'startNum'  	   => '开始时间',
			'status'      	   => '项目状态',
			'workingHours'     => '项目工时',
			'contractUrl' 	   => '合同',
			'contractAmount'   => '合同金额',
			'realIncomeAmount' => '实收金额',
			'outsourceAmount'  => '外包支出'
        );
        
		$itemList = array(
			array(
				'projectNo' 	   => '123456',
				'projectName'      => '室内设计',
				'customerName'     => '小李子',
				'startNum'  	   => '2019-05-05',
				'status'      	   => '已完成',
				'workingHours'     => '120人天',
				'contractUrl' 	   => 'xx合同.doc',
				'contractAmount'   => '100,000,00',
				'realIncomeAmount' => '90,000,00',
				'outsourceAmount'  => '10,000'
			)
		);
        
         $data = array(
			'pageInfo' => array(
				'page'  => 1, // $page,
				'total' => 10, // $totalPage,
				'size'  => 10 // $perpage
			),
			'itemTitle' => $itemTitle,
			'itemList'  => $itemList
        );
        
        
        die(json_encode(array('code'=> '0000', 'message' => '成功', 'data' => $data)));
	}
}
