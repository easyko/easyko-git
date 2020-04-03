<?php
/**
 * imag thumb
 * 
 * @package 	Imag.Framework
 * @subpackage  Image
 * @author      jerry.cao (caowlong163@163.com)
 */
 
Class ImageThumb extends GdExtend
{

	private $thumbWidth  = 200;

	private $width;
	private $height;
	private $mime;

	private $sourcePath;
	private $savePath;
	private $sourceImage;

	public function __construct($source,$width){

		$this->thumbWidth = $width;
		$this->sourcePath = $source;
		$this->setValueFromPath();
		$this->init();
	}

	private function init(){
		$this->sourceImage = $this->imageCreateFromMime();
	}

	public function setSavePath($_path){
		$this->savePath   = $_path;
	}
	
	public function setThumbSize($width){

		$this->thumbWidth = $width;

	}

	public function setValueFromPath(){

		list($this->width, $this->height, $this->mime) = getimagesize($this->sourcePath);

	}

	public function getImage($mime,$sourcePath){
		$this->mime = $mime;
		$this->sourcePath = $sourcePath;

		return $this->imageCreateFromMime();
	}


	public function toThumb(){

		$source = $this->sourceImage;
		
		list($newWidth, $newHeight) = $this->getSize($this->width, $this->height);
		//var_dump($newWidth, $newHeight);
		$thumb = imagecreatetruecolor($newWidth, $newHeight);

		$bgcolor   = imagecolorallocate($thumb,255,255,255);
		imagefilledrectangle($thumb,0,0,$newWidth, $newHeight,$bgcolor); 

		// Resize
		imagecopyresampled($thumb, $source, 0, 0, 0, 0, $newWidth,$newHeight, $this->width, $this->height);
		//$this->sharp($thumb,0.5);

		//imageinterlace($thumb,true);

      
		//imagefilter($thumb, IMG_FILTER_CONTRAST,-1);
        //imagejpeg($thumb,$this->savePath,100);

		$this->imageOutputByMime($thumb,$this->savePath);

		imagedestroy($thumb);

	}

	private function imageOutputByMime($image,$path){
		imagejpeg($image,$path,100);
		/*
		switch($this->mime){
			case IMAGETYPE_GIF:
				imagegif($image,$path);
				break;
			case IMAGETYPE_JPEG:
				imagejpeg($image,$path,100);
				break;
			case IMAGETYPE_PNG:
				imagepng($image,$path);
				break;
			case IMAGETYPE_BMP:
				$path = strtolower($path);
				$path = str_replace("bmp", "jpg", $path);
				imagejpeg($image,$path,100);
				break;
		}
		*/
	}


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

	private function getSize($width, $height){

		$result = array($width,$height);

		//if($width > $this->thumbWidth){
			$result = array($this->thumbWidth,floor($this->thumbWidth/$width*$height));
		//}
		return $result;

	}

	private function getDstRect($_thumbWidth,$_thumbHeight){

		$x = 0;
		$y = 0;

		if($this->width>$this->height){
			$newWidth  = $this->height * ($_thumbWidth / $_thumbHeight);
			if($newWidth>$this->width){
				$newWidth  = $this->width;
				$newHeight = $this->width * ($_thumbHeight / $_thumbWidth);
				$y = floor(($this->height - $newHeight)/2);
			}else{
				$newHeight = $this->height;
				$x = floor(($this->width - $newWidth)/2);
			}
			
		}else{
		
			$newHeight = $this->width * ($_thumbHeight / $_thumbWidth);
			if($newHeight>$this->height){
				$newWidth  = $this->height * ($_thumbWidth / $_thumbHeight);
				$newHeight = $this->height;
				$x = floor(($this->width - $newWidth)/2);
			}else{
				$newWidth  = $this->width;
				$y = floor(($this->height - $newHeight)/2);
			}
		}

		return array($x,$y,$newWidth,$newHeight);

	}


//( resource $dst_image , resource $src_image , int $dst_x , int $dst_y , int $src_x , int $src_y , int $dst_w , int $dst_h , int $src_w , int $src_h )

	public function toSliceThunb($_width,$_height){

		$dst = array(0,0,$_width,$_height);
		$src = $this->getDstRect($_width,$_height);

		$source = $this->sourceImage;

		$square = imagecreatetruecolor($dst[2],$dst[3]);

		$bgcolor   = imagecolorallocate($square,255,255,255);
		imagefilledrectangle($square,0,0,$dst[2],$dst[3],$bgcolor); 

		imagecopyresampled($square, $source, $dst[0], $dst[1], $src[0], $src[1], $dst[2],$dst[3], $src[2], $src[3]);
		//$this->sharp($square,0.5);
		$this->imageOutputByMime($square,$this->savePath);

		imagedestroy($square);

	
	}

	public function destroy(){
		imagedestroy($this->sourceImage);
	}

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
