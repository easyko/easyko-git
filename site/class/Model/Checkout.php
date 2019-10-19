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

	public function getCompany($companyId) {
		$list = array();

		$sql = "SELECT `company_id` AS companyId, `company_no` AS companyNo, `company_name` AS companyName, 
					`contact_name` AS contactName, `email`, `industry_id`, `valid`, `product_id` AS productId, 
					`recommand_id` AS recommandId
				FROM `ek_company` 
				WHERE `company_id` = '".$companyId."'";

		$stmt = $this->db->query($sql, array($phone));
		if ($row = $stmt->fetch()) {
			$list = $row;
		}

		return $list;
	}
	public function getList()
	{
		$list = array();

		$sql = "SELECT `product_id` AS productId, `name`, `line_price` AS linePrice, 
						`max_people` AS maxPeople, `price`, `months`, `add_price` AS addPrice,
						`comment`, `sale_tag` AS saleTag, `server_contents` AS serverContents,
						`server_ids` AS serverIds, `valid`
				FROM `{$this->tableProduct['name']}`
				WHERE `valid` = '1' 
				ORDER BY `ord` ASC,`product_id` DESC";

		if ($stmt = $this->db->query($sql)) {
			while ($row = $stmt->fetch()) {

				$row['serverContents'] = explode('|', $row['serverContents']);

				$list[] = $row;
			}
		}

		return $list;
	}

	/**
	 * 获取服务选项
	 */
	public function getServerList($serverIds = '')
	{
		$list = array();

		$sql = "SELECT `server_id`, `name` FROM `{$this->tableServer['name']}`
				WHERE `valid` = 1";
				
		if ($serverIds != '') {
			$sql .= " AND `server_id` IN ({$serverIds})";
		}

		if ($stmt = $this->db->query($sql)) {
			while ($row = $stmt->fetch()) {
				$list[] = $row;
			}
		}
		
		return $list;
	}
}
?>
