<?php
/**
 * Fuse pagenator 
 *
 * @category   Fuse
 * @package    Fuse_Paginator
 * @copyright  Copyright (c) 2010-now 75.cn (http://cms.e75.cn)
 * @author Gary wang(qq:465474550,msn:wangbaogang123@hotmail.com)
 * 
 */
class Fuse_Paginator
{
	/**
	 * total count
	 */
	public $count;
	/**
	 * current page
	 */
	public $page;
	/**
	 * per page item count
	 */
	public $itemCountPerPage;
	/**
	 * page range of page navigation
	 */
	public $pageRange;
	
	/**
     * Constructor
     *
     * Initialize paginator source.
     *
     * @param  int                   $count
     * @param  int            $page
     * @param  int                   $itemCountPerPage
     * @param  int                   $pageRange
     * @return void
     */
	public function __construct($count, $currentpage, $itemCountPerPage=20, $pageRange=10)
	{
		$this->count            = $count;
		$this->page             = $currentpage;
		$this->itemCountPerPage = $itemCountPerPage;
		$this->pageRange        = $pageRange;
		$this->checkPage();
	}
	
	/**
	 * Check current page
	 */
	private function checkPage()
	{
		$total = $this->getTotal();
		if($this->page > $total)
		{
			$this->page = $total;
		}
		if(empty($this->page) || $this->page < 1)
		{
			$this->page = 1;
		}
	}
	
	/**
	 * total
	 */
	public function getTotal(){
		return ceil($this->count/$this->itemCountPerPage);
	}
	
	/**
	 * Get pages
	 * @return array
	 */
	public function getPages()
	{
		$totalpage = $this->getTotal();
		if ( $totalpage <= 1 ){
			return array();
		}

		$on_page = $this->page;

		$pagelist = array();

		if ( $totalpage > $this->pageRange ){

			if($on_page < floor($this->pageRange/2)){ //<5
				$first_page  = 1;
			}else{
				$first_page  = $on_page - floor($this->pageRange/2)+1;//保证第5个
			}
			$this_total = $first_page + $this->pageRange-1;//最后一个 保证存在10个

			if(($first_page + $this->pageRange) >= ($totalpage-$totalpage%$this->pageRange) && $on_page>floor($this->pageRange/2)+1){
					$first_page = $totalpage - $this->pageRange+1; //最后一个 保证存在10个
					$this_total  = $totalpage;
			}
			for($i = $first_page; $i <= $this_total; $i++){
				$pagelist[] = $i;
			}
		}
		else
		{
			for($i = 1; $i < $totalpage + 1; $i++)
			{
				$pagelist[] = $i;
			}
		}
		
		$next = $on_page+1;
		if($next > $totalpage){
			$next = $totalpage;
		}
		
		$previous = $on_page-1;
		if($previous<1){
			$previous = 1;
		}

		
		$object = new stdClass();
		$object->pageCount        = $totalpage;
		$object->itemCountPerPage = $this->itemCountPerPage;
		$object->first            = 1;
		$object->current          = $on_page;
		$object->last             = $totalpage;
		$object->previous         = $previous;
		$object->next             = $next;
		$object->pagesInRange     = $pagelist;
		$object->firstPageInRange = $pagelist[0];
		$object->lastPageInRange  = $pagelist[count($pagelist)-1];

		return $object;

	}
	
	/**
	 * Get limit for sql query
	 * @return array
	 */
	public function getLimit()
	{
		return array(
					"start"=>($this->page-1)*$this->itemCountPerPage,
					"offset"=>$this->itemCountPerPage
		);
	}
	
}
?>
