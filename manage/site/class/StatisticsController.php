<?php
/**
 *
 * Controller for Statistics
 *
 * @desc	统计相关
 * @author	jerry.cao (caowlong163@163.com)
 * $date    2019-08-16 23:49:45
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
		parent::__construct($config);$this->userId=1;$this->companyId=1;
		parent::checkLoginValid();

		$this->registerTask('companyStatisticsTotal', 'companyStatisticsTotal');
		$this->registerTask('companyStatistics', 'companyStatistics');
		
		$this->registerTask('memberStatisticsTotal', 'memberStatisticsTotal');
		$this->registerTask('memberStatistics', 'memberStatistics');
		
		$this->model = $this->createModel('Model_Statistics', dirname( __FILE__ ));
		
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
	 * 任务统计数量
	 */
	public function companyStatisticsTotal()
	{
		// 本周
		$currWeek = Fuse_Tool::getCurrWeekBetween();
		$currWeekWhere = " `create_time` >= '{$currWeek['start']}' AND `create_time` < '{$currWeek['end']}' ";
		$currWeekWherePayed = " `create_time` >= '{$currWeek['start']}' AND `create_time` < '{$currWeek['end']}' AND `is_payed` = '1' ";

		// 本月
		$currMonth = Fuse_Tool::getCurrMonthBetween();
		$currMonthWhere = " `create_time` >= '{$currMonth['start']}' AND `create_time` < '{$currMonth['end']}' ";
		$currMonthWherePayed = " `create_time` >= '{$currMonth['start']}' AND `create_time` < '{$currMonth['end']}' AND `is_payed` = '1' ";

		$totalWhere = " `is_payed` = '1' ";

        $statisticsList = array(
			'register' => array( // 注册企业
				array('name' => '注册企业用户总数', 'total' => $this->model->getTotalCompany()),
				array('name' => '本周新增总数', 'total' => $this->model->getTotalCompany($currWeekWhere)),
				array('name' => '本月新增总数', 'total' => $this->model->getTotalCompany($currMonthWhere))
				/*'total' 		 => $this->model->getTotalCompany(), 			   // 注册企业用户总数
				'currWeekTotal'  => $this->model->getTotalCompany($currWeekWhere), // 本周新增总数
				'currMonthTotal' => $this->model->getTotalCompany($currMonthWhere) // 本月新增总数*/
			),
			'company' => array( // 付费企业
				array('name' => '付费企业总数', 'total' => $this->model->getTotalCompany($totalWhere)),
				array('name' => '本周新增总数', 'total' => $this->model->getTotalCompany($currWeekWherePayed)),
				array('name' => '本月新增总数', 'total' => $this->model->getTotalCompany($currMonthWherePayed)),
				/*'total' 		 => 0, // 付费企业总数
				'currWeekTotal'  => 0, // 本周新增总数
				'currMonthTotal' => 0, // 本月新增总数*/
			),
			/*'product' => array( // 套餐
				array('name' => '团队版总数', 'total' => 4),
				array('name' => '企业专业版总数', 'total' => 5),
				array('name' => '企业定制版总数', 'total' => 6),
				//'groupVerTotal'   => 0, // 团队版总数
				//'editionVerTotal' => 0, // 企业专业版总数
				//'customVerTotal'  => 0  // 企业定制版总数
			)*/
			'product' => $this->model->getProdectByType()
        );

        $data = array(
			'statisticsList' => $statisticsList, // 统计列表
        );

        die(json_encode(array('code'=> '0000', 'message' => '成功', 'data' => $data)));
	}
	
	/**
	 * 任务统计
	 */
	public function companyStatistics()
	{
		// 本周日期
		$weekList = Fuse_Tool::getCurrWeek();
		
		
		$weekListStr = '';
		$weekRegisterStr = '';
		$weekPayedStr = '';
		$row = 0;
		foreach ($weekList as $value) {
			if ($row > 0) {
				$weekListStr .= ',';
			}
			$weekListStr .= $value;
			
			if ($row > 0) {
				$weekRegisterStr .= ',';
			}
			$currWeekRegisterWhere = " LEFT(`create_time`, 10) = '{$value}' ";
			$weekRegisterStr .= $this->model->getTotalCompany($currWeekRegisterWhere);
			
			if ($row > 0) {
				$weekPayedStr .= ',';
			}
			$currWeekPayedWhere = " LEFT(`create_time`, 10) = '{$value}' AND `is_payed` = '1' ";
			$weekPayedStr .= $this->model->getTotalCompany($currWeekPayedWhere);
			
			$row++;
		}
		
        $data = array(
			'date' => $weekListStr, // 日期 
			'info' => array(
				'0' => array(
					'name' => '新增注册企业',
					'value' => $weekRegisterStr,
				),
				'1' => array(
					'name' => '新增付费企业',
					'value' => $weekPayedStr
				)
			)
		);

        die(json_encode(array('code'=> '0000', 'message' => '成功', 'data' => $data)));
	}

	
	/**
	 * 注册用户数量
	 */
	public function memberStatisticsTotal() 
	{
		// 本周
		$currWeek = Fuse_Tool::getCurrWeekBetween();
		$currWeekWhere = " `create_time` >= '{$currWeek['start']}' AND `create_time` < '{$currWeek['end']}' ";

		// 本月
		$currMonth = Fuse_Tool::getCurrMonthBetween();
		$currMonthWhere = " `create_time` >= '{$currMonth['start']}' AND `create_time` < '{$currMonth['end']}' ";

        $statisticsList = array(
			array('name' => '注册用户总数', 'total' => $this->model->getTotalMember()),
			array('name' => '本周新增总数', 'total' => $this->model->getTotalMember($currWeekWhere)),
			array('name' => '本月新增总数', 'total' => $this->model->getTotalMember($currMonthWhere))
		);

        $data = array(
			'statisticsList' => $statisticsList, // 统计列表
        );

        die(json_encode(array('code'=> '0000', 'message' => '成功', 'data' => $data)));
	}

	/**
	 * 用户统计
	 */
	public function memberStatistics()
	{
		// 本周日期
		$weekList = Fuse_Tool::getCurrWeek();
		
		
		$weekListStr = '';
		$weekRegisterStr = '';
		$weekPayedStr = '';
		$row = 0;
		foreach ($weekList as $value) {
			if ($row > 0) {
				$weekListStr .= ',';
			}
			$weekListStr .= $value;
			
			if ($row > 0) {
				$weekRegisterStr .= ',';
			}
			$currWeekRegisterWhere = " LEFT(`create_time`, 10) = '{$value}' ";
			$weekRegisterStr .= $this->model->getTotalMember($currWeekRegisterWhere);
			
			if ($row > 0) {
				$weekPayedStr .= ',';
			}
			$currWeekPayedWhere = " LEFT(`create_time`, 10) = '{$value}' AND `is_payed` = '1' ";
			$weekPayedStr .= $this->model->getTotalCompanyByTotal($value, $currWeekPayedWhere);
			
			$row++;
		}
		
        $data = array(
			'date' => $weekListStr, // 日期 
			'info' => array(
				'0' => array(
					'name' => '新增注册用户',
					'value' => $weekRegisterStr,
				),
				'1' => array(
					'name' => '新增付费用户',
					'value' => $weekPayedStr
				)
			)
		);

        die(json_encode(array('code'=> '0000', 'message' => '成功', 'data' => $data)));
	}
}
