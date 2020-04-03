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

		$this->registerTask('list', 'memberList');
		$this->registerTask('getinfo', 'getInfo');
		$this->registerTask('editinfo', 'editInfo');
		$this->registerTask('deluser', 'delUser');

		// 配置文件
		include_once(dirname(__file__) . '/config.php');
		$this->roleList = $roleList; // 权限
		
		$this->model = $this->createModel('Model_Member', dirname( __FILE__ ));
	}

	/**
	 * 人员列表
	 */
	public function memberList()
	{

		$itemList0 = $this->model->getMemberList(0);
		$itemList1 = $this->model->getMemberList(1);
		$itemList2 = $this->model->getMemberList(2);
		$itemList3 = $this->model->getMemberList(3);
		
		$data = array(
			array( // 最高权限
				'name' => $this->roleList['0'],
				'list' => $itemList0
			),
			array( // 总监
				'name' => $this->roleList['1'],
				'list' => $itemList1
			),
			array( // 项目经理
				'name' => $this->roleList['2'],
				'list' => $itemList2
			),
			array( // 执行人员
				'name' => $this->roleList['3'],
				'list' => $itemList3
			)
		);
        
        die(json_encode(array('code'=> '0000', 'message' => '成功', 'data' => $data)));
	}

	/**
	 * 获取人员信息
	 */
	public function getInfo()
	{
		$this->check();

		$userId = Fuse_Request::getVar('user_id', 'post');
		if (empty($userId)) {
			die(json_encode(array('code'=> '1111', 'message' => '参数丢失', 'data' => '')));
		}

		$userInfo = $this->model->getInfo($userId);
		if (empty($userInfo)) {
			die(json_encode(array('code'=> '2222', 'message' => '参数错误', 'data' => '')));
		}
		
		$data = array(
			'userInfo' => $userInfo,
			'roleList' => $this->roleList,
			'roleId'   => $this->roleId
		);
		
		die(json_encode(array('code'=> '0000', 'message' => '成功', 'data' => $data)));
	}

	/**
	 * 修改人员信息
	 */
	public function editInfo()
	{
		$this->check();

		$type      = Fuse_Request::getVar('type', 'post');
		$userId    = intval(Fuse_Request::getVar('user_id', 'post'));
		$username  = trim(Fuse_Request::getVar('edit_name', 'post'));
		$loginName = trim(Fuse_Request::getVar('edit_login', 'post'));
		$password  = Fuse_Request::getVar('edit_passwd', 'post');
		$roleId    = Fuse_Request::getVar('edit_role_id', 'post');
		$email	   = trim(Fuse_Request::getVar('edit_email', 'post'));
		$sendEmail = intval(Fuse_Request::getVar('send_mail', 'post'));

		if (!in_array($type, array('add', 'edit'))) {
			die(json_encode(array('code'=> '1111', 'message' => '验证参数异常', 'data' => '')));
		}

		if ($username == '') {
			die(json_encode(array('code'=> '2222', 'message' => '请填写员工姓名', 'data' => '')));
		}

		if ($loginName == '') {
			die(json_encode(array('code'=> '3333', 'message' => '请填写登录名', 'data' => '')));
		}

		if ($type == 'add') {
			if ($password == '') {
				die(json_encode(array('code'=> '4444', 'message' => '请填写密码', 'data' => '')));
			}
		}

		if (!array_key_exists($roleId, $this->roleList)) {
			die(json_encode(array('code'=> '5555', 'message' => '职位参数异常', 'data' => '')));
		}

		if ($email != '') {
			include_once('Email/Mailhelper.php');
			if (!MailHelper::isEmailAddress($email)) {
				die(json_encode(array('code'=> '6666', 'message' => '邮箱格式不正确', 'data' => '')));
			}
		}


		$isLoginNameUnique = $this->model->checkUnique('login_name', $loginName, $userId);
		if (!$isLoginNameUnique) {
			die(json_encode(array('code'=> '7777', 'message' => '登录名已存在', 'data' => '')));
		}

		/*$isEmailUnique = $this->model->checkUnique('email', $email, $userId);
		if (!$isEmailUnique) {
			die(json_encode(array('code'=> '8888', 'message' => '邮箱已存在', 'data' => '')));
		}*/

		$object 					= array();
		$object['username']      	= $username;
		$object['login_name']    	= $loginName;
		$object['role_id']       	= $roleId;
		$object['email'] 	     	= $email;
		$object['password']      	= $password;
		$object['last_mod_user_id'] = $this->userId;
		$object['last_mod_time']    = Config_App::getTime();
		if ($type == 'add') {
			$object['ip']      = Config_App::getIP();
			$object['time']    = Config_App::getTime();
			$object['is_used'] = '1';
		}

		if ($type == 'edit') {
			$model->update('user_list', $object, " `user_id` = '{$userId}' ");
		} else if ($type == 'add') {
			$returnId = $this->model->store('user_list', $object);
		}

		// 发邮件
		if ($sendEmail) {
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
						欢迎使用上海仁挚设计项目管理系统！
					</td>
				  </tr>
				  <tr>
					<td>
						系统地址：<a href="http://www.renzcreative.com" target="_blank">www.renzcreative.com</a>
					</td>
				  </tr>
				  <tr>
					<td>
						您的登录名：' . $loginName . '
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

			$emailResult = $this->sendToEmail($email, '仁挚登录名和密码信息', $body);
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
		}

		if ($roleId == '2' && $this->userId == $userId) {
			$return['msg'] .= "\n你已修改自己为项目经理，此时无权访问此功能";
			$return['redirect'] = 'project.php';
		}

		if ($roleId == '3' && $this->userId == $userId) {
			$return['msg'] .= "\n你已修改自己为执行人员，此时无权访问此功能，请重新登录";
			$return['redirect'] = '/';
		}

		die(json_encode(array('code'=> '0000', 'message' => $return['msg'], 'data' => '')));
	}

	/**
	 * 删除人员信息
	 */
	public function delUser()
	{
		$this->check();

		$userIds = Fuse_Request::getVar('user_ids', 'post');
		if (empty($userIds)) {
			die(json_encode(array('code'=> '1111', 'message' => '请勾选删除人员', 'data' => '')));
		}

		$userIds = "'" . implode("','", $userIds) . "'";
		// $this->model->delUser($userIds, $this->userId);
		$this->model->update('user_list', array('is_used' => '0'), " `user_id` IN ({$userIds}) ");

		die(json_encode(array('code'=> '0000', 'message' => '删除成功', 'data' => '')));
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

	/**
	 * 检查权限
	 */
	public function check()
	{
		if ($this->roleId != '0') {
			die(json_encode(array('code'=> '9999', 'message' => '无权限访问此功能', 'data' => '')));
		}
	}
}
?>
