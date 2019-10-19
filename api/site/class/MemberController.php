<?php
/**
 *
 * Controller for Member
 *
 * @desc	人员管理
 * @author	jerry.cao (caowlong163@163.com)
 * $date    2019-04-20 23:37:45
 */

class MemberController extends CommonController
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

		$this->registerTask('roleList', 'roleList');
		$this->registerTask('roleManageList', 'roleManageList');
		$this->registerTask('delRole', 'delRole');
		$this->registerTask('editRole', 'editRole');
		$this->registerTask('memberList', 'memberList');
		$this->registerTask('memberListAll', 'memberListAll');
		
		$this->registerTask('roleInfo', 'roleInfo');
		$this->registerTask('editRoleInfo', 'editRoleInfo');
		$this->registerTask('setUserValidStatus', 'setUserValidStatus');
		
		$this->registerTask('getUserInfo', 'getUserInfo');
		$this->registerTask('editUserInfo', 'editUserInfo');
		
		$this->registerTask('getListByDepartmentId', 'getListByDepartmentId');

		$this->model = $this->createModel('Model_Member', dirname( __FILE__ ));
	}

	/**
	 * 根据部门id查询列表
	 */
	public function getListByDepartmentId()
	{
		$username = Fuse_Request::getFormatVar($this->params, 'username');
		$username = Fuse_Tool::paramsCheck($username);

		$departmentId = Fuse_Request::getFormatVar($this->params, 'departmentId', '1');
		if ($departmentId == '') {
			die(json_encode(array('code'=> '1111', 'message' => '参数丢失', 'data' => '')));
		}

		$itemList  = $this->model->getListByDepartmentId($this->companyId, $departmentId, $username);
		
		die(json_encode(array('code'=> '0000', 'message' => '成功', 'data' => $itemList)));	
	}

	/**
	 * 角色列表 - 权限管理列表 使用
	 */
	public function roleList($returnArray = false)
	{
		$data = $this->model->getRoleList($this->companyId);
		
		if ($returnArray) {
			return $data;
		}
		
		die(json_encode(array('code'=> '0000', 'message' => '成功', 'data' => $data)));
	}
	
	/**
	 * 角色管理列表
	 */
	public function roleManageList()
	{
		$itemTitle = array(
			'itemNo'   => '序号',
			'roleName' => '角色名称',
			'action'   => '操作'
		);
		
		$itemList = array();
		if (!empty($this->roleList)) {
			foreach ($this->roleList as $value) {
				$i = 0;
				$itemList[] = array(
					'itemNo'   => $i + 1,
					'roleName' => $value['roleName'],
					'action'   => '<a id="' . $value['roleId'] . '" class="edit">修改</a>'
				);
				$i++;
			}
		}

		$data = array(
			'itemTitle' => $itemTitle,
			'itemList'  => $itemList
        );
		
		die(json_encode(array('code'=> '0000', 'message' => '成功', 'data' => $data)));
	}
	
	/**
	 * 角色删除
	 */
	public function delRole()
	{
		$roleId = Fuse_Request::getFormatVar($this->params, 'roleId', '1');
		if (empty($roleId)) {
			die(json_encode(array('code'=> '1111', 'message' => '参数丢失', 'data' => '')));
		}
		
		$nums = $this->model->delRoleById($this->companyId, $roleId);
		if ($nums == 0) {
			die(json_encode(array('code'=> '2222', 'message' => '删除角色失败', 'data' => '')));
		}
		
		die(json_encode(array('code'=> '0000', 'message' => '删除角色成功', 'data' => '')));
	}

	/**
	 * 角色编辑名称
	 */
	public function editRole()
	{
		$roleId = Fuse_Request::getFormatVar($this->params, 'roleId', '1');
		$name = Fuse_Request::getFormatVar($this->params, 'name');
		if (empty($roleId) || $name == '') {
			die(json_encode(array('code'=> '1111', 'message' => '参数丢失', 'data' => '')));
		}

		$nums = $this->model->editRole($this->companyId, $roleId, $name);
		if ($nums == 0) {
			die(json_encode(array('code'=> '2222', 'message' => '删除角色失败', 'data' => '')));
		}
		
		die(json_encode(array('code'=> '0000', 'message' => '删除角色成功', 'data' => '')));
	}

	/**
	 * 根据角色id查询所有用户列表
	 */
	public function memberList()
	{
		$roleId = Fuse_Request::getFormatVar($this->params, 'roleId', '1');
		if (empty($roleId)) {
			die(json_encode(array('code'=> '1111', 'message' => '参数丢失', 'data' => '')));
		}

		$page = Fuse_Request::getFormatVar($this->params, 'page', '1');
        if (empty($page)) { $page = 1; }
        $perpage = !empty($size) ? $size : 10;

		$where = '1';

		$totalitems = $this->model->getMemberListTotal($where, $this->companyId, $roleId);
		$totalPage = ceil($totalitems / $perpage);
		if ($page > $totalPage && $totalPage > 0) $page = $totalPage;

		$itemTitle = array(
			'itemNo'   => '序号',
			'username' => '姓名',
			'userNo'   => '工号',
			'mobile'   => '手机',
			'action'   => '操作'
		);

		$itemList = $this->model->getMemberListByRoleId($this->companyId, $roleId);
		if (!empty($itemList)) {
			$i = 0;
			foreach ($itemList as $key => &$value) {
				$actionNo = '<a class="valid_no" id="' . $value['userId'] . '">禁用</a>';
				$actionYes = '<a class="valid_yes" id="' . $value['userId'] . '">启用</a>';
				$actionEdit = '&nbsp;&nbsp;<a class="edit" id="' . $value['userId'] . '">编辑</a>';
				$newValue = array(
					'itemNo'   => ($page - 1) * $perpage + $i + 1,
					'username' => $value['username'],
					'userNo'   => $value['userNo'],
					'mobile'   => $value['mobile'],
					'action'   => $value['valid'] ? $actionNo : $actionYes
				);
				$newValue['action'] .= $actionEdit;

				$value = $newValue;
				unset($value);
				$i++;
			}
		}


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
	 * 根据公司id查询所有用户列表
	 */
	public function memberListAll()
	{
		$page = Fuse_Request::getFormatVar($this->params, 'page', '1');
        if (empty($page)) { $page = 1; }
        $perpage = !empty($size) ? $size : 10;

		$where = '1';

		$totalitems = $this->model->getMemberListTotal($where, $this->companyId, '');
		$totalPage = ceil($totalitems / $perpage);
		if ($page > $totalPage && $totalPage > 0) $page = $totalPage;

		$itemTitle = array(
			'itemNo'       => '序号',
			'username'     => '姓名',
			'userNo'       => '工号',
			'mobile'       => '手机',
			'role'         => '角色',
			'department'   => '部门',
			'job' 		   => '职位',
			'registerDate' => '注册日期'
		);


		$itemList = $this->model->getMemberListByCompanyId($this->companyId);
		if (!empty($itemList)) {
			$i = 0;
			foreach ($itemList as $key => &$value) {
				$newValue = array(
					'itemNo'   	   => ($page - 1) * $perpage + $i + 1,
					'username'	   => $value['username'],
					'userNo'  	   => $value['userNo'],
					'mobile'   	   => $value['mobile']
				);

				// 角色
				$newValue['role'] = $this->checkRole($value['roleId']);
				
				// 部门
				$newValue['department'] = $this->checkDepartment($value['departmentId']);
				
				// 角色
				$newValue['job'] = $this->checkJob($value['jobId']);

				$newValue['registerDate'] = $value['registerDate'];
				$value = $newValue;
				unset($value);
				$i++;
			}
		}


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
	 * 根据角色id查询出角色信息及对应权限
	 */
	public function roleInfo()
	{
		$roleId = Fuse_Request::getFormatVar($this->params, 'roleId', '1');
		if (empty($roleId)) {
			die(json_encode(array('code'=> '1111', 'message' => '参数丢失', 'data' => '')));
		}
		
		$data = $this->model->getRoleInfo($this->companyId, $this->roleId);

        die(json_encode(array('code'=> '0000', 'message' => '成功', 'data' => $data)));
	}
	
	/**
	 * 保存用户角色信息
	 */
	public function editRoleInfo()
	{
		$roleId   = Fuse_Request::getFormatVar($this->params, 'roleId', '1');
		$roleName = Fuse_Request::getFormatVar($this->params, 'roleName');
		$menus    = Fuse_Request::getFormatVar($this->params, 'menus');
		
		if ($roleName == '' && $menus == '') {
			die(json_encode(array('code'=> '1111', 'message' => '参数丢失', 'data' => '')));
		}
		
		$object = array();
		if ($roleName != '') {
			$object['role_name'] = $roleName;
		}
		
		$sql = " `role_id` = '{$roleId}' AND `company_id` = '{$this->companyId}' ";
		$result = $this->model->update($this->model->getTableRoleName(), $object, $sql);
		
		if ($result && $menus != '') {
			$menuList = explode(',', $menus);
			
			$sql = "DELETE FROM `{$this->model->getTableRoleMenuName()}` 
				WHERE `company_id` = '{$this->companyId}' AND `role_id` = '{$roleId}'";
			$this->model->query($sql);
			
			foreach ($menuList as $menuId) {
				$objArray = array(
					'role_id' 	 => $roleId,
					'menu_id' 	 => $menuId,
					'company_id' => $this->companyId
				);
				$this->model->store($this->model->getTableRoleMenuName(), $objArray);
			}
		}
		
		die(json_encode(array('code'=> '0000', 'message' => '成功', 'data' => '')));
	}
	
	/**
	 * 设置用户状态是否禁用
	 */
	public function setUserValidStatus()
	{
		$userId = Fuse_Request::getFormatVar($this->params, 'userId', '1');
		$valid = Fuse_Request::getFormatVar($this->params, 'valid', '1');
		if (!in_array($valid, array('0', '1')) || empty($userId)) {
			die(json_encode(array('code'=> '1111', 'message' => '参数错误', 'data' => '')));
		}

		// 判断有效成员数量是否超过限制
		if ($valid == 1) {
			$this->checkValidMember();
		}

		$object = array(
			'valid' 		   => $valid,
			'last_mod_user_id' => $this->userId,
			'last_mod_time'    => Config_App::getTime()
		);
		$sql = " `user_id` = '{$userId}' AND `company_id` = '{$this->companyId}' ";
		$this->model->update($this->model->getTableUserName(), $object, $sql);
		
		die(json_encode(array('code'=> '0000', 'message' => '成功', 'data' => '')));
	}
	
	
	/**
	 * 获取人员信息
	 */
	public function getUserInfo()
	{
		$userId = Fuse_Request::getFormatVar($this->params, 'userId', '1');
		if (empty($userId)) {
			die(json_encode(array('code'=> '1111', 'message' => '参数丢失', 'data' => '')));
		}

		$userInfo = $this->model->getUserInfo($userId);
		if (empty($userInfo)) {
			die(json_encode(array('code'=> '2222', 'message' => '参数错误', 'data' => '')));
		}
		
		$data = array(
			'userInfo' => $userInfo,
		);
		
		die(json_encode(array('code'=> '0000', 'message' => '成功', 'data' => $data)));
	}
	
	/**
	 * 新增/修改人员信息
	 */
	public function editUserInfo()
	{
		$type         = Fuse_Request::getFormatVar($this->params, 'type');
		$userId       = Fuse_Request::getFormatVar($this->params, 'userId', '1');
		$username     = Fuse_Request::getFormatVar($this->params, 'username');
		$mobile       = Fuse_Request::getFormatVar($this->params, 'mobile');
		$password     = Fuse_Request::getFormatVar($this->params, 'password');
		$loginName    = '';
		$email	      = Fuse_Request::getFormatVar($this->params, 'email');
		$userNo    	  = Fuse_Request::getFormatVar($this->params, 'userNo');
		$roleId       = Fuse_Request::getFormatVar($this->params, 'roleId', '1');
		$departmentId = Fuse_Request::getFormatVar($this->params, 'departmentId', '1');
		$jobId    	  = Fuse_Request::getFormatVar($this->params, 'jobId', '1');
		$isSend 	  = Fuse_Request::getFormatVar($this->params, 'isSend', '1');

		if (!in_array($type, array('add', 'edit'))) {
			die(json_encode(array('code'=> '1111', 'message' => '验证参数异常', 'data' => '')));
		}

		if ($username == '') {
			die(json_encode(array('code'=> '2222', 'message' => '请填写员工姓名', 'data' => '')));
		}

		if ($mobile == '') {
			die(json_encode(array('code'=> '3333', 'message' => '请填写手机号', 'data' => '')));
		}

		if ($type == 'add') {
			if ($password == '') {
				die(json_encode(array('code'=> '4444', 'message' => '请填写密码', 'data' => '')));
			}
		}

		if ($email != '') {
			include_once('Email/Mailhelper.php');
			if (!MailHelper::isEmailAddress($email)) {
				die(json_encode(array('code'=> '5555', 'message' => '邮箱格式不正确', 'data' => '')));
			}

			$isEmailUnique = $this->model->checkUnique('email', $email, $this->userId);
			if (!$isEmailUnique) {
				die(json_encode(array('code'=> '6666', 'message' => '邮箱已存在', 'data' => '')));
			}	
		}
		
		if ($roleId == '') {
			die(json_encode(array('code'=> '7777', 'message' => '请选择角色', 'data' => '')));
		} else if ($this->checkRole($roleId) == '') {
			die(json_encode(array('code'=> '7777', 'message' => '角色参数异常', 'data' => '')));
		}

		if ($jobId != '' && $this->checkJob($jobId) == '') {
			die(json_encode(array('code'=> '8888', 'message' => '职位参数异常', 'data' => '')));
		}
		
		if ($departmentId != '' && $this->checkDepartment($departmentId) == '') {
			die(json_encode(array('code'=> '9999', 'message' => '部门参数异常', 'data' => '')));
		}

		/*$isLoginNameUnique = $this->model->checkUnique('login_name', $loginName, $userId);
		if (!$isLoginNameUnique) {
			die(json_encode(array('code'=> '7777', 'message' => '登录名已存在', 'data' => '')));
		}*/

		$object 					= array(); 
		$object['username']      	= $username;
		$object['login_name']    	= $loginName;
		$object['company_id']      	= $this->companyId;
		$object['user_no']      	= $userNo;
		$object['mobile']      		= $mobile;
		$object['role_id']       	= $roleId;
		$object['email'] 	     	= $email;
		$object['rand_str'] 	    = Fuse_Tool::getRandStr(10);
		$object['password']      	= md5($password . $object['rand_str']);
		$object['is_package']      	= 1;
		$object['department_id']    = $departmentId;
		$object['job_id']    		= $jobId;
		$object['last_mod_user_id'] = $this->userId;
		$object['last_mod_time']    = Config_App::getTime();
		if ($type == 'add') {
			$object['create_ip']      = Config_App::getIP();
			$object['create_time']    = Config_App::getTime();
			$object['valid'] = '1';
		}

		if ($type == 'edit' && $userId != '') {
			$this->model->update($this->model->getTableUserName(), $object, " `user_id` = '{$userId}' ");
		} else if ($type == 'add') {
			// 判断有效成员数量是否超过限制
			$this->checkValidMember();
			
			$returnId = $this->model->store($this->model->getTableUserName(), $object);
		}

		// 发邮件
		/*if ($isSend) {
			$body = '<!DOCTYPE html>
			<html lang="en">
			  <head>
				<meta charset="utf-8">
				<title>上海仁挚设计</title>
			  </head>
			  <body style="padding:0;nargin:0">
				<table border="0" width="400" align="left" cellpadding="0" cellspacing="0">
				  <tr>
					<td>
						您好，' . $username . '
					</td>
				  </tr>
				  <tr>
					<td>
						欢迎使用easyku平台！
					</td>
				  </tr>
				  <tr>
					<td>
						系统地址：<a href="http://www.renzcreative.com" target="_blank">www.renzcreative.com</a>
					</td>
				  </tr>
				  <tr>
					<td>
						您手机号：' . $mobile . '
					</td>
				  </tr>
				  <tr>
					<td>
						密码：' . $password . '。
					</td>
				  </tr>
				  <tr>
					<td>
						请勿泄漏
					</td>
				  </tr>
				</table>
			  </body>
			 </html>';

			$emailResult = $this->sendToEmail($email, 'easyku登录名和密码信息', $body);
		}

		$return = array('status'=> 'SUCCESS');
		if ($type == 'add') {
			$return['msg'] = '添加成功';
			$return['user_id'] = $returnId;
		} else {
			$return['msg'] = '修改成功';
		}

		if ($sendEmail && !$emailResult) {
			$return['msg'] .= '，邮件提醒发送失败';
		}*/
		
		if ($type == 'add') {
			$return['msg'] = '添加成功';
			$return['user_id'] = $returnId;
		} else {
			$return['msg'] = '修改成功';
		}

		die(json_encode(array('code'=> '0000', 'message' => $return['msg'], 'data' => '')));
	}

	/**
	 * 检查角色
	 */
	private function checkRole($roleId)
	{
		$flag = '';
		$itemList = $this->roleList(true);
		if (!empty($itemList)) {
			foreach ($itemList as $value) {
				if ($roleId == $value['roleId']) {
					$flag = $value['roleName'];
					break;
				}
			}
		}
		
		return $flag;
	}
	
	/**
	 * 检查职位
	 */
	private function checkJob($jobId)
	{
		$flag = '';
		
		$model = $this->createModel('Model_Job', dirname( __FILE__ ));
		$where = " `company_id` = '{$this->companyId}' AND `valid` = '1' ";
		$itemList  = $model->getList(0, 0, $where);

		if (!empty($itemList)) {
			foreach ($itemList as $value) {
				if ($jobId == $value['jobId']) {
					$flag = $value['jobName'];
					break;
				}
			}
		}
		
		return $flag;
	}
	
	/**
	 * 检查部门
	 */
	private function checkDepartment($departmentId)
	{
		$flag = '';
		
		$model = $this->createModel('Model_Department', dirname( __FILE__ ));
		$where = " `company_id` = '{$this->companyId}' AND `valid` = '1' ";
		$itemList  = $model->getList(0, 0, $where);

		if (!empty($itemList)) {
			foreach ($itemList as $value) {
				if ($departmentId == $value['departmentId']) {
					$flag = $value['departmentName'];
					break;
				}
			}
		}
		
		return $flag;
	}

	/**
	 * 删除人员信息
	 */
	public function delUser()
	{
		$userIds = Fuse_Request::getFormatVar($this->params, 'userIds');
		if (empty($userIds)) {
			die(json_encode(array('code'=> '1111', 'message' => '请勾选删除人员', 'data' => '')));
		}

		$userIds = "'" . implode("','", $userIds) . "'";
		// $this->model->delUser($userIds, $this->userId);
		$this->model->update('user_list', array('is_used' => '0'), " `user_id` IN ({$userIds}) ");

		die(json_encode(array('code'=> '0000', 'message' => '删除成功', 'data' => '')));
	}

	/**
	 * 判断有效成员数量是否超过限制
	 */
	private function checkValidMember()
	{
		/** 判断有效成员数量是否超过限制 开始 **/
		$peopleNums = 0;
		
		// 套餐
		$modelProduct = $this->createModel('Model_Product', dirname( __FILE__ ));
		$productInfo = $modelProduct->getProductById($this->productId);
		if (isset($productInfo['max_people']) && $productInfo['max_people'] != '') {
			$peopleNums += intval($productInfo['max_people']);
		}
		
		// 订单
		$modelOrder = $this->createModel('Model_Order', dirname( __FILE__ ));
		$orderInfo = $modelOrder->getOrderByCompanyId($this->companyId);
		if (isset($orderInfo['extra_nums']) && $orderInfo['extra_nums'] != '') {
			$peopleNums += intval($orderInfo['extra_nums']);
		}
		
		// 当前已有人数
		$currentNums = $this->model->getMemberTotal($this->companyId);
		if ($currentNums >= $peopleNums) {
			die(json_encode(array('code'=> '1000', 'message' => '已超过成员数量限制，如需再新增人员，请联系客服续费', 'data' => '')));
		}
		/** 判断有效成员数量是否超过限制 结束 **/
	}

	/**
	 * 发送邮件
	 */
	private function sendToEmail($sendto, $subject, $body)
	{
		include_once('Email/phpmailer/class.phpmailer.php');
		$configEmail = Config_Email::toArray();

		try {
			$mail = new PHPMailer();
			$mail->SMTPDebug = false;
			$mail->IsSMTP(); 						 	// send via SMTP
			$mail->SMTPAuth = true;             	 	// turn on SMTP authentication
			$mail->Host 	= $configEmail['host'];		// SMTP servers
			$mail->Port 	= 25;                	    // SMTP server port
			$mail->Username = $configEmail['username']; // SMTP username
			$mail->Password = $configEmail['password']; // SMTP password
			// $mail->IsSendmail();  				 	// 使用Sendmail组件，如果没有sendmail组件就把这注释掉
			// $mail->AddReplyTo("pms_posher@163.com"); // 答复地址必须和发件人一致
			$mail->From 	= $configEmail['from'];  	// 发件人邮箱
			$mail->FromName = $configEmail['fromName']; // 发件人
			$mail->CharSet  = "UTF8";   		     	// 字符集
			$mail->Encoding = "base64";				 	// 编码方式
			$mail->AddAddress($sendto, "");      	 	// 收件人邮箱和姓名
			$mail->IsHTML(true);  					 	// 是否支持HTML邮件
			$mail->Subject = $subject;				 	// 邮件主题
			// $mail->Body = $body;					 	// 邮件内容
			$mail->MsgHTML($body);
			$mail->AltBody  = "text/html";
			$mail->WordWrap = 80; 					 	// set word wrap
			if (!$mail->Send()) {
				return false;
			}
			return true;
		} catch (Exception $e) {
			return false;
		}
	}
}
?>
