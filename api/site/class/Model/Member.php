<?php
/**
 * Member model
 *
 * @desc	人员管理
 * @author	jerry.cao (caowlong163@163.com)
 * $date    2016-03-24 22:59:45
 */

class Model_Member extends Fuse_Model
{
	private $tableUser = array('name' => 'ek_user', 'key' => 'user_id');
	private $tableMenu = array('name' => 'ek_menu', 'key' => 'menu_id');
	private $tableRole = array('name' => 'ek_role', 'key' => 'role_id');
	private $tableRoleMenu = array('name' => 'ek_role_menu', 'key' => 'role_menu_id');
	private $tableCompany = array('name' => 'ek_company', 'key' => 'company_id');


	public function __construct($config=array())
	{
		parent::__construct($config);
	}
	
	/**
	 * 获取角色列表
	 */
	public function getRoleList($companyId)
	{
		$list = array();

		$sql = "SELECT role_id roleId, role_name roleName
				FROM `{$this->tableRole['name']}`
				WHERE `company_id` = '{$companyId}' AND `valid` = '1'
				ORDER BY `{$this->tableRole['key']}` ASC";

		if ($stmt = $this->db->query($sql)) {
			while ($row = $stmt->fetch()) {
				$list[] = $row;
			}
		}

		return $list;
	}

	/**
	 * 角色删除
	 */
	public function delRoleById($companyId, $roleId)
	{
		$time = Config_App::getTime();
		$sql = "UPDATE `{$this->tableRole['name']}` 
				SET `valid` = '0', `update_time` = '{$time}' 
				WHERE `role_id` = '{$roleId}' 
					AND `company_id` = '{$companyId}'";
				
		return $this->db->query($sql);
	}

	/**
	 * 角色编辑名称
	 */
	public function editRole($companyId, $roleId, $name)
	{
		$time = Config_App::getTime();
		$sql = "UPDATE `{$this->tableRole['name']}` 
				SET `role_name` = '{$name}', `update_time` = '{$time}' 
				WHERE `role_id` = '{$roleId}' 
					AND `company_id` = '{$companyId}' 
					AND `valid` = '1'";
				
		return $this->db->query($sql);
	}

	/**
	 * 根据角色id获取用户列表
	 */
	public function getMemberListByRoleId($companyId, $roleId)
	{
		$list = array();

		$sql = "SELECT user_id userId, username, user_no userNo, mobile, role_id, valid
				FROM `{$this->tableUser['name']}`
				WHERE `company_id` = '{$companyId}' AND `role_id` = '{$roleId}' 
				ORDER BY `{$this->tableUser['key']}` ASC";

		if ($stmt = $this->db->query($sql)) {
			while ($row = $stmt->fetch()) {
				unset($row['role_id']);
				
				$list[] = $row;
			}
		}

		return $list;
	}
	
	/**
	 * 根据公司id获取用户列表
	 */
	public function getMemberListByCompanyId($companyId, $valid = 0)
	{
		$list = array();

		$sql = "SELECT user_id userId, username, user_no userNo, 
				mobile, role_id roleId, valid, 
				department_id departmentId, job_id jobId,
				create_time createTime
				FROM `{$this->tableUser['name']}`
				WHERE `company_id` = '{$companyId}'";
				
		if ($valid == 1) {
			$sql .= " AND `valid` = '{$valid}' ";
		}
				
		$sql .= " ORDER BY `{$this->tableUser['key']}` ASC";

		if ($stmt = $this->db->query($sql)) {
			while ($row = $stmt->fetch()) {
				$row['registerDate'] = substr($row['createTime'], 0, 10);
				unset($row['createTime']);
				
				$list[] = $row;
			}
		}

		return $list;
	}
	
	/**
	 * 根据角色id获取用户列表的总记录数
	 */
	public function getMemberListTotal($where, $companyId, $roleId = '')
	{
		$info = 0;

		$sql = "SELECT COUNT(`user_id`) AS count
				FROM `{$this->tableUser['name']}`
				WHERE `company_id` = '{$companyId}'";
				
		if ($roleId != '') {
			$sql .= " AND `role_id` = '{$roleId}'";
		}
		$sql .= " AND {$where}";

		if ($stmt = $this->db->query($sql)) {
			if ($row = $stmt->fetch()) {
				$info = $row['count'];
			}
		}

		return $info;
	}
	
	/**
	 * 根据公司id和角色id获取角色信息
	 */
	private function getRoleInfoByCompanyIdAndRoleId($companyId, $roleId)
	{
		$info = array();

		$sql = "SELECT * FROM `{$this->tableRole['name']}` 
				WHERE `role_id` = '{$companyId}' AND `company_id` = '{$companyId}' 
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
	 * 根据角色id获取角色信息
	 */
	public function getRoleInfo($companyId, $roleId)
	{
		$info = $this->getRoleInfoByCompanyIdAndRoleId($companyId, $roleId);
		if (empty($info)) {
			return array();
		}
		
		$menus = '';
		$menusList = $this->getRoleMenuList($companyId, $roleId);
		if (!empty($menusList)) {
			foreach ($menusList as $key => $value) {
				$menus .= $value['menu_id'];
				if ($key + 1 != count($menusList)) {
					$menus .= ',';
				}
			}
		}
		
		$data = array(
			'roleName'  => $info['role_name'],
			'menusList' => $menusList,
			'menus'     => $menus
		);
		return $data;
	}
	
	/**
	 * 查询用户角色列表
	 */
	public function getRoleMenuList($companyId, $roleId)
	{
		$list = array();

		$sql = "SELECT * FROM `{$this->tableRoleMenu['name']}` 
				WHERE `company_id` = '{$companyId}' AND `role_id` = '{$roleId}'
				ORDER BY `{$this->tableRoleMenu['key']}` ASC";

		if ($stmt = $this->db->query($sql)) {
			while ($row = $stmt->fetch()) {
				$list[] = $row;
			}
		}

		return $list;
	}
	
	/**
	 * 判断用户唯一
	 */
	public function checkUnique($field, $value, $myId)
	{
		$list = array();

		$sql = "SELECT `user_id`,`username` FROM `{$this->tableUser['name']}`
				WHERE `user_id` != '{$myId}' AND `{$field}` = '{$value}' AND `valid` = '1' LIMIT 1";

		if ($stmt = $this->db->query($sql)) {
			if ($row = $stmt->fetch()) {
				$list = $row;
			}
		}

		if (!empty($list)) {
			return false;
		}

		return true;
	}
	
	/**
	 * 获取用户信息
	 */
	/*public function getUserInfo($userId)
	{
		$list = array();

		$sql = "SELECT user_id userId, company_id companyId, user_no userNo,
					username, login_name loginName, mobile, email, role_id roleId,
					department_id departmentId, job_id jobId
				FROM `{$this->tableUser['name']}` 
				WHERE `{$this->tableUser['key']}` = '{$userId}'";

		if ($stmt = $this->db->query($sql)) {
			if ($row = $stmt->fetch()) {
				$list = $row;
			}
		}

		return $list;
	}*/
	
	/**
	 * 根据当前用户的角色信息获取当前用户的菜单列表
	 */
	public function getCurrMenuListByRoleId($companyId, $roleId, $menus)
	{
		$list = array();

		if ($menus != '') {
			$menus = "'" . str_replace(',', "','", $menus) . "'";
		}

		$sql = "SELECT DISTINCT menu.`menu_id` AS menuId, menu.`name` AS menuName, menu.`menu_key` AS menuKey 
				FROM `{$this->tableMenu['name']}` menu, `{$this->tableRoleMenu['name']}` rolemenu
				WHERE menu.menu_id = rolemenu.menu_id AND menu.`parent_menu_id` = '0' 
				AND menu.`valid` = 1 AND rolemenu.`menu_id` IN ({$menus})
				ORDER BY menu.`{$this->tableMenu['key']}` ASC";

		if ($stmt = $this->db->query($sql)) {
			while ($row = $stmt->fetch()) {
				// 查询二级菜单信息
				$row['childList'] = $this->getCurrMenuListByFirstLevelMenuId($row['menuId'], $menus);
				$list[] = $row;
			}
		}

		return $list;
	}
	
	/**
	 * 根据一级菜单id获取指定范围内的二次菜单
	 */
	private function getCurrMenuListByFirstLevelMenuId($parentMenuId, $menus)
	{
		$list = array();

		$sql = "SELECT `menu_id` AS menuId, `name` AS menuName, `menu_key` AS menuKey 
				FROM `{$this->tableMenu['name']}` 
				WHERE `parent_menu_id` = '{$parentMenuId}' 
				AND `valid` = 1 AND `menu_id` IN ({$menus})";
		if ($stmt = $this->db->query($sql)) {
			while ($row = $stmt->fetch()) {
				$list[] = $row;
			}
		}

		return $list;
	}
	
	/**
	 * 查询当前公司总人数
	 */
	public function getMemberTotal($companyId)
	{
		$info = 0;

		$sql = "SELECT COUNT(`user_id`) AS count FROM `{$this->tableUser['name']}`
				WHERE `company_id` = '{$companyId}' AND `valid` = '1'";
		if ($stmt = $this->db->query($sql)) {
			if ($row = $stmt->fetch()) {
				$info = $row['count'];
			}
		}

		return $info;
	}
	
	/**
	 * 根据部门id查询列表
	 */
	public function getListByDepartmentId($companyId, $departmentId, $name = '')
	{
		$list = array();
		
		$sql = "SELECT tu.`user_id` AS userId, tu.username AS name
				FROM `{$this->tableUser['name']}` tu
				WHERE `company_id` = '{$companyId}' 
				AND `department_id` = '{$departmentId}' AND `valid` = 1";
				
		if ($name != '') {
			$sql .= " AND `username` LIKE '%{$username}%'";
		}
		
		if ($stmt = $this->db->query($sql)) {
			while ($row = $stmt->fetch()) {
				$list[] = $row;
			}
		}

		return $list;
	}
	
	public function getUserInfoById($companyId, $userId)
	{
		$info = array();
		
		$sql = "SELECT user_no AS userNo, username AS name, 
					login_name AS loginName, mobile, email
				FROM `{$this->tableUser['name']}` user
				WHERE `user_id` = '{$userId}' 
					AND `company_id` = '{$companyId}' 
					AND `valid` = 1";

		if ($stmt = $this->db->query($sql)) {
			if ($row = $stmt->fetch()) {
				$info = $row;
			}
		}

		return $info;
	}
	
	public function getTableRoleName()
	{
		return $this->tableRole['name'];
	}
	
	public function getTableUserName()
	{
		return $this->tableUser['name'];
	}
	
	public function getTableRoleMenuName()
	{
		return $this->tableRoleMenu['name'];
	}
}
?>
