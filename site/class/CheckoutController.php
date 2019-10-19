<?php
/**
 *
 * Controller for Product
 *
 * @desc	产品
 * @author	jerry.cao (caowlong163@163.com)
 * $date    2019-05-18 23:49:31
 */
class CheckoutController extends Fuse_Controller
{
	private $url = '/product';
    protected $language = array(
        'error_illegal' => '非法提交',
        'error_tel_empty' => '手机号码为空',
        'error_telephone' => '手机号码格式错误',
        'error_password_empty' => '密码为空',
        'error_password' => '密码使用了非法字符',
        'error_login' => '手机号或密码错误',
        'error_smscode_empty' => '短信验证码为空',
        'error_smscode' => '短信验证码出错',
        'config_name' => '易酷',
        'title' => '购买'
    );

	/**
	 * Constructor
	 *
	 * @params	array	Controller configuration array
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);

		if (!Fuse_Customer::getInstanceCustomer()->isLogged()) {
			Fuse_Response::redirect('/');
		}

		$this->registerTask('index', 'index');

        $this->userId   = Fuse_Session::getInstance()->data['customer_id'];
        //$this->roleId   = Fuse_Session::getInstance()->role_id;
        $this->username = Fuse_Session::getInstance()->data['username'];

        $this->model = $this->createModel('Model_Checkout', dirname( __FILE__ ));

        $this->session = Fuse_Session::getInstance();
	}

	/**
	 * 下单页面
	 */
	public function index()
	{
		$data = array();

		//$data['itemList'] = $this->model->getList();
		//$data['serverList'] = $this->model->getServerList();

        // 企业信息
        //echo '<PRE>';print_r($this->session);exit;
        $company = $this->model->getCompany($this->session->data['company_id']);
        $data['companyName'] = $company['companyId'];
        echo '<pre>';print_r($company);exit;
        // 计算费用

        // 页面信息展示
		$view 			= $this->createView();
        $view->formhash = Config_App::formhash('checkout');
		$view->data 	= $data;
		$view->title	= $this->language['title'];
		$view->display('../checkout/checkout.html');
	}

    /**
     * 版本修改
     */
    public function editVersion(){

    }

    /**
     * 成员数量修改
     */
	public function editNum(){

    }

    /**
     * 购买年限修改
     */
    public function editYears() {

    }

	public function finished()
	{
		$jobNo = Fuse_Request::getVar('job_no', 'post');
		$formhash = Fuse_Request::getVar('formhash', 'post');

		$checkFormhash = Config_App::formhash('user');
		if ($checkFormhash != $formhash) {
			die(json_encode(array('status'=> 'INVALID_FORM', 'msg' => '非法提交')));
		}

		$model = new Fuse_Model();
		$info = $model->getRow("SELECT peu.`execuser_id`,peu.`job_no`,pl.`project_no` FROM `project_exec_users` as peu
								LEFT JOIN `project_list` as pl
								ON peu.`project_id` = pl.`project_id`
								WHERE `user_id` = '{$this->userId}'
								AND `job_no` = '{$jobNo}'");
		if (empty($info)) {
			die(json_encode(array('status'=> 'PARAM_ERROR', 'msg' => '参数错误')));
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
			die(json_encode(array('status'=> 'FILE_ERROR', 'msg' => '请上传文件')));
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
		die(json_encode(array('status'=> 'SUCCESS', 'msg' => '提交成功')));
	}
}
?>