<?php
/**
 * Component Model
 *
 * @package		Joomla
 * @subpackage	Content
 * @since 1.5
 */
class Model_Image extends Fuse_Model
{

	/**
	 * array
	 */
	var $table = array("name"=>"editor_image_list","key"=>"editor_image_list_id");

	
	function __construct($config=array())
	{
		parent::__construct($config);
	}

	function getList($start=0,$per_page=10,$where=1)
	{
		$list = array();
		$sql = " SELECT * FROM `{$this->table['name']}` WHERE {$where} ORDER BY `{$this->table['key']}` DESC";

		if($start>=0 && !empty($per_page)){
			$sql .= " LIMIT {$start},{$per_page}";
		}
		
		if( ($stmt = $this->db->query($sql)) )
		{
			while ( $row = $stmt->fetch() )
			{
				$list[] = $row;
			}
		}
		return $list;
	}
	
	function getTotal($where=1)
	{
		$total = 0;
        
		$sql = "SELECT COUNT(*) AS total FROM `{$this->table['name']}` WHERE {$where}";
		if(($stmt = $this->db->query($sql)))
		{
			if($row = $stmt->fetch())
			{
				$total = $row['total'];
			}
		}
		
		return $total;
	}
	
	function getRowOne($id)
	{
		$list = null;
		
		$sql = "SELECT * FROM `{$this->table['name']}` WHERE `{$this->table['key']}`='{$id}'";
		
		if(($stmt = $this->db->query($sql)))
		{
			if($row = $stmt->fetch())
			{
				$list = $row;
			}
		}
		return $list;
	}
 
	function delete($id)
	{
		$sql = "DELETE FROM `{$this->table['name']}` WHERE `{$this->table['key']}`='{$id}'";
		return $this->db->query($sql);
	}
	
	function getKey()
	{
		return $this->table['key'];
	}
	
	function getTable()
	{
		return $this->table['name'];
	}

}
?>