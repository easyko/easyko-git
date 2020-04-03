<?php
/**
 * Category manager
 */
class CategoryController extends Fuse_Controller
{
	function __construct($config = array())
	{
		parent::__construct($config);

		$this->registerTask( 'category','category');
		$this->registerTask( 'insert','insert');
		$this->registerTask( 'update','update');
		$this->registerTask( 'delete','delete');
		
		if($this->getPrivilege()=="0"){
			exit("No privilege!");
		}
		
	}

	function getPrivilege(){
		$uid = Fuse_Cookie::getInstance()->user_id;
		$model = new Fuse_Model();
		$row = $model->getRow("SELECT `isadmin` FROM `users` WHERE `user_id`='{$uid}'");
		return $row["isadmin"];
	}
	
	function category()
	{
		$forward = Fuse_Request::getVar("forward","post");
		if(empty($forward))
		{
			$forward = Fuse_Request::getVar("HTTP_REFERER",'server');
		}
		
		$model = $this->createModel("Model_Category",dirname( __FILE__ ));

		$where = '1';
		if(empty($p)){$p=1;}
		$baseurl = "category.php?p=";
		$perpage = 20;
		
		$totalitems = $model->getTotal($where);
		$paginator = new Fuse_Paginator($totalitems,$p,$perpage,10);
		$limit = $paginator->getLimit();
		$itemList = $model->getList($limit["start"],$limit["offset"],$where);

		$view  = $this->createView();
		$view->pageList = $paginator->getPages();
		$view->itemList = $itemList;
		$view->total = $totalitems;
		$view->display("category.html");

	}
	
	function insert()
	{
		$forward = Fuse_Request::getVar("forward","post");
		if(empty($forward))
		{
			$forward = Fuse_Request::getVar("HTTP_REFERER",'server');
		}	

		$category = Fuse_Request::getVar("category","post");
		$model = $this->createModel("Model_Category",dirname( __FILE__ ));
		$object = array("name"=>$category);

		if ($model->store($model->getTable(),$object))
		{
			Fuse_Response::redirect($forward,"success");
		}
		else
		{
			Fuse_Response::redirect($forward,"fail");
		}
	}
	
	function update()
	{
		$forward = Fuse_Request::getVar("forward","post");
		if(empty($forward))
		{
			$forward = Fuse_Request::getVar("HTTP_REFERER",'server');
		}
		$category = Fuse_Request::getVar("category","post");
		$cid = Fuse_Request::getVar("cid","post");
		
		$model = $this->createModel("Model_Category",dirname( __FILE__ ));

		$object = array();
		$object["name"] =  $category;

		if ($model->update($model->getTable(),$object,"`".$model->getKey()."`='{$cid}'"))
		{
			Fuse_Response::redirect($forward,"success");
		}
		else
		{
			Fuse_Response::redirect($forward,"success");
		}
	}
	
	function delete()
	{
		$forward = Fuse_Request::getVar("forward");
		if(empty($forward))
		{
			$forward = Fuse_Request::getVar("HTTP_REFERER",'server');
		}	

		$cid = Fuse_Request::getVar("cid","get");
	
		$model = $this->createModel("Model_Category",dirname( __FILE__ ));

		if ($model->delete($cid))
		{
			Fuse_Response::redirect('category.php',"success");
		}
		else
		{
			Fuse_Response::redirect('category.php',"fail");
		}
	}
}
