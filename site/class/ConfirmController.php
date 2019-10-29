<?php
/**
 *
 * Controller for Product
 *
 * @desc	产品
 * @author	jerry.cao (caowlong163@163.com)
 * $date    2019-05-18 23:49:31
 */
class ConfirmController extends Fuse_Controller
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
        $this->registerTask('editData', 'editData');

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

        $product = $this->session->data['product'];
        $data['companyName'] = $this->session->data['companyName'];
        //$data['version'] = $this->session->data['version'] = 'team';
        $data['num'] = $this->session->data['num'];
        $data['years'] = $this->session->data['years'];
        $data['perprice'] = $this->session->data['perprice'];
        $data['total'] = $this->session->data['total'];

        list($usec, $sec) = explode(" ", microtime());
        $data['order_no'] = date("Ym").substr($usec, 2, 6);
        $data['company_id'] = $this->session->data['company_id'];
        $data['product_id'] = $product['product_id'];
        $data['start_date'] = $product['create_time'];
        $data['end_date'] = date("Y-m-d H:i:s", strtotime("+1 year", strtotime($product['create_time'])));
        $data['extra_nums'] = $this->session->data['num'];
        $data['price'] = $this->session->data['perprice'];
        $data['total_price'] = $data['extra_price'] = $this->session->data['total'];
        $data['create_ip'] = $_SERVER['REMOTE_ADDR'];

        // 生成订单
        $data['orderId'] = $this->session->data['orderId'] = $this->model->addOrder($data);

        // 页面信息展示
		$view 			= $this->createView();
        $view->formhash = Config_App::formhash('checkout');
		$view->data 	= $data;
		$view->title	= $this->language['title'];
		$view->display('../checkout/confirm.html');
	}

    /**
     * 版本修改
     */
    public function editVersion(){

    }

    /**
     * 成员数量和年限修改
     */
	public function editData(){
        $this->request['num'] = !empty(Fuse_Request::getVar('num', 'post')) ? Fuse_Request::getVar('num', 'post') : $this->session->data['num'];
        $this->request['years'] = !empty(Fuse_Request::getVar('years', 'post')) ? Fuse_Request::getVar('years', 'post') : $this->session->data['years'];
        $this->request['yearcaptital'] = !empty(Fuse_Request::getVar('yearcaptital', 'post')) ? Fuse_Request::getVar('yearcaptital', 'post') : $this->session->data['yearcaptital'];
        $this->request['forward']  = Fuse_Request::getVar('forward');
        $data['num'] = $this->session->data['num'] = $this->request['num'];
        $data['years'] = $this->session->data['years'] = $this->request['years'];
        $data['yearcaptital'] = $this->session->data['yearcaptital'] = $this->request['yearcaptital'];
        $data['perprice'] = $this->session->data['perprice'] = $this->session->data['perprice'];
        $data['total'] = $this->session->data['total'] = $data['perprice'] * (int)$data['years'] * (int)$data['num'];
        die(json_encode($data));
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