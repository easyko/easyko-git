<?php
/**
 *
 * Controller for Job
 *
 * @desc	职位相关
 * @author	jerry.cao (caowlong163@163.com)
 * $date    2019-07-27 11:27:52
 */

class JobController extends CommonController
{
	/**
	 * Constructor
	 *
	 * @params	array	Controller configuration array
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);

		$this->registerTask('index', 'jobList');
		$this->registerTask('getSampleList', 'getSampleList');

		$this->registerTask('edit', 'edit');
		$this->registerTask('delete', 'delete');

		$this->model = $this->createModel('Model_Job', dirname( __FILE__ ));
	}

	/**
	 * 项目统计
	 */
	public function jobList()
	{
		$size = intval(Fuse_Request::getFormatVar($this->params, 'size'));
		

        $page = intval(Fuse_Request::getFormatVar($this->params, 'page'));
        if (empty($page)) { $page = 1; }
		$perpage = !empty($size) ? $size : 10;

		$where = " `company_id` = '{$this->companyId}' AND `valid` = '1' ";
		$totalitems = $this->model->getTotal($where);
		$totalPage = ceil($totalitems / $perpage);
		if ($page > $totalPage && $totalPage > 0) $page = $totalPage;

		$paginator = new Fuse_Paginator($totalitems, $page, $perpage, 10);
		$limit     = $paginator->getLimit();
		$itemList  = $this->model->getList($limit['start'], $limit['offset'], $where);

        $itemTitle = array(
			'itemNo'  => '编号',
			'jobName' => '职位名称',
			'action'  => '操作'
		);
        
        if (!empty($itemList)) {
			$i = 0;
			foreach ($itemList as $key => &$value) {
				$newValue = array(
					'itemNo'  => ($page - 1) * $perpage + $i + 1,
					'jobName' => $value['jobName'],
					'action'  => '<a id="' . $value['jobId'] . '">编辑</a>'
				);
				$value = $newValue;
				unset($value);
				$i++;
			}
		}
        
        $data = array(
			'pageInfo' => array(
				'page'  => $page,
				'total' => $totalPage,
				'size'  => $perpage
			),
			'itemTitle' => $itemTitle,
			'itemList'  => $itemList
        );

        die(json_encode(array('code'=> '0000', 'message' => '成功', 'data' => $data)));
	}
	
	/**
	 * 获取简单列表
	 */
	public function getSampleList()
	{
		$where = " `company_id` = '{$this->companyId}' AND `valid` = '1' ";
		$itemList  = $this->model->getList(0, 0, $where);

		if (!empty($itemList)) {
			foreach ($itemList as &$value) {
				unset($value['createTime']);
				unset($value['companyId']);
				unset($value['valid']);
			}
		}
		
		die(json_encode(array('code'=> '0000', 'message' => '成功', 'data' => $itemList)));
	}
	
	/**
	 * 新增或修改
	 */
	public function edit()
	{
		$type = Fuse_Request::getFormatVar($this->params, 'type');
		$id   = Fuse_Request::getFormatVar($this->params, 'id', '1');
		$name = Fuse_Request::getFormatVar($this->params, 'name');
		
		if (!in_array($type, array('add', 'edit'))) {
			die(json_encode(array('code'=> '1111', 'message' => '验证参数异常', 'data' => '')));
		}
		
		if ($name == '') {
			die(json_encode(array('code'=> '2222', 'message' => '请填写名称', 'data' => '')));
		}
		
		$object = array();
		$object['job_name'] = $name;
		
		if ($type == 'add') {
			$object['company_id'] = $this->companyId;
			$object['create_time'] = Config_App::getTime();
			$object['valid'] = 1;
		}
		
		if ($type == 'edit' && $id != '') {
			$this->model->update($this->model->getTableJobName(), $object, " `job_id` = '{$id}' ");
		} else if ($type == 'add') {
			$this->model->store($this->model->getTableJobName(), $object);
		}
		
		die(json_encode(array('code'=> '0000', 'message' => '成功', 'data' => '')));
	}
	
	/**
	 * 删除
	 */
	public function delete()
	{
		$id = Fuse_Request::getFormatVar($this->params, 'id', '1');
		if ($id == '') {
			die(json_encode(array('code'=> '1111', 'message' => '参数丢失', 'data' => '')));
		}
		
		$this->model->delete($this->companyId, $id);
		
		die(json_encode(array('code'=> '0000', 'message' => '成功', 'data' => '')));
	}
}
