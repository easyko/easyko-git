<?php
class Model_Company extends Fuse_Model
{
	private $tableCompany = array('name' => 'ek_company', 'key' => 'company_id');
	private $tableIndustry = array('name' => 'ek_company_industry', 'key' => 'industry_id');
	private $tableSize = array('name' => 'ek_company_size', 'key' => 'size_id');
	private $tableUser = array('name' => 'ek_user', 'key' => 'user_id');
	private $tableOrder = array('name' => 'ek_order', 'key' => 'order_id');
	private $tableProduct = array('name' => 'ek_product', 'key' => 'product_id');

	public function __construct($options = array())
	{
		parent::__construct($options);
	}

	function getCompanyById($id)
	{
		$info = array();

		$sql = "SELECT *, is_payed isPayed, expire_date expireDate FROM `".$this->tableCompany['name']."` 
				WHERE `".$this->tableCompany['key']."` = ?";

		$stmt = $this->db->query($sql, array($id));
		if ($row = $stmt->fetch()) {
			$info = $row;
		}

		return $info;
	}
	
	/**
	 * 查询公司名称及有效人数
	 */
	public function getSimpleInfo($companyId)
	{
		$info = array();

		$sql = "SELECT c.`company_name` AS companyName, 
					(
						SELECT COUNT(`{$this->tableUser['key']}`) 
						FROM {$this->tableUser['name']} u 
						WHERE u.company_id = c.company_id AND u.`valid` = '1'
					) AS userTotals,
					(
						SELECT LEFT(`end_date`, 10) 
						FROM `{$this->tableOrder['name']}` o 
						WHERE o.company_id = c.company_id AND o.`valid` = '1'
						ORDER BY o.`{$this->tableOrder['key']}` DESC
					) AS endDate
				FROM `{$this->tableCompany['name']}` c
				WHERE `company_id` = '{$companyId}' 
				AND `valid` = '1'
				LIMIT 1";
		if ($stmt = $this->db->query($sql)) {
			if ($row = $stmt->fetch()) {
				$info = $row;
			}
		}

		return $info;
	}
	
	/**
	 * 获取公司详情
	 */
	public function getCompanyInfo($companyId)
	{
		$info = $this->getCompanyById($companyId);
		return $info;
	}
	
	/**
	 * 企业行业
	 */
	public function getIndustryList()
	{
		$list = array();

		$sql = "SELECT industry_id industryId, `name` industryName
				FROM `{$this->tableIndustry['name']}`
				WHERE `valid` = '1'
				ORDER BY `{$this->tableIndustry['key']}` ASC";
		if ($stmt = $this->db->query($sql)) {
			while ($row = $stmt->fetch()) {
				$list[] = $row;
			}
		}

		return $list;
	}
	
	/**
	 * 企业规模
	 */
	public function getSizeList()
	{
		$list = array();

		$sql = "SELECT size_id sizeId, `name` sizeName
				FROM `{$this->tableSize['name']}`
				WHERE `valid` = '1'
				ORDER BY `{$this->tableSize['key']}` ASC";
		if ($stmt = $this->db->query($sql)) {
			while ($row = $stmt->fetch()) {
				$list[] = $row;
			}
		}

		return $list;
	}
}
?>