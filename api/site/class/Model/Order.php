<?php
class Model_Order extends Fuse_Model
{
	private $tableOrder = array('name' => 'ek_order', 'key' => 'order_id');
	private $tableUser = array('name' => 'ek_user', 'key' => 'user_id');

	public function __construct($options = array())
	{
		parent::__construct($options);
	}

	function getOrderByCompanyId($companyId, $valid = 0)
	{
		$info = array();

		$sql = "SELECT * FROM `".$this->tableOrder['name']."` 
				WHERE `company_id` = ?";
				
		if ($valid == 1) {
			$sql .= "  AND `valid` = '1' ";
		}

		$stmt = $this->db->query($sql, array($companyId));
		if ($row = $stmt->fetch()) {
			$info = $row;
		}

		return $info;
	}
}
?>