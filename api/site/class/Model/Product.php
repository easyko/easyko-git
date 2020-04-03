<?php
class Model_Product extends Fuse_Model
{
	private $tableProduct = array('name' => 'ek_product', 'key' => 'product_id');
	private $tableUser = array('name' => 'ek_user', 'key' => 'user_id');

	public function __construct($options = array())
	{
		parent::__construct($options);
	}

	function getProductById($id)
	{
		$info = array();

		$sql = "SELECT * FROM `".$this->tableProduct['name']."` 
				WHERE `{$this->tableProduct['key']}` = ?";

		$stmt = $this->db->query($sql, array($id));
		if ($row = $stmt->fetch()) {
			$info = $row;
		}

		return $info;
	}
}
?>