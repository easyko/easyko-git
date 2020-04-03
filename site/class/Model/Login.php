<?php
class Model_Login extends Fuse_Model
{
	private $table = array('name' => 'ek_company', 'key' => 'company_id');

	public function __construct($options = array())
	{
		parent::__construct($options);
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

	public function smsLogin($phone, $smscode, $request_id) {
	    $verCodeResult = $this->db->fetchRow("SELECT `verification_code` FROM `ek_verification_code` WHERE phone='" . $phone . "' and `request_id` = '" . $request_id . "'and `type` = '2' and `is_used` = '0' and `code` = 'OK'");
        if ($verCodeResult['verification_code'] == $smscode) {
            return true;
        }
        return false;
    }
}
?>