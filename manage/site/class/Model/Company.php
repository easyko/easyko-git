<?php
class Model_Company extends Fuse_Model
{
	private $tableCompany = array('name' => 'ek_company', 'key' => 'company_id');
	private $tableIndustry = array('name' => 'ek_company_industry', 'key' => 'industry_id');
	private $tableSize = array('name' => 'ek_company_size', 'key' => 'size_id');
	private $tableUser = array('name' => 'ek_user', 'key' => 'user_id');
	private $tableOrder = array('name' => 'ek_order', 'key' => 'order_id');

	public function __construct($options = array())
	{
		parent::__construct($options);
	}

	function getCompanyById($id)
	{
		$info = array();

		$sql = "SELECT *, is_free isFree, expire_date expireDate FROM `".$this->tableCompany['name']."` 
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
	public function getSampleInfo($companyId)
	{
		$info = array();

		$sql = "SELECT company.`company_name` AS companyName, 
					(
						SELECT COUNT(`{$this->tableUser['key']}`) 
						FROM {$this->tableUser['name']} user 
						WHERE user.company_id = company.company_id AND user.`valid` = '1'
					) AS userTotals,
					(
						SELECT LEFT(`end_date`, 10) 
						FROM `{$this->tableOrder['name']}` order 
						WHERE order.company_id = company.company_id AND order.`valid` = '1'
						ORDER BY `{$this->tableOrder['key']}` DESC
					) AS endDate
				FROM `{$this->tableCompany['name']}` company
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
	
	public function getCompanyByName($name)
	{
		$sql = "SELECT `company_no` AS companyNo, `company_name` AS companyName
				FROM `{$this->tableCompany['name']}` WHERE `company_name` LIKE '%{$name}%'";
		return $this->getRowSet($sql);
	}
	
	/**
	 * 获取公司详情
	 */
	public function getCompanyInfo($companyNo)
	{
		$sql = "SELECT `company_no` AS companyNo, `company_name` AS companyName, `email`, 
					`contact_name` AS contactName, `contact_phone` AS contactPhone, `address`,  
					`expire_date` AS expireDate, `industry_id` AS industryId, `product_id` AS productId,
					`recommand_id` AS recommandId, LEFT(`create_time`, 10) AS createTime 
				FROM `{$this->tableCompany['name']}` WHERE `company_no` = '{$companyNo}'";
		return $this->getRow($sql);
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