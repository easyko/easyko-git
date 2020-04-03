<?php
/**
 *
 * Controller for Financial
 *
 * @desc	财务相关
 * @author	jerry.cao (caowlong163@163.com)
 * $date    2019-07-27 11:27:52
 */

class FinancialController extends CommonController
{
	private $locationRegular = '/^(SH|GZ|LD)\d{7}$/';

	/**
	 * Constructor
	 *
	 * @params	array	Controller configuration array
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);
		parent::checkLoginValid();

		$this->registerTask('financialReport', 'financialReport');
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
