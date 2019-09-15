<?php
class Model_Register extends Fuse_Model
{
	private $table = array('name' => 'ek_company', 'key' => 'company_id');

	public function __construct($options = array())
	{
		parent::__construct($options);
	}


	function validateCode($phone) {
        $sql = "SELECT `company_id` AS companyId, `company_no` AS companyNo, `company_name` AS companyName, 
					`contact_name` AS contactName, `email`, `industry`, `valid`, `product_id` AS productId, 
					`recommand_id` AS recommandId
				FROM `".$this->table['name']."` 
				WHERE `contact_phone` = '" . $phone . "'";
    }
	/**
	 * login check
	 */
	function getLogin($phone)
	{
		$list = array();

		$sql = "SELECT `company_id` AS companyId, `company_no` AS companyNo, `company_name` AS companyName, 
					`contact_name` AS contactName, `email`, `industry`, `valid`, `product_id` AS productId, 
					`recommand_id` AS recommandId
				FROM `".$this->table['name']."` 
				WHERE `contact_phone` = ?";

		$stmt = $this->db->query($sql, array($phone));
		if ($row = $stmt->fetch()) {
			$list = $row;
		}

		return $list;
	}

    public function addUser($data) {

	    // insert ek_company
        $data['create_ip'] = $_SERVER['REMOTE_ADDR'];
        $this->db->query("INSERT INTO `ek_company` SET `company_name` = '" . $data['company'] . "', `contact_name` = '" . $data['username'] . "', `contact_phone` = '" . $data['phone'] . "', `create_time` = NOW(), `create_ip` = '" . $data['create_ip'] . "'");
        $company_id = $this->db->lastInsertId();
        $date = date('Ymd', time());
        $company_no = $date + $company_id;
        $this->db->query("UPDATE ek_company SET company_no = '" . $company_no ."' WHERE company_id='" . $company_id . "'");

        // insert ek_user
        //"', salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape
        $sql = "INSERT INTO `ek_user` SET `company_id` = '" . $company_id . "', `username` = '" . $data['username'] . "', `mobile` = '" . $data['phone'] . "', `rand_str` = '" . ($salt = substr(md5(uniqid(rand(), true)), 0, 10)) . "', `password` = '" . (sha1($salt . sha1($salt . sha1($data['password'])))) . "', `create_time` = NOW(), `create_ip` = '" . $data['create_ip'] ."'";
        $this->db->query($sql);
        $user_id = $this->db->lastInsertId();

        // insert ek_role
        // 新增“超级管理员”仅限 1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30
        $this->db->query("INSERT INTO `ek_role` (`role_name`, `company_id`, `create_userid`, `create_time`, `create_ip`, `update_time`, `update_userid`, `valid`) VALUES ('超级管理员', '" . $company_id . "', '" . $user_id . "', NOW(), '" . $data['create_ip'] . "', NOW(), '" . $user_id . "', '1')");
        $role_id_1 = $this->db->lastInsertId();
        $sql_1 = "INSERT INTO `ek_role_menu` (`role_id`, `menu_id`, `company_id`) VALUES ";
        $sql_1_count = 5;
        $sql_tmp = '';
        for($i=1; $i <= $sql_1_count; $i++) {
            $sql_tmp .= "('" . $role_id_1 . "', '" . $i . "', '" . $company_id . "')" . (($i == $sql_1_count) ? '' : ',');
        }
        $this->db->query($sql_1.$sql_tmp);

        // 新增“总监”仅限 1,2,3,4,5,14,6,7,8,13,9,10,27,28,29,30,23,24,25,26
        $this->db->query("INSERT INTO `ek_role` (`role_name`, `company_id`, `create_userid`, `create_time`, `create_ip`, `update_time`, `update_userid`, `valid`) VALUES ('总监', '" . $company_id . "', '" . $user_id . "', NOW(), '" . $data['create_ip'] . "', NOW(), '" . $user_id . "', '1')");
        $role_id_2 = $this->db->lastInsertId();
        $sql_2 = "INSERT INTO `ek_role_menu` (`role_id`, `menu_id`, `company_id`) VALUES ";
        $sql_2_count = array(1,2,3,4,5,14,6,7,8,13,9,10,27,28,29,30,23,24,25,26);
        $sql_tmp = '';
        foreach ($sql_2_count as $v) {
            $sql_tmp .= "('" . $role_id_2 . "', '" . $v . "', '" . $company_id . "')" . (($v == end($sql_2_count)) ? '' : ',');
        }
        $this->db->query($sql_2.$sql_tmp);

        // 新增“项目经理”仅限 1,2,3,4,5,14,6,7,8,13,9,10,27,28,29,30,23,24,25,26
        $this->db->query("INSERT INTO `ek_role` (`role_name`, `company_id`, `create_userid`, `create_time`, `create_ip`, `update_time`, `update_userid`, `valid`) VALUES ('项目经理', '" . $company_id . "', '" . $user_id . "', NOW(), '" . $data['create_ip'] . "', NOW(), '" . $user_id . "', '1')");
        $role_id_3 = $this->db->lastInsertId();
        $sql_3 = "INSERT INTO `ek_role_menu` (`role_id`, `menu_id`, `company_id`) VALUES ";
        $sql_3_count = array(1,2,3,4,5,14,6,7,8,13,9,10,27,28,29,30,23,24,25,26);
        $sql_tmp = '';
        foreach ($sql_3_count as $v) {
            $sql_tmp .= "('" . $role_id_3 . "', '" . $v . "', '" . $company_id . "')" . (($v == end($sql_3_count)) ? '' : ',');
        }
        $this->db->query($sql_3.$sql_tmp);

        // 新增“执行人员”仅限 6,7,8,13,9,10,27,28,29,30,23,24,25,26
        $this->db->query("INSERT INTO `ek_role` (`role_name`, `company_id`, `create_userid`, `create_time`, `create_ip`, `update_time`, `update_userid`, `valid`) VALUES ('执行人员', '" . $company_id . "', '" . $user_id . "', NOW(), '" . $data['create_ip'] . "', NOW(), '" . $user_id . "', '1')");
        $role_id_4 = $this->db->lastInsertId();
        $sql_4 = "INSERT INTO `ek_role_menu` (`role_id`, `menu_id`, `company_id`) VALUES ";
        $sql_4_count = array(6,7,8,13,9,10,27,28,29,30,23,24,25,26);
        $sql_tmp = '';
        foreach ($sql_4_count as $v) {
            $sql_tmp .= "('" . $role_id_4 . "', '" . $v . "', '" . $company_id . "')" . (($v == end($sql_4_count)) ? '' : ',');
        }
        $this->db->query($sql_4.$sql_tmp);
        return $role_id_4;
    }

    public function saveSmsContent($result) {
        $type = $result['type'];
        $phone = $result['phone'];
        $request_id = $result['requestId'];
        $code = $result['code'];
        $message = $result['msg'];
        $biz_id = $result['bizId'];
        $verification_code = $result['verification_code'];
        $is_used = '0';
        $sendtimes = '1';
        $time = date('Y-m-d H:i:s');
        $ip = $_SERVER['REMOTE_ADDR'];

//        if (empty($smsInfo)) {
            $sql = "INSERT INTO `ek_verification_code`(`type`, `phone`, `request_id`, `code`, `message`, `biz_id`, `verification_code`, `is_used`, `sendtimes`, `time`, `ip`) VALUES('{$type}', '{$phone}', '{$request_id}', '{$code}', '{$message}', '{$biz_id}', '{$verification_code}', '{$is_used}', '{$sendtimes}', '{$time}', '{$ip}')";

            $this->db->query($sql);
            return $this->db->lastInsertId();
//        } else {
//            $sendtimes = $smsInfo['sendtimes'] + 1;
//            $sql = "UPDATE `" . DB_PREFIX . "validate_user` SET `code` = '{$code}', `is_used` = '0', `sendtimes` = '{$sendtimes}', `time` = '{$time}', `ip`='{$ip}' WHERE `name` = '{$telephone}' LIMIT 1";
//
//            $this->db->query($sql);
//            return $this->db->countAffected();
//        }
    }

    // 查询手机号是否注册过公司
    public function getCompanyByTelephone($phone) {
        $query = $this->db->fetchRow("SELECT `company_id`, `contact_phone` FROM ek_company WHERE `contact_phone` = '" . $phone . "'");
        return $query;
    }

    // 查询手机号是否注册过用户
    public function getUserByTelephone($phone) {
        $query = $this->db->fetchRow("SELECT * FROM ek_user WHERE `mobile` = '" . $phone . "'");
        return $query;
    }

    public function smsLogin($phone, $smscode, $request_id) {
	    $verCodeResult = $this->db->fetchRow("SELECT `verification_code` FROM `ek_verification_code` WHERE phone='" . $phone . "' and `request_id` = '" . $request_id . "'and `type` = '1' and `is_used` = '0' and `code` = 'OK'");
	    if ($verCodeResult['verification_code'] == $smscode) {
            return true;
        }
        return false;
    }
}
?>