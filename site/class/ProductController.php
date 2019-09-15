<?php
/**
 *
 * Controller for Product
 *
 * @desc	产品
 * @author	jerry.cao (caowlong163@163.com)
 * $date    2019-05-18 23:49:31
 */
class ProductController extends Fuse_Controller
{
	private $url = '/product';

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

/*
		if (empty($this->userId) || empty($this->roleId)) {
			Fuse_Response::redirect('/');
		}
*/

		$this->registerTask('index', 'index');

/*
		// 配置文件
		include_once(dirname(__file__) . '/config.php');

*/
		$this->model = $this->createModel('Model_Product', dirname( __FILE__ ));
	}

	/**
	 * 产品列表
	 */
	public function index()
	{

		$data = array();

		$data['itemList'] = $this->model->getList();
		$data['serverList'] = $this->model->getServerList();

		$title = '产品';
		$html = 'product.html';

		$view 			= $this->createView();
		$view->data 	= $data;
		$view->title	= $title;
		$view->username = $this->username;
        $view->display($html);
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
