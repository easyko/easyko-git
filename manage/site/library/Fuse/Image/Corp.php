<?php
/**
 * Image corp
 * @package 	Imag.Framework
 * @subpackage  Image
 * @author      gary wang (wangbaogang123@hotmail.com)
 * 
 */
class Fuse_Image_Corp extends Fuse_Image_Extend
{

	/**
	 * mime type
	 */
	private $mime;

	/**
	 * source path
	 */
	private $sourcePath;
	
	/**
	 * save path
	 */
	private $savePath;
	
	/**
	 * source image object
	 */
	private $sourceImage;
	
	
	/**
	 * Constructor
	 */
	public function __construct($source){

		$this->sourcePath = $source;
		$this->setValueFromPath();
		$this->init();
	}

	/**
	 * init
	 * @return	void.
	 */
	private function init(){
		$this->sourceImage = $this->imageCreateFromMime();
	}

	/**
	 * Set save path
	 * 
	 * @param	string	$_path	path to save
	 * @return	void
	 */
	public function setSavePath($_path){
		$this->savePath   = $_path;
	}

	/**
	 * Set value from source path
	 * 
	 * @return	void
	 */
	public function setValueFromPath(){

		list($this->width, $this->height, $this->mime) = getimagesize($this->sourcePath);

	}

	/**
	 * Save image by mime type
	 * 
	 * @param	image	$image	image object
	 * @param	string	$path	path to save
	 * @return	void
	 * 
	 */
	private function imageOutputByMime($image,$path){
		switch($this->mime){
			case IMAGETYPE_GIF:
				imagegif($image,$path);
				break;
			case IMAGETYPE_JPEG:
				imagejpeg($image,$path);
				break;
			case IMAGETYPE_PNG:
				imagepng($image,$path);
				break;
			case IMAGETYPE_BMP:
				$path = strtolower($path);
				$path = str_replace("bmp", "jpg", $path);
				imagejpeg($image,$path);
				break;
		}
	}

	/**
	 * Get image by mime type
	 * 
	 * @return	void
	 * 
	 */
	private function imageCreateFromMime(){
		switch($this->mime){
			case IMAGETYPE_GIF:
				return imagecreatefromgif($this->sourcePath);
				break;
			case IMAGETYPE_JPEG:
				return imagecreatefromjpeg($this->sourcePath);
				break;
			case IMAGETYPE_PNG:
				return imagecreatefrompng($this->sourcePath);
				break;
			case IMAGETYPE_BMP:
				return $this->imagecreatefrombmp($this->sourcePath);
				break;
		}
	}


//( resource $dst_image , resource $src_image , int $dst_x , int $dst_y , int $src_x , int $src_y , int $dst_w , int $dst_h , int $src_w , int $src_h )

	/**
	 * Corp image by config
	 * 
	 * @param	object	$options	config
	 * @return	void
	 * 
	 */
	public function toCorp($options){

		$resource = imagecreatetruecolor($options->width, $options->height);

		$bgcolor   = imagecolorallocate($resource,255,255,255);
		imagefilledrectangle($resource,0,0,$options->width, $options->height,$bgcolor); 

		imagecopyresampled($resource, $this->sourceImage, 0, 0, 0, 0, $options->width, $options->height, $this->width, $this->height);
		//$sourcecropwidth = floor($options->cropwidth * $this->width/$options->width);
		//$sourcecropheight = floor($options->cropheight * $this->height/$options->height);
		$croper = imagecreatetruecolor($options->cropwidth,$options->cropheight);
		imagefilledrectangle($croper,0,0,$options->cropwidth, $options->cropheight,$bgcolor); 
		//imagecopyresampled($croper, $this->sourceImage, 0, 0, $options->x, $options->y, $options->width,$options->height, $sourcecropwidth, $sourcecropheight);
		$crop_point = array(0,0);
		$source_point = array($options->x, $options->y);
		if($options->x<0){
			$source_point[0] = 0;
			$crop_point[0] = -$options->x;
		}
		if($options->y<0){
			$source_point[1] = 0;
			$crop_point[1] = -$options->y;
		}
		imagecopyresampled($croper, $resource, $crop_point[0], $crop_point[1], $source_point[0], $source_point[1], $options->width,$options->height, $options->width,$options->height);
		
		$this->imageOutputByMime($croper,$this->savePath);
		imagedestroy($croper);

	}

	/**
	 * Get save image size by height
	 * 
	 * @param	int	$height	height
	 * @return	array
	 */
	public function getByHeight($height) {
      $ratio = $height / $this->getHeight();
      $width = $this->getWidth() * $ratio;
	  $object = new stdClass();
	  $object->width   = $width;
	  $object->height = $height;
      return $object;
	}

	/**
	 * Get save image size by width
	 * 
	 * @param	int	$width	width
	 * @return	array
	 */
	public function getByWidth($width) {
		  $ratio = $width / $this->getWidth();
		  $height = $this->getHeight() * $ratio;
		  $object = new stdClass();
		  $object->width   = $width;
		  $object->height = $height;
		  return $object;
	}

	/**
	 * Destory image
	 * @return	void 
	 */
	public function destroy(){
		imagedestroy($this->sourceImage);
	}

	
	/**
	 * Save resize image
	 * 
	 * @param	int	$width	width
	 * @param	int	$height	height
	 * @param	string	$folder	folder
	 * @return	void 
	 */
	public function resize($width,$height) {
	  
	  if($this->getWidth()/$this->getHeight()>$width/$height){
		 $option = $this->getByWidth($width);
	  }else{
		 $option = $this->getByHeight($height);
	  }

	  $new_image = imagecreatetruecolor($option->width, $option->height);
      imagecopyresampled($new_image, $this->sourceImage, 0, 0, 0, 0, $option->width, $option->height, $this->getWidth(), $this->getHeight());
	  
	  $savePath = $this->savePath;
      $this->imageOutputByMime($new_image,$savePath);
	  imagedestroy($new_image);

   }

	
	/**
	 * 
	 * @return	int 
	 */
	public function getWidth() {
      return imagesx($this->sourceImage);
	}
	
	/**
	 * 
	 * @return	int 
	 */
	public function getHeight() {
      return imagesy($this->sourceImage);
	}

	/**
	 * 
	 * Sharp image
	 * 
	 * @param	image	$im	width
	 * @param	int	$degree	sharp degree
	 * @return	void
	 */
	private function sharp($im,$degree){  
       $cnt = 0;  
       for($x=imagesx($im)-1;$x>0;$x--){
               for($y=imagesy($im)-1;$y>0;$y--){  
                       
					   $clr1 = imagecolorsforindex($im,imagecolorat($im, $x-1, $y-1));  
                       $clr2 = imagecolorsforindex($im,imagecolorat($im, $x,   $y));  
                       $r  =  intval($clr2["red"]+$degree*($clr2["red"]-$clr1["red"]));  
                       $g  =  intval($clr2["green"]+$degree*($clr2["green"]-$clr1["green"]));  
                       $b  =  intval($clr2["blue"]+$degree*($clr2["blue"]-$clr1["blue"]));  
                       $r  =  min(255, max($r,0));  
                       $g  =  min(255, max($g,0));  
                       $b  =  min(255, max($b,0));  
                       //echo  "r:$r,  g:$g,  b:$b<br>";  
                       if(($new_clr = imagecolorexact($im,$r,$g,$b))==-1){ 
                               $new_clr = Imagecolorallocate($im,$r,$g,$b);  
					   }
                       $cnt++;
                       if($new_clr==-1) die("color  allocate  faile  at  $x,  $y  ($cnt).");  
                       imagesetpixel($im,$x,$y,$new_clr);
					   
               }
		}
	}
}
?>