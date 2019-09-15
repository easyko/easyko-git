<?php
// 官网地址
$webUrl = 'pms.posher.cn';

// 项目区域
$locationList = array(
	'1' => array(
		'cn' => '上海',
		'en' => 'SH'
	),
	'2' => array(
		'cn' => '广州',
		'en' => 'GZ'
	),
	'3' => array(
		'cn' => '伦敦',
		'en' => 'LD'
	)
);

// 项目状态
$projectStatList = array(
	'1' => array(
		'statusName' => '执行中',
		'colorClass' => 'project_status_executing'
	),
	'2' => array(
		'statusName' => '已完成',
		'colorClass' => 'project_status_complete'
	),
	'3' => array(
		'statusName' => '已取消',
		'colorClass' => 'project_status_calcel'
	),
	'4' => array(
		'statusName' => '待回复',
		'colorClass' => 'project_status_reply'
	)
);

// 任务类型
$typeList = array(
	'1' => '策划',
	'2' => '设计',
	'3' => '调整',
	'4' => '完稿'
);

// 文件上传类别
$fileUploadList = array(
	'projectFile',			 // 项目资料
	'projectContractFile',	 // 合同
	'projectProposalFile',   // 项目提案资料
	'projectMeetingNoteFile' // 会议纪要
);
?>