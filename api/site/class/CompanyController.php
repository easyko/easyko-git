<?php
/**
 *
 * Controller for Company
 *
 * @desc	企业相关
 * @author	jerry.cao (caowlong163@163.com)
 * $date    2019-07-27 11:27:52
 */

class CompanyController extends CommonController
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

		$this->registerTask('simpleInfo', 'simpleInfo');
		$this->registerTask('info', 'info');
		$this->registerTask('industry', 'industry');
		$this->registerTask('size', 'size');
		
		$this->model = $this->createModel('Model_Company', dirname( __FILE__ ));
	}

	/**
	 * 查询公司名称及有效人数
	 */
	public function simpleInfo()
	{
		$data = $this->model->getSimpleInfo($this->companyId);

		die(json_encode(array('code'=> '0000', 'message' => '成功', 'data' => $data)));
	}

	/**
	 * 企业信息
	 */
	public function info()
	{
		$company = $this->model->getCompanyInfo($this->companyId);
		if (empty($company)) {
			return array();
		}
		
		// 查询产品信息
		$modelProduct = $this->createModel('Model_Product', dirname( __FILE__ ));
		$product = $modelProduct->getProductById($this->companyId);
		if (empty($product)) {
			return array();
		}
		
		// 可用账户
		$effectAccount = 0;
		$effectAccount += $product['max_people'];
		
		// 订单
		$modelOrder = $this->createModel('Model_Order', dirname( __FILE__ ));
		$orderInfo = $modelOrder->getOrderByCompanyId($this->companyId, 1);
		if (empty($orderInfo)) {
			return array();
		}
		if (isset($orderInfo['extra_nums']) && $orderInfo['extra_nums'] != '') {
			$effectAccount += intval($orderInfo['extra_nums']);
		}
		
		// 查询有效注册用户数量
		$modelMember = $this->createModel('Model_Member', dirname( __FILE__ ));
		$memberList = $modelMember->getMemberListByCompanyId($this->companyId, 1);
		$onUse = count($memberList);
		$waitUse = $effectAccount - $onUse;
		$waitUse = $waitUse < 0 ? 0 : $waitUse;
		$accountProcess = "已开通使用{$onUse}个，未开通{$waitUse}个";
		
		$buyDate = isset($orderInfo['start_date']) ? substr($orderInfo['start_date'], 0, 10) : '--';
		$limitDate = isset($orderInfo['end_date']) ? substr($orderInfo['end_date'], 0, 10) : '--';
		
		$isUsedDays = Fuse_Tool::getDaysBetweenTwoDays($orderInfo['start_date'], date('Y-m-d H:i:s')) + 1;
		$remainDays = Fuse_Tool::getDaysBetweenTwoDays($orderInfo['end_date'], date('Y-m-d H:i:s'));
		
		$data = array(
			'currVersion' 	 => $product['name'], 	     // 前版本
			'effectAccount'  => $effectAccount . '个',   // 可用账户
			'accountProcess' => $accountProcess, 	     // 账户使用情况
			'buyDate' 		 => $buyDate, 			     // 购买日期
			'limitDate' 	 => $limitDate, 		     // 账户有效期
			'isUsedDays' 	 => $isUsedDays . '天',      // 已使用
			'remainDays' 	 => $remainDays . '天',      // 剩余天数
			'sizeId' 		 => $company['size_id'],     // 规模ID
			'industryId' 	 => $company['industry_id'], // 企业类型ID
			'address' 		 => $company['address'] 	 // 所在地（总部）
		);

        die(json_encode(array('code'=> '0000', 'message' => '成功', 'data' => $data)));
	}
	
	/**
	 * 企业行业
	 */
	public function industry()
	{
		$data = $this->model->getIndustryList();
		
		die(json_encode(array('code'=> '0000', 'message' => '成功', 'data' => $data)));
	}
	
	/**
	 * 企业规模
	 */
	public function size()
	{
		$data = $this->model->getSizeList();
		
		die(json_encode(array('code'=> '0000', 'message' => '成功', 'data' => $data)));
	}
}
