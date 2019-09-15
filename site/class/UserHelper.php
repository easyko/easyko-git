<?php
/**
 * User tool
 *
 * @package		classes
 * @subpackage	tool
 * @author 		jerry.cao (caowlong163@163.com)
 */
class UserHelper
{
	/**
	 * Username check 4-16 char
	 */
	public static function checkUsername($username){
		return preg_match("/^[a-zA-Z0-9_]{4,16}$/",$username);
	}

	public static function checkPassword($password){
		return preg_match("/^[a-zA-Z0-9]{6,16}$/",$password);
	}

	public static function checkRealname($realname){
//		return preg_match("/^[\u0391-\uFFE5]{2,6}$/",$realname);
		return preg_match("/^[\x7f-\xff]+$/",$realname);
	}

	public static function checkMobile($mobile){
		return preg_match("/^1[3|4|5|7|8][0-9]{1}\d{8}$/",$mobile);
	}

	/**
	 * Email check
	 */
	public static function checkEmail($email){
		return preg_match('/^[a-z0-9&\'\.\-_\+]+@[a-z0-9\-]+\.([a-z0-9\-]+\.)*?[a-z]+$/is', $email);
	}

	/**
	 * Idcard check
	 */
	public static function checkIdcard($IDCard){
		$flag=0;
		//if(!eregi("^[1-9]([0-9a-zA-Z]{17}|[0-9a-zA-Z]{14})$",$IDCard)){
		if(!ereg("^[0-9a-zA-Z]*$",$IDCard)){
			$flag=0;
		}else{
			if(strlen($IDCard)==18){
				$tyear=intval(substr($IDCard,6,4));
				$tmonth=intval(substr($IDCard,10,2));
				$tday=intval(substr($IDCard,12,2));
				if($tyear>date("Y")||$tyear<(date("Y")-100)){
					$flag=0;
				}elseif($tmonth<0||$tmonth>12){
					$flag=0;
				}elseif($tday<0||$tday>31){
					$flag=0;
				}else{
					$tdate=array($tyear,$tmonth,$tday);
					$flag=1;
				}
			}elseif(strlen($IDCard)==15){
				$tyear=intval("19".substr($IDCard,6,2));
				$tmonth=intval(substr($IDCard,8,2));
				$tday=intval(substr($IDCard,10,2));
				if($tyear>date("Y")||$tyear<(date("Y")-100)){
					$flag=0;
				}elseif($tmonth<0||$tmonth>12){
					$flag=0;
				}elseif($tday<0||$tday>31){
					$flag=0;
				}else{
					$tdate=array($tyear,$tmonth,$tday);
					$flag=1;
				}
			 }
		}

		return $flag;
		//return array($flag,$tdate);
	}

	/**
	 * Idcard check 2
	 */
	public static function getIdcardInfo($IDCard){
		if(!eregi("^[1-9]([0-9a-zA-Z]{17}|[0-9a-zA-Z]{14})$",$IDCard)){
			$flag=0;
		}else{
			if(strlen($IDCard)==18){

				$tyear=intval(substr($IDCard,6,4));
				$tmonth=intval(substr($IDCard,10,2));
				$tday=intval(substr($IDCard,12,2));

				if($tyear>date("Y")||$tyear<(date("Y")-100)){
					$flag=0;
				}elseif($tmonth<0||$tmonth>12){
					$flag=0;
				}elseif($tday<0||$tday>31){
					$flag=0;
				}else{
					$tdate=$tyear."-".$tmonth."-".$tday." 00:00:00";
					if((time()-mktime(0,0,0,$tmonth,$tday,$tyear))>18*365*24*60*60){
						$flag=0;
					}else{
						$flag=1;
					}
				}

			}elseif(strlen($IDCard)==15){
				$tyear=intval("19".substr($IDCard,6,2));
				$tmonth=intval(substr($IDCard,8,2));
				$tday=intval(substr($IDCard,10,2));
				if($tyear>date("Y")||$tyear<(date("Y")-100)){
					$flag=0;
				}elseif($tmonth<0||$tmonth>12){
					$flag=0;
				}elseif($tday<0||$tday>31){
					$flag=0;
				}else{
					$tdate=$tyear."-".$tmonth."-".$tday." 00:00:00";
					if((time()-mktime(0,0,0,$tmonth,$tday,$tyear))>18*365*24*60*60){
						$flag=0;
					}else{
						$flag=1;
					}
				}
			}
		}
		$result = array();
		$result[] = $flag;
		$result[] = $tdate;
		return $result;

	}

	public static function getIp(){
		$onlineip = "";
		if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
			$onlineip = getenv('HTTP_CLIENT_IP');
		} elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
			$onlineip = getenv('HTTP_X_FORWARDED_FOR');
		} elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
			$onlineip = getenv('REMOTE_ADDR');
		} elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
			$onlineip = $_SERVER['REMOTE_ADDR'];
		}
		return $onlineip;
	}

	public static function formhash($time,$user_id,$sitekey,$hashadd=""){
		return substr(md5(substr($time, 0, -7).'|'.$user_id.'|'.md5($sitekey).'|'.$hashadd), 8, 8);
	}
}
?>