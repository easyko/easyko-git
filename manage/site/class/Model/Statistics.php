<?php
/**
 * Statistics model
 *
 * @desc	统计
 * @author	jerry.cao (caowlong163@163.com)
 * $date    2019-08-16 23:58:06
 */

class Model_Statistics extends Fuse_Model
{
	private $tableUser = array('name' => 'ek_user', 'key' => 'user_id');
	private $tableCompany = array('name' => 'ek_company', 'key' => 'company_id');
	private $tableProduct = array('name' => 'ek_product', 'key' => 'product_id');

	public function __construct($config=array())
	{
		parent::__construct($config);
	}

	/**
	 * 公司数量
	 */
	public function getTotalCompany($where = "1"){
		$sql = "SELECT COUNT(`company_id`) AS total FROM `{$this->tableCompany['name']}` WHERE {$where}";
		$row = $this->getRow($sql);
		return isset($row['total']) ? $row['total'] : 0;
	}

	/**
	 * 用户数量
	 */
	public function getTotalMember($where = "1"){
		$sql = "SELECT COUNT(`user_id`) AS total FROM `{$this->tableUser['name']}` WHERE {$where}";
		$row = $this->getRow($sql);
		return isset($row['total']) ? $row['total'] : 0;
	}

	/**
	 * 付费用户数量
	 * 
	 *    先查询出付费企业再查对应的人数
	 */
	public function getTotalCompanyByTotal($date, $where = "1"){
		$total = 0;
		$sql = "SELECT DISTINCT `company_id` FROM `{$this->tableCompany['name']}` WHERE {$where}";
		$rows = $this->getRowSet($sql);
		if (!empty($rows)) {
			foreach ($rows as $row) {
				$sql = "SELECT COUNT(`user_id`) AS total FROM `{$this->tableUser['name']}` 
						WHERE `company_id` = '{$row['company_id']}' AND LEFT(`create_time`, 10) = '{$date}'";
				$info = $this->getRow($sql);
				
				$total += isset($info['total']) ? $info['total'] : 0;
			}
		}
		
		return $total;
	}

	/**
	 * 根据产品类型分类统计总数
	 */
	public function getProdectByType($isPayed = "1")
	{
		$sql = "SELECT CONCAT(p.`name`, '总数') AS `name`, COUNT(c.`product_id`) AS total
				FROM `{$this->tableCompany['name']}` c
				LEFT JOIN `{$this->tableProduct['name']}` p ON c.`product_id` = p.`product_id`
				WHERE c.`is_payed` = '{$isPayed}'
				GROUP BY c.`product_id`
				ORDER BY p.`ord` ASC";

		return $this->getRowSet($sql);
	}

	public function getTableUserName()
	{
		return $this->tableUser['name'];
	}
	
	public function getTableUserKey()
	{
		return $this->tableUser['key'];
	}
}
?>
