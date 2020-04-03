<?php
/**
 *
 * Controller for Login
 *
 * @package		classes
 * @subpackage	index
 * @author		jerry.cao (caowlong163@163.com)
 *
 */

class IndexController extends Fuse_Controller
{
    protected $title = '首页';
	/**
	 * Constructor
	 *
	 * @params	array	Controller configuration array
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);

		$this->registerTask('index', 'index');
		$this->registerTask('login', 'login');
		$this->registerTask('logout', 'logout');

		$this->companyId = Fuse_Cookie::getInstance()->companyId;
		$this->companyName = Fuse_Cookie::getInstance()->companyName;
	}

	public function display()
	{
		die('error');
	}

	/**
	 * 登录
	 */
	public function index()
	{
        $view = $this->createView();
        $view->formhash = Config_App::formhash('login');
        $view->title = $this->title;

        if (Fuse_Customer::getInstanceCustomer()->isLogged()) {
            $view->loginname = Fuse_Session::getInstance()->data['username'];
            $view->display('../common/index.html');
        } else {
            $view->display('../common/index_unlogin.html');
        }
        return;
	}
}
