<?php
/**
 *
 * Controller for Common
 *
 * @package		classes
 * @subpackage	index
 * @author		jerry.cao (caowlong163@163.com)
 *
 */

class CommonController extends Fuse_Controller
{
	protected $username  = '';
	protected $userId    = 0;
	protected $roleId    = 0;
	protected $companyId = 0;
	protected $productId = 0;
	protected $menuName  = '';
	protected $roleList = array();
	protected $params = array();

	/**
	 * Constructor
	 *
	 * @params	array	Controller configuration array
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);
			$this->companyId=1;
		$this->menuName = str_replace('.php', '', str_replace('/', '', $_SERVER['SCRIPT_NAME']));
		// $this->url = str_replace('/', '', $_SERVER['SCRIPT_NAME']);

		// 获取json或post等提交的参数
		$this->params = $this->initParamsData();

		// 参数校验
		$this->checkParams($this->params);

		$sign = Fuse_Request::getFormatVar($this->params, 'signature'); // Fuse_Request::getVar('signature', 'post'); // post传，后期修改为header
		// $sign = Fuse_Request::getVar('HTTP_SIGNATURE', 'SERVER'); // header传值
		if (!empty($sign)) {
			$data = Fuse_Tool::getUserInfo($sign);
			if (!empty($data)) {
				$this->userId    = $data['info']['userId'];
				$this->username  = $data['info']['username'];
				$this->roleId    = $data['info']['roleId'];
				$this->companyId = $data['info']['companyId'];

				// 获取用户的公司角色信息
				$modelRole = $this->createModel('Model_Member', dirname( __FILE__ ));
				$this->roleList = $modelRole->getRoleList($data['info']['companyId']);
				
				// 查询公司套餐商品
				$modelCompany = $this->createModel('Model_Company', dirname( __FILE__ ));
				$companyInfo = $modelCompany->getCompanyById($data['info']['companyId']);
				$this->productId = isset($companyInfo['product_id']) ? $companyInfo['product_id'] : '';
			}

			$this->checkLoginValid();
			$this->checkUser();	
		}
	}

	/**
	 * 参数校验
	 * 
	 */
	private function checkParams($params)
	{
		// 此处暂时只检查json参数
		$type = isset($_SERVER['CONTENT_TYPE']) ? $_SERVER['CONTENT_TYPE'] : '';
        if (isset($_SERVER['HTTP_CONTENT_TYPE'])) {
            $type = $_SERVER['HTTP_CONTENT_TYPE'];
        }
		if ($type != 'application/json') {
			return;
		}
		
		if (empty($params) || empty($params)) {
			die(json_encode(array('code'=> '0001', 'message' => '参数丢失', 'data' => '')));
		}

		if (!isset($params['params']) || empty($params['params'])) {
			die(json_encode(array('code'=> '0002', 'message' => '业务参数丢失', 'data' => '')));
		}
		
		if (!isset($params['time']) || $params['time'] == '') {
			die(json_encode(array('code'=> '0003', 'message' => '时间戳参数丢失', 'data' => '')));
		}
		
		// 时间校验超过5分钟报异常
		if ((time() - $params['time']) > 300) {
			//die(json_encode(array('code'=> '0004', 'message' => '时间戳参数无效', 'data' => '')));
		}
		
		if (!isset($params['version']) || $params['version'] == '' || $params['version'] != 'v1.0') {
			die(json_encode(array('code'=> '0005', 'message' => '版本参数无效', 'data' => '')));
		}
		
		if (!isset($params['sign']) || $params['sign'] == '') {
			die(json_encode(array('code'=> '0006', 'message' => '签名参数丢失', 'data' => '')));
		}
		
		// 验证签名
		$sign = $this->createSign($params['params']);
		if ($this->params['sign'] != $sign) {
			//die(json_encode(array('code'=> '0007', 'message' => '签名参数无效', 'data' => '')));
		}
	}
	
    /**
     * 验证登录超时
     *
     */
	public function checkLoginValid()
	{
		if (empty($this->userId) || empty($this->roleId) 
			|| empty($this->companyId) || empty($this->roleList)) {
			die(json_encode(array('code'=> '0111', 'message' => '参数缺失，登录异常', 'data' => '')));
		}
	}

    /**
     * 验证登录权限
     *
     */
	public function checkUser()
	{
/*
		$model = $this->createModel('Model_Member', dirname( __FILE__ ));
		$userInfo = $model->getInfo($this->userId);
		Fuse_Cookie::getInstance()->role_id = $userInfo['role_id'];
*/
	}
	
	/**
     * 获取 post 参数; 在 content_type 为 application/json 时，自动解析 json
     * @return array
     */
    private function initParamsData()
    {
        if (isset($_SERVER['CONTENT_TYPE']) && $_SERVER['CONTENT_TYPE'] == 'application/json') {
            $content = file_get_contents('php://input');
            $content = json_decode($content, true);
            $content = !empty($content) ? Fuse_Filter_Input::shtmlspecialchars($content) : array();
        } else {
            $content = $_REQUEST;
        }

        return $content;
    }
    
    /**
	 * 获取签名
	 * 
	 */
	private function createSign($content)
	{
		$sign = '';
		if ($content == '') {
			return $sign;
		}
		
		// 签名
		ksort($content); // 键按ASCII码升序排序
		
		foreach ($content as $key => $value) {
            if (trim($value) != '') {
                $sign .= $key . $value;
            }
        }

        if (!empty($sign)) {
            $sign .= Config_Global::$signCommentKey;
            $sign = md5($sign);
        }

        return $sign;
	}
}