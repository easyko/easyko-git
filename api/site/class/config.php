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

$roleList = array(
	'0' => '最高权限',
	'1' => '总监',
	'2' => '项目经理',
	'3' => '执行人员'
);

/*$menuList = array(
	'0' => array(
		'0' => array(
			'name' => '项目查询',
			'url'  => 'project.html'
		),
		'1' => array(
			'name' => '创建项目',
			'url'  => 'project_create.html'
		),
		'2' => array(
			'name' => '人员管理',
			'url'  => 'member.html'
		),
		'3' => array(
			'name' => '绩效考核',
			'url'  => 'performance.html'
		),
		'4' => array(
			'name' => '工作周报',
			'url'  => 'weekly_report.html'
		),
		'5' => array(
			'name' => '项目总结',
			'url'  => 'project_report.html'
		)
	),
	'1' => array(
		'0' => array(
			'name' => '项目查询',
			'url'  => 'project.html'
		),
		'1' => array(
			'name' => '绩效考核',
			'url'  => 'performance.html'
		),
		'2' => array(
			'name' => '工作周报',
			'url'  => 'weekly_report.html'
		),
		'3' => array(
			'name' => '项目总结',
			'url'  => 'project_report.html'
		)
	),
	'2' => array(
		'0' => array(
			'name' => '项目查询',
			'url'  => 'project.html'
		),		
		'1' => array(
			'name' => '创建项目',
			'url' => 'project_create.html'
		),
		'2' => array(
			'name' => '工作周报',
			'url'  => 'weekly_report.html'
		),
		'3' => array(
			'name' => '项目总结',
			'url'  => 'project_report.html'
		)
	),
	'3' => array(
		'0' => array(
			'name' => '工号单',
			'url'  => 'serial_number.html'
		)
	)
);*/

/*
$menuList = array(
	array(
		'menuId' => '1',
		'menuName' => '统计分析',
		'childList' => array(
			array(
				'name' => '项目统计',
				'nameId' => 'project_statistics'
			),
			array(
				'name' => '任务统计',
				'nameId' => 'task_statistics'
			),
			array(
				'name' => '成员进度',
				'nameId' => 'progress_statistics'
			),
			array(
				'name' => '项目财报',
				'nameId' => 'financial_report'
			)
		),
	),
	array(
		'menuId' => '2',
		'menuName' => '项目',
		'childList' => array(
			array(
				'name' => '项目总览',
				'nameId' => 'project_overview'
			),
			array(
				'name' => '新建项目',
				'nameId' => 'create_project'
			)
		)
	)
);
*/
?>