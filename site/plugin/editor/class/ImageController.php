<?php
class ImageController extends Fuse_Controller
{
	function __construct($config = array())
	{
		parent::__construct($config);

		$this->registerTask( 'insert','insert');
		$this->registerTask( 'delete','delete');
		$this->registerTask( 'image_list','image_list');

		if($this->getPrivilege()=="-1"){
			exit("No privilege!");
		}
	}

	function getPrivilege(){
		$uid = Fuse_Cookie::getInstance()->user_id;
		$model = new Fuse_Model();
		$row = $model->getRow("SELECT `isadmin` FROM `users` WHERE `user_id`='{$uid}'");
		return $row["isadmin"];
	}

	/**
	 * insert
	 */
	function insert()
	{
		$cid = Fuse_Request::getVar("cid",'post');
		$describe = Fuse_Request::getVar("describe",'post');
		$forward = Fuse_Request::getVar("forward",'Fuse_Request');
		if(empty($forward)){
			$forward = Fuse_Request::getVar("HTTP_REFERER",'server');
		}
		
		$uid = Fuse_Cookie::getInstance()->user_id;

		$model = $this->createModel("Model_Image",dirname( __FILE__ ));

		$basedir = Config_App::homedir()."/upload/";
		$path    = Config_App::homeurl()."/upload/";
		
		foreach ($_FILES["pictures"]["error"] as $key => $error) 
		{
			if ($error == UPLOAD_ERR_OK)
			{
				
				$files = array(
					"tmp_name"=>$_FILES["pictures"]["tmp_name"][$key],
					"name"=>$_FILES["pictures"]["name"][$key],
					"type"=>$_FILES["pictures"]['type'][$key]
				);
				
				try{
					$uploader = new Fuse_Image_Uploader($files,$basedir);
					$uploader->toUpload();
				}catch(Exception $e){
					var_dump($e->getMessage());
					exit();
				}
				
				$object = array();
				$object["image_category_id"] = $cid;
				$object["describe"]          = $describe;
				$object["path"]              = $uploader->getFolderFile();
				if(!$model->store($model->getTable(),$object)){
					var_dump($model->getError());
				}
			}
		}
		Fuse_Response::redirect($forward,"Upload Ok!");
	}

	/**
	 * delete
	 */
	function delete()
	{
		$basedir = Config_App::homedir()."/upload/";

		$forward = Fuse_Request::getVar("forward",'Fuse_Request');
		if(empty($forward)){
			$forward = Fuse_Request::getVar("HTTP_REFERER",'server');
		}

		$iid = Fuse_Request::getVar('iid',"get");

		if(empty($iid)){
			Fuse_Response::redirect($forward,"Need id!");
		}
		
		$model = $this->createModel("Model_Image",dirname( __FILE__ ));
		$row = $model->getRowOne($iid);
		
		Fuse_Image_Uploader::deleteFile($basedir.DS.$row["pathname"]);

		if(!$model->delete($iid)){
			Fuse_Response::redirect($forward,"Database ");
		}

		Fuse_Response::redirect($forward,"image deleted!");
	}

	/**
	 * list
	 */
	function image_list()
	{
		$p = Fuse_Request::getVar("p",'get');
		$cid = Fuse_Request::getVar("cid",'get');

		$forward = Fuse_Request::getVar("forward",'Fuse_Request');
		if(empty($forward)){
			$forward = Fuse_Request::getVar("HTTP_REFERER",'server');
		}
		
		if(empty($cid)){
			Fuse_Response::redirect("category.php","empty cid!");
		}
		
		$model = $this->createModel("Model_Image",dirname( __FILE__ ));

		$where = "`image_category_id`='{$cid}'";
		if(empty($p)){$p=1;}
		$baseurl = "image_list.php?cid={$cid}&p=";
		$perpage = 20;
		
		$totalitems = $model->getTotal($where);
		$paginator = new Fuse_Paginator($totalitems,$p,$perpage,10);
		$limit = $paginator->getLimit();
		$itemList = $model->getList($limit["start"],$limit["offset"],$where);

		$view  = $this->createView();
		$view->path		=  "/upload/";
		$view->forward  = $_SERVER['PHP_SELF'];
		$view->pageList = $paginator->getPages();
		$view->itemList = $itemList;
		$view->total    = $totalitems;
		$view->cid      = $cid;
		$view->display("images_list.html");

	}
}