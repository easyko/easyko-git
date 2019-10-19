<?php
/**
 * Project model
 *
 * @desc	项目列表
 * @author	jerry.cao (caowlong163@163.com)
 * $date    2016-03-19 22:45:45
 */

class Model_Project extends Fuse_Model
{

	private $tableProject = array('name' => 'ek_project', 'key' => 'project_id');
	private $tableUser 	  = array('name' => 'ek_user', 'key' => 'user_id');
	private $tableTask    = array('name' => 'ek_project_task', 'key' => 'task_id');


	public function __construct($config=array())
	{
		parent::__construct($config);

		$this->userId = Fuse_Cookie::getInstance()->user_id;
		$this->roleId = Fuse_Cookie::getInstance()->role_id;
	}

	public function getList($start=0, $per_page=10, $where=1, $statList)
	{
		$list = array();
		$sql = "SELECT '' as itemNo,tp.project_no as projectNo,tp.project_name as projectName,
					tp.customer_name as customerName,tu.username as managerName,
					tp.task_users as execUsersIds, tp.start_date as startDate,
					tp.finished_date as finishDate, '0' as taskNums, '0' as projectTime, 
					'0' as workHours,tp.status
				FROM `{$this->tableProject['name']}` as tp
				LEFT JOIN `{$this->tableUser['name']}` as tu ON tp.`project_manager_id` = tu.`user_id`
				WHERE {$where} AND tp.`valid` = '1'";

		$sql .= " ORDER BY tp.`{$this->tableProject['key']}` DESC";

		if ($start>=0 && !empty($per_page)) {
			$sql .= " LIMIT {$start},{$per_page}";
		}

		if ($stmt = $this->db->query($sql)) {
			while ($row = $stmt->fetch()) {
				// 格式化
				$row['startDate'] = $row['startDate'];
				$row['status'] = !empty($row['status']) ? $statList[$row['status']]['statusName'] : '';
				$row['execUsersIds'] = $this->getExceUsers($row['execUsersIds']);

                $list[] = $row;
			}
		}

		return $list;
	}

	public function checkProjectNoExists($projectNo)
	{
		$list = array();
		$sql = "SELECT `project_no` FROM `{$this->tableProject['name']}`
				WHERE `project_no` = '{$projectNo}' AND `valid` = '1' LIMIT 1";

		if ($stmt = $this->db->query($sql)) {
			if ($row = $stmt->fetch()) {
				$list = $row;
			}
		}

		return !empty($list) ? true : false;
	}

	/**
	 * 取1天前的无用的项目数据，删除
	 *
	 */
	public function checkEmptyProjectData()
	{
		$date = date('Y-m-d', time() - 3600*24);

		$projectNos = array();
		$sql = "SELECT `project_no` FROM `{$this->tableProject['name']}`
				WHERE `valid` = '0' AND LEFT(`time`, 10) <= '{$date}'";

		if ($stmt = $this->db->query($sql)) {
			while ($row = $stmt->fetch()) {
				$projectNos[] = $row['project_no'];
			}
		}

		if (empty($projectNos)) {
			return;
		}

		$projectNos = "'" . implode("','", $projectNos) . "'";
		$this->db->query("DELETE FROM `{$this->tableProject['name']}` WHERE `project_no` IN ({$projectNos})");
	}

	/**
	 * 取1天前的无用的执行人数据，删除
	 *
	 */
	public function checkEmptyProjectExceUserData()
	{
		$date = date('Y-m-d', time() - 3600*24);

		$execuserIds = array();
		$sql = "SELECT `{$this->tableTask['key']}` FROM `{$this->tableTask['name']}`
				WHERE `valid` = '0' AND LEFT(`time`, 10) <= '{$date}'";

		if ($stmt = $this->db->query($sql)) {
			while ($row = $stmt->fetch()) {
				$execuserIds[] = $row['execuser_id'];
			}
		}

		if (empty($execuserIds)) {
			return;
		}

		$execuserIds = "'" . implode("','", $execuserIds) . "'";
		$this->db->query("DELETE FROM `{$this->tableTask['name']}` WHERE `{$this->tableTask['key']}` IN ({$execuserIds})");
	}

	public function getTotal($where = 1)
	{
		$total = 0;

		$list = array();
		$sql = "SELECT count(*) as total FROM `{$this->tableProject['name']}` as tp
				LEFT JOIN `{$this->tableUser['name']}` as tu ON tp.`project_manager_id` = tu.`user_id`
				WHERE {$where} AND tp.`valid` = '1'";

		if ($stmt = $this->db->query($sql)) {
			if ($row = $stmt->fetch()) {
				$total = $row['total'];
			}
		}

		return $total;
	}

	public function getExceUsers($users)
	{
		$userHtml = '';
		if (empty($users)) {
			return $userHtml;
		}

		$userSql = "'" . str_replace(",", "','", $users) . "'";

		$sql = "SELECT `username`
				FROM `{$this->tableUser['name']}`
				WHERE `user_id` IN ({$userSql})
				ORDER BY `user_id` ASC";

		$lib = '';

		if ($stmt = $this->db->query($sql)) {
			while ($row = $stmt->fetch()) {
				$userHtml .= $lib . $row['username'];
				$lib = ' ';
			}
		}

		return $userHtml;
	}

	public function getProjectNo($location)
	{
		// 删除1天前的所有无用数据
		$this->checkEmptyProjectData();
		$this->checkEmptyProjectExceUserData();

		Fuse_Cookie::getInstance()->project_no    = '';
		Fuse_Cookie::getInstance()->project_no_id = '';

		// 将旧的项目资料cookie和合同资料cookie删除
		// Fuse_Cookie::getInstance()->projectFile = '';
		// Fuse_Cookie::getInstance()->projectContractFile = '';
		$_SESSION['projectFile'] 		 	='';
		$_SESSION['projectContractFile']    ='';
		$_SESSION['projectProposalFile']    = '';
		$_SESSION['projectMeetingNoteFile'] = '';

		$projectNo = $location . date('ym') . '001';
		$currMon = date('Y-m');
		$sql = "SELECT `project_no` FROM `{$this->tableProject['name']}`
				WHERE LEFT(`time`, 7) = '{$currMon}' ORDER BY `project_id` DESC LIMIT 1";
		if ($stmt = $this->db->query($sql)) {
			if ($row = $stmt->fetch()) {
				$projectNo = $row['project_no'];
				$projectNo = substr($projectNo, 2);
				$projectNo++;
				$projectNo = $location . $projectNo;
			}
		}

		$object = array(
			'project_no' 	   => $projectNo,
			'last_mod_user_id' => Fuse_Cookie::getInstance()->user_id,
			'last_mod_time'    => Config_App::getTime(),
			'valid' 		   => '0',
			'ip' 	  		   => Config_App::getIP(),
			'time' 	 		   => Config_App::getTime()
		);

		$model = new Fuse_Model();
		$returnId = $model->store($this->tableProject['name'], $object);
		if ($returnId) {
			Fuse_Cookie::getInstance()->project_no    = $projectNo;
			Fuse_Cookie::getInstance()->project_no_id = $returnId;
		}

		return $returnId ? $projectNo : 0;
	}

	/**
	 * 获取任务单号
	 */
	public function getTaskNo($companyId)
	{
		$taskNo = date('md') . $companyId . '001';
		$currDay = date('Y-m-d');
		$sql = "SELECT `task_no` FROM `{$this->tableTask['name']}`
				WHERE LEFT(`time`, 10) = '{$currDay}' 
				ORDER BY `{$this->tableTask['key']}` DESC 
				LIMIT 1";
		if ($stmt = $this->db->query($sql)) {
			if ($row = $stmt->fetch()) {
				$taskNo = $row['task_no'];
				$taskNo++;
			}
		}

		$model = new Fuse_Model();
		$object = array(
			'company_id' => $companyId,
			'task_no' 	 => $taskNo,
			'valid'  	 => '0',
			'ip' 	 	 => Config_App::getIP(),
			'time' 	 	 => Config_App::getTime()
		);
		$returnId = $model->store($this->tableTask['name'], $object);

		$result = array(
			'no' => $taskNo,
			'id' => $returnId
		);

		return $result;
	}

	public function getProjectDetail($companyId, $projectNo, $typeList)
	{
		$list = array();

		$sql = "SELECT project.*, users.username AS managerName
				FROM `{$this->tableProject['name']}` project
				LEFT JOIN `{$this->tableUser['name']}` users ON project.project_manager_id = users.user_id
				WHERE project.`project_no` = '{$projectNo}' 
					AND project.`company_id` = '{$companyId}' 
				LIMIT 1";
		if ($stmt = $this->db->query($sql)) {
			if ($row = $stmt->fetch()) {
				/*$userInfo = $this->userInfo($row['project_manager_id']);
				if (!empty($userInfo['username'])) {
					$row['manager_name'] = $userInfo['username'];
				} else {
					$row['manager_name'] = '';
				}*/
				
				// 项目周期
				$row['projectTime'] = str_replace('-', '.', $row['start_date']) . '-';
				if ($row['finished_date'] == '') {
					$row['projectTime'] .= '至今';
				} else {
					$row['projectTime'] .= str_replace('-', '.', $row['finished_date']);
				}
				
				// 项目workTime工时
				$row['workTime'] = $this->getProjectWorkTime($companyId, $row['project_id']);

				$dir = $projectNo . '/project_info/';
				$attachment = !empty($row['attachment']) ? explode(',', $row['attachment']) : array();
				$row['attachment'] = array();
				if (!empty($attachment)) {
					$newList = array();
					foreach ($attachment as $v) {
						if (is_array($v)) {
							$newList[] = $v;
						} else {
							$newList[] = array(
								'name' => $v,
								'url' => $dir . $v
							);
						}
					}
					unset($row['attachment']);
					$row['attachment'] = $newList;
				}

				$dir = $projectNo . '/contract_info/';
				$contractAttachment = !empty($row['contract_attachment']) ? explode(',', $row['contract_attachment']) : array();
				$row['contractAttachment'] = array();
				if (!empty($contractAttachment)) {
					$newList = array();
					foreach ($contractAttachment as $v) {
						if (is_array($v)) {
							$newList[] = $v;
						} else {
							$newList[] = array(
								'name' => $v,
								'url' => $dir . $v
							);
						}
					}
					unset($row['contractAttachment']);
					$row['contractAttachment'] = $newList;
				}

				$dir = $projectNo . '/proposal_info/';
				$proposalAttachment = !empty($row['proposal_attachment']) ? explode(',', $row['proposal_attachment']) : array();
				$row['proposalAttachment'] = array();
				if (!empty($proposalAttachment)) {
					$newList = array();
					foreach ($proposalAttachment as $v) {
						if (is_array($v)) {
							$newList[] = $v;
						} else {
							$newList[] = array(
								'name' => $v,
								'url' => $dir . $v
							);
						}
					}
					unset($row['proposalAttachment']);
					$row['proposalAttachment'] = $newList;
				}

				$dir = $projectNo . '/meetingnote_info/';
				$meetingNoteAttachment = !empty($row['meetingnote_attachment']) ? explode(',', $row['meetingnote_attachment']) : array();
				$row['meetingnoteAttachment'] = array();
				if (!empty($meetingNoteAttachment)) {
					$newList = array();
					foreach ($meetingNoteAttachment as $v) {
						if (is_array($v)) {
							$newList[] = $v;
						} else {
							$newList[] = array(
								'name' => $v,
								'url' => $dir . $v
							);
						}
					}
					unset($row['meetingnoteAttachment']);
					$row['meetingnoteAttachment'] = $newList;
				}

				$row['taskUsersList'] = $this->getTaskUserList($row['project_id'], $row['project_no'], $typeList);
				$row['taskUsers'] = array();
				if (!empty($row['taskUsersList'])) {
					$taskUsers = array();
					foreach ($row['taskUsersList'] as $val) {
						$taskUsers[] = $val['username'];
					}
					$row['taskUsers'] = implode('、', $taskUsers);
				}

				$row['contractAmount'] = $row['contract_amount']*1;
				$row['realAmount']     = $row['real_amount']*1;
				$row['otherAmount']    = $row['other_amount']*1;

				if (strpos($row['project_desc'], "<br />") === false) {
					$row['projectDesc'] = str_replace("\n", "<br />", $row['project_desc']);
				}
				$row['projectDesc'] = htmlentities($row['project_desc']);

				$list = $row;
			}
		}

		return $list;
	}

	/**
	 * 获取任务执行人员列表
	 */
	public function getTaskUserList($projectId, $projectNo, $typeList)
	{
		$list = array();
		$sql = "SELECT tu.`username`,eu.`user_id`,eu.`type`,eu.`task_id`,
				eu.`task_no`,eu.`attachment`,eu.`finished_time`,eu.`start_time`,
				eu.`end_time`,eu.`work_unit`,eu.`plan_score`,eu.`real_score`
				FROM `{$this->tableTask['name']}` as eu
				LEFT JOIN `{$this->tableUser['name']}` as tu ON eu.`user_id` = tu.`user_id`
				WHERE eu.`project_id` = '{$projectId}' AND eu.`valid` = '1'
				ORDER BY eu.`time` ASC";

		if ($stmt = $this->db->query($sql)) {
			while ($row = $stmt->fetch()) {
				$row['type_id'] = $row['type'];
				$row['type'] = isset($typeList[$row['type']]) ? $typeList[$row['type']] : '';

				if (empty($row['finished_time']) || $row['finished_time'] == '0000-00-00 00:00:00') {
					$row['finished_time'] = '--';
				} else {
					$row['finished_time'] = substr($row['finished_time'], 0, 10);
				}

				if (empty($row['start_time']) || $row['start_time'] == '0000-00-00 00:00:00') {
					$row['start_time'] = '--';
				} else {
					$row['start_time'] = substr($row['start_time'], 0, 10);
				}

				if (empty($row['end_time']) || $row['end_time'] == '0000-00-00 00:00:00') {
					$row['end_time'] = '--';
				} else {
					$row['end_time'] = substr($row['end_time'], 0, 10);
				}

				$dir = $projectNo . '/' . $row['task_no'] . '/';
				$attachment = unserialize($row['attachment']);
				if (!empty($attachment)) {
					$newList = array();
					foreach ($attachment as $v) {
						if (is_array($v)) {
							$newList[] = $v;
						} else {
							$newList[] = array(
								'name' => $v,
								'url' => $dir . $v
							);
						}
					}
					unset($row['attachment']);
					$row['attachment'] = $newList;
				} else {
					$row['attachment'] = array();
				}

				$list[] = $row;
			}
		}

		return $list;
	}

	public function userInfo($id)
	{
		$list = array();

		$sql = "SELECT * FROM `{$this->tableUser['name']}` WHERE `user_id` = '{$id}' LIMIT 1";
		if ($stmt = $this->db->query($sql)) {
			if ($row = $stmt->fetch()) {
				$list = $row;
			}
		}

		return $list;
	}

	public function delUser($productId, $userId, $jobNo)
	{
		$sql = "DELETE FROM `{$this->tableTask['name']}` WHERE `project_id` = '{$productId}' AND `user_id` = '{$userId}' AND `job_no` = '{$jobNo}' LIMIT 1";

		return $this->db->query($sql);
	}

	public function updateProjectUserData($productId)
	{
		$info = array();

		$sql = "SELECT * FROM `{$this->tableTask['name']}` WHERE `{$this->tableProject['key']}` = '{$productId}' AND `valid` = '1' ORDER BY `{$this->tableTask['key']}` ASC";

		if ($stmt = $this->db->query($sql)) {
			while ($row = $stmt->fetch()) {
				$info[] = $row['user_id'];
			}
		}

		if (empty($info)) {
			return;
		}

		$info = array_values($info);
		$info = array_unique($info);
		$info = implode(',', $info);

		$sqlUpdate = "UPDATE `{$this->tableProject['name']}` SET `task_users` = '{$info}' WHERE `{$this->tableProject['key']}` = '{$productId}'";

		return $this->db->query($sqlUpdate);
	}

	public function formatMoney($money)
	{
		return number_format($money, 2, '.', ',');
	}
	
	/**
	 * 统计分析 - 项目统计 - 项目统计数量
	 * 
	 * inProgress 进行中
	 * cancel 已取消
	 * completed 已完成
	 * percent 完成率
	 */
	public function projectStatisticsTotal($where = 1)
	{
		$info = array(
			'inProgress' => 0,
			'completed'  => 0,
			'cancel'     => 0,
			'percent'    => 0
		);
		
		// 项目状态 1:执行中 2:已完成 3:已取消 4:待回复
		$sql = "SELECT COUNT(`project_id`) AS count, `status` 
				FROM `{$this->tableProject['name']}`
				WHERE {$where}
				GROUP BY `status`";

		if ($stmt = $this->db->query($sql)) {
			while ($row = $stmt->fetch()) {
				if ($row['status'] == 1) {
					$info['inProgress'] += $row['count'];
				} else if ($row['status'] == 2) {
					$info['completed'] += $row['count'];
				} else if ($row['status'] == 3) {
					$info['cancel'] += $row['count'];
				}
			}
		}

		// 总数
		$total = $row['status'] + $info['completed'] + $info['cancel'];

		if ($total > 0) {
			$info['percent'] = Fuse_Tool::getFormatMoneyInt($info['completed'] / $total);
			$info['percent'] = $info['percent'] * 100 . '%';
		}

		return $info;
	}
	
	/**
	 * 统计分析 - 项目统计 - 获取项目列表
	 */
	public function getListProject($start = 0, $perPage = 10, $where = 1)
	{
		$list = array();

		$sql = "SELECT project_id AS projectId,
					project_no AS projectNo,
					project_name AS projectName,
					customer_name AS customerName,
					project_manager_id AS managerName,
					task_users AS execUsersIds,
					start_date AS createDate,
					finished_date AS finishedDate,
					cancel_date AS cancelDate,
					0 AS workDate,
					0 AS workTime,
					`status`,
					company_id AS companyId
				FROM `{$this->tableProject['name']}` 
				WHERE {$where}
				ORDER BY `{$this->tableProject['key']}` DESC";

		if ($start >= 0 && !empty($perPage)) {
			$sql .= " LIMIT {$start},{$perPage}";
		}

		if ($stmt = $this->db->query($sql)) {
			while ($row = $stmt->fetch()) {
				// 执行人
				$row['execUsersIds'] = $this->getExceUsers($row['execUsersIds']);
				
				// 计算workDate项目时长 workTime项目工时				
				// 项目状态 1:执行中 2:已完成 3:已取消 4:待回复
				$row['workDate'] = '';
				if (!in_array($row['status'], array(2, 3)) && (time() - strtotime($row['createDate'])) > 0) {
					$row['workDate'] = Fuse_Tool::getDaysBetweenTwoDays(Config_App::getDate(), $row['createDate']);
				} else if ($row['status'] == 2) {
					$row['workDate'] = Fuse_Tool::getDaysBetweenTwoDays($row['finishedDate'], $row['createDate']);
				} else if ($row['status'] == 3) {
					$row['workDate'] = Fuse_Tool::getDaysBetweenTwoDays($row['cancelDate'], $row['createDate']);
				}
				
				// 项目workTime工时
				$row['workTime'] = $this->getProjectWorkTime($row['companyId'], $row['projectId']);
				
				unset($row['finishedDate']);
				unset($row['cancelDate']);
				unset($row['projectId']);
				unset($row['companyId']);
				$list[] = $row;
			}
		}

		return $list;
	}
	
	/**
	 * 统计分析 - 项目统计 - 计算项目工时
	 */
	private function getProjectWorkTime($companyId, $projectId)
	{
		$days = 0;
		
		$sql = "SELECT finished_time AS finishedTime,
					start_time AS startTime,
					end_time AS endTime
				FROM `{$this->tableTask['name']}` 
				WHERE `company_id` = '{$companyId}' 
					AND `project_id` = '{$projectId}'";

		if ($stmt = $this->db->query($sql)) {
			while ($row = $stmt->fetch()) {
				// 如果完成，那完成日期与开始日期对比
				if ($row['finishedTime'] != '') {
					$days += Fuse_Tool::getDaysBetweenTwoDays($row['finishedTime'], $row['startTime']);
				} 
				// 否则，当前日期和开始日期对比
				else if ((time() - strtotime($row['startTime'])) > 0) {
					$days += Fuse_Tool::getDaysBetweenTwoDays(Config_App::getDate(), $row['startTime']);
				}
			}
		}
		
		return ceil($days);
	}
	
	/**
	 * 统计分析 - 项目统计 - 获取项目列表总记录数
	 */
	public function getTotalProject($where = 1)
	{
		$info = array();

		$sql = "SELECT COUNT(`{$this->tableProject['key']}`) AS count
				FROM `{$this->tableProject['name']}`
				WHERE {$where}";
		if ($stmt = $this->db->query($sql)) {
			if ($row = $stmt->fetch()) {
				$info = $row['count'];
			}
		}

		return $info;
	}
	
	/**
	 * 统计分析 - 任务统计 - 任务统计数量
	 */
	public function taskStatisticsTotal($where, $companyId)
	{
		$statisticsList = array(
			'created' 		   => 0,  // 已创建
			'inProgress' 	   => 0,  // 进行中
			'completed'        => 0,  // 已完成
			'delayed'          => 0,  // 已延期
			'cancel'     	   => 0,  // 已取消
			'completedPercent' => '', // 任务完成率
			'delayedPercent'   => ''  // 任务延期率
        );
        
        $list = array();

		// 项目状态 1:执行中 2:已完成 3:已取消 4:待回复

		$sql = "SELECT project_id AS projectId, `status`, `valid`
				FROM `{$this->tableProject['name']}` 
				WHERE {$where}";
				
		if ($stmt = $this->db->query($sql)) {
			while ($row = $stmt->fetch()) {
				// 查询出此项目下的所有执行工单
				$itemList = $this->getWorkOrderList($companyId, $row['projectId']);
				
				$statisticsList['created'] += count($itemList);
				
				if ($row['status'] == 3) {
					$statisticsList['cancel'] += count($itemList);
				}

				if (count($itemList) > 0) {
					foreach ($itemList as $item) {
						if (time() > strtotime($item['startTime']) && time() <= strtotime($item['endTime'])) {
							$statisticsList['inProgress'] += 1;
						}
						
						if ($item['finishedTime'] != '' 
							&& strtotime($item['finishedTime']) > strtotime($item['startTime'])
							&& strtotime($item['finishedTime']) <= strtotime($item['endTime'])
						) {
							$statisticsList['completed'] += 1;
						}
						
						if ($item['finishedTime'] == '' && time() > strtotime($item['endTime'])) {
							$statisticsList['delayed'] += 1;
						}
					}
				}
			}
		}
		
		if ($statisticsList['created'] > 0) {
			$statisticsList['completedPercent'] = Fuse_Tool::getFormatMoneyInt($statisticsList['completed'] / $statisticsList['created']);
			$statisticsList['completedPercent'] = $statisticsList['completedPercent'] * 100 . '%';
			
			$statisticsList['delayedPercent'] = Fuse_Tool::getFormatMoneyInt($statisticsList['delayed'] / $statisticsList['created']);
			$statisticsList['delayedPercent'] = $statisticsList['delayedPercent'] * 100 . '%';
		}

		return $statisticsList;
	}
	
	/**
	 * 统计分析 - 任务统计 - 项目工单详情查询
	 */
	private function getWorkOrderList($companyId, $projectId = '', $userId = '')
	{
		$list = array();
		
		$sql = "SELECT `execuser_id` AS execuser_id, job_no AS jobNo,
					`start_time` AS startTime, `end_time` AS endTime, 
					`finished_time` AS finishedTime
				FROM `{$this->tableTask['name']}` 
				WHERE `company_id` = '{$companyId}'";
		
		if ($projectId != '') {
			$sql .= " AND `project_id` = '{$projectId}'";
		}
			
		if ($userId != '') {
			$sql .= " AND `user_id` = '{$userId}'";
		}
				
		if ($stmt = $this->db->query($sql)) {
			while ($row = $stmt->fetch()) {
				$list[] = $row;
			}
		}
		
		return $list;
	}
	
	
	/**
	 * 统计分析 - 成员进度 - 查询成员进度记录总数
	 */
	public function getTotalProgressStatistics($where)
	{
		$info = array();

		$sql = "SELECT COUNT(`user_id`) AS count
				FROM `{$this->tableUser['name']}`
				WHERE `company_id` = '{$where['companyId']}'";
		if ($stmt = $this->db->query($sql)) {
			if ($row = $stmt->fetch()) {
				$info = $row['count'];
			}
		}

		return $info;
	}
	
	/**
	 * 统计分析 - 成员进度 - 查询成员进度列表
	 */
	public function getListProgressStatistics($start = 0, $perPage = 10, $where = array())
	{
		$list = array();
		
		/*$sql = "SELECT tu.`user_id` AS userId, tu.username AS member,
					(
						SELECT COUNT(DISTINCT `project_id`) 
						FROM `{$this->tableTask['name']}` teu
						WHERE tu.`company_id` = tu.`company_id` 
						AND teu.`user_id` = tu.`user_id`
					) AS joinNum
				FROM `{$this->tableUser['name']}` tu
				WHERE `company_id` = '{$where['companyId']}'";*/
		$sql = "SELECT tu.`user_id` AS userId, tu.username AS member
				FROM `{$this->tableUser['name']}` tu
				WHERE `company_id` = '{$where['companyId']}'";

		$sql .= " ORDER BY tu.`{$this->tableUser['key']}` DESC";

		if ($start>=0 && !empty($per_page)) {
			$sql .= " LIMIT {$start},{$perPage}";
		}

		if ($stmt = $this->db->query($sql)) {
			$i = 0;
			while ($row = $stmt->fetch()) {
				$statisticsList = array(
					'itemNo'	   => ($where['page'] - 1) * $perPage + $i + 1,
					'member'	   => $row['member'], 
					'joinNum' 	   => 0,  // 参与项目数
					'joiningNum'   => 0,  // 进行中任务
					'finishedNum'  => 0,  // 已完成任务
					'delayedNum'   => 0,  // 已延期任务
					'finishedRate' => '', // 任务完成率
					'delayedRate'  => ''  // 任务延期率
				);       
				
				// 查询出此项目下的所有执行工单
				$itemList = $this->getWorkOrderList($where['companyId'], '', $row['userId']);
				
				$statisticsList['joinNum'] += count($itemList);
				if (count($itemList) > 0) {
					foreach ($itemList as $item) {
						if (time() > strtotime($item['startTime']) && time() <= strtotime($item['endTime'])) {
							$statisticsList['joiningNum'] += 1;
						}
						if ($item['finishedTime'] != '' 
							&& strtotime($item['finishedTime']) > strtotime($item['startTime'])
							&& strtotime($item['finishedTime']) <= strtotime($item['endTime'])
						) {
							$statisticsList['finishedNum'] += 1;
						}
						if ($item['finishedTime'] == '' && time() > strtotime($item['endTime'])) {
							$statisticsList['delayedNum'] += 1;
						}
					}
				}
				if ($statisticsList['joinNum'] > 0) {
					$statisticsList['finishedRate'] = Fuse_Tool::getFormatMoneyInt($statisticsList['finishedNum'] / $statisticsList['joinNum']);
					$statisticsList['finishedRate'] = $statisticsList['finishedRate'] * 100 . '%';
					
					$statisticsList['delayedRate'] = Fuse_Tool::getFormatMoneyInt($statisticsList['delayedNum'] / $statisticsList['joinNum']);
					$statisticsList['delayedRate'] = $statisticsList['delayedRate'] * 100 . '%';
				}

                $list[] = $statisticsList;
                unset($row);
                $i++;
			}
		}

		return $list;
	}
	
	/**
	 * 详情-任务列表
	 */
	public function getTaskList($companyId, $projectNo, $projectTypeList)
	{
		$list = array();

		$sql = "SELECT '' AS taskFile, task.task_no AS taskNo, task.task_desc AS taskDesc, task.type, 
					users.username AS userName, task.start_time AS startTime, task.end_time AS endTime, 
					'' AS taskTime, task.finished_time AS finishedTime, '' AS workHours, 
					task.plan_score AS planScore, task.real_score AS realScore, 
				project.project_id AS projectId, project.project_no AS projectNo
				FROM `{$this->tableTask['name']}` task
				LEFT JOIN `{$this->tableProject['name']}` project ON task.project_id = project.project_id
				LEFT JOIN `{$this->tableUser['name']}` users ON task.user_id = users.user_id
				WHERE task.`company_id` = '{$companyId}' 
					AND project.`project_no` = '{$projectNo}' 
					AND task.`valid` = 1 
				ORDER BY task.`task_id` DESC";

		if ($stmt = $this->db->query($sql)) {
			while ($row = $stmt->fetch()) {
				if ($row['finishedTime'] == '0000-00-00 00:00:00') {
					$row['finishedTime'] = '';
				}
				
				// 项目workTime工时
				$value['workTime'] = $this->getProjectWorkTime($companyId, $row['projectId']);
				
				// 计划日期
				$row['taskTime'] = Fuse_Tool::formatDateToStr($row['startTime'], $row['endTime']);
				
				// 类型
				$row['type'] = isset($projectTypeList[$row['type']]) ? $projectTypeList[$row['type']] : $row['type'];
				
				unset($row['startTime']);
				unset($row['endTime']);
				unset($row['projectId']);
				$list[] = $row;
			}
		}
		
		return $list;
	}
	
	public function getTableProjectName()
	{
		return $this->tableProject['name'];
	}
	
	public function getTableTaskName()
	{
		return $this->tableTask['name'];
	}
	
	public function getTableUserName()
	{
		return $this->tableUser['name'];
	}
}
?>
