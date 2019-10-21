<?php
// 官网地址
$webUrl = 'my.site.com';

/*
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
*/

// 项目状态
$projectStatusList = array(
	array(
		'key' => '1',
		'name' => '执行中'
	),
	array(
		'key' => '2',
		'name' => '已完成'
	),
	array(
		'key' => '3',
		'name' => '已取消'
	),
	array(
		'key' => '4',
		'name' => '待回复'
	)
);

// 任务类型
$projectTypeList = array(
	array(
		'key' => '1',
		'name' => '策划'
	),
	array(
		'key' => '2',
		'name' => '设计'
	),
	array(
		'key' => '3',
		'name' => '调整'
	),
	array(
		'key' => '4',
		'name' => '完稿'
	)
);

// 项目文件模块类型
$fileUploadTypeList = array(
	/*'projectFile',			  // 项目资料
	'projectContractFile',	  // 合同
	'projectProposalFile',    // 项目提案资料
	'projectMeetingNoteFile', // 会议纪要
	'projectTaskFile' 		  // 项目任务单资料*/
	
	array(
		'id' => 1,
		'key' => 'projectFile',
		'name' => '项目资料'
	),
	array(
		'id' => 2,
		'key' => 'projectContractFile',
		'name' => '合同'
	),
	array(
		'id' => 3,
		'key' => 'projectProposalFile',
		'name' => '项目提案资料'
	),
	array(
		'id' => 4,
		'key' => 'projectMeetingNoteFile',
		'name' => '会议纪要'
	),
	array(
		'id' => 5,
		'key' => 'projectTaskFile',
		'name' => '项目任务单资料'
	)
);

/*
$roleList = array(
	'0' => '最高权限',
	'1' => '总监',
	'2' => '项目经理',
	'3' => '执行人员'
);
*/

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