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

		$this->registerTask('queryInfoByName', 'queryInfoByName');
		$this->registerTask('info', 'info');
		
		$this->model = $this->createModel('Model_Company', dirname( __FILE__ ));
	}

	/**
	 * 通过企业名称查询企业编号
	 */
	public function queryInfoByName()
	{
		$companyName = Fuse_Request::getFormatVar($this->params, 'companyName');
		
		$data = array();
		if ($companyName != '') {
			$data = $this->model->getCompanyByName($companyName);
		}
		
		die(json_encode(array('code'=> '0000', 'message' => '成功', 'data' => $data)));
	}

	/**
	 * 企业信息
	 */
	public function info()
	{
		$companyNo = Fuse_Request::getFormatVar($this->params, 'companyNo');
		
		$data = $this->model->getCompanyInfo($companyNo);
		/*$data = array(
			'currVersion' => '团队版', // 当前版本
			'effectAccount' => '9个', // 可用账户
			'accountProcess' => '已开通使用7个，未开通2个', // 账户使用情况
			'buyDate' => '2019-06-24', // 购买日期
			'limitDate' => '2020-06-24', // 账户有效期
			'isUsedDays' => '45天', // 已使用
			'remainDays' => '15天', // 剩余天数
			'sizeId' => '1', // 规模ID
			'industryId' => '1', // 企业类型ID
			'address' => '上海静安' // 所在地（总部）
		);*/

        die(json_encode(array('code'=> '0000', 'message' => '成功', 'data' => $data)));
	}
}
