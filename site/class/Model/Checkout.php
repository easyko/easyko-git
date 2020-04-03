<?php
/**
 * Product model
 *
 * @desc	产品
 * @author	jerry.cao (caowlong163@163.com)
 * $date    2019-05-18 23:49:31
 */

class Model_Checkout extends Fuse_Model
{

	private $tableProduct = array('name' => 'ek_product', 'key' => 'product_id');
	private $tableServer  = array('name' => 'ek_product_server', 'key' => 'server_id');

	public function __construct($config=array())
	{
		parent::__construct($config);
	}

	public function addOrder($data) {
		$order_no = $data['order_no'];
		$company_id = $data['company_id'];
		$product_id = $data['product_id'];
		$start_date = $data['start_date'];
		$end_date = $data['end_date'];
		$extra_nums = $data['extra_nums'];
		$price = $data['price'];
		$extra_price = $data['extra_price'];
		$total_price = $data['total_price'];
		$is_free = $data['is_free'];
		$valid = $data['valid'];
		$create_ip = $data['create_ip'];
		$sql = "INSERT INTO `ek_order` (`order_no`, `company_id`, `product_id`, `start_date`, `end_date`, `extra_nums`, `price`, `extra_price`, `total_price`, `is_free`, `valid`, `create_time`, `create_ip`, `update_time`, `payment_time`, `pay_note`) 
VALUES ('$order_no', '$company_id', '$product_id', '$start_date', '$end_date', '$extra_nums', '$price', '$extra_price', '$total_price', '$is_fee', '$valid', NOW(), '', '$create_ip', '', '')";
		//echo $sql;exit;
		$this->db->query($sql);
		$role_id_1 = $this->db->lastInsertId();
		return $role_id_1;
	}

	public function getOrderById($orderId) {
		$list = array();

		$sql = "SELECT * FROM `ek_order` WHERE `order_id` = '".$orderId."'";

		$stmt = $this->db->query($sql, array($phone));
		if ($row = $stmt->fetch()) {
			$list = $row;
		}

		return $list;
	}
}
?>
