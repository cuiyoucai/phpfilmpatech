<?php
/**
 * add a new
 * 图片存库
 * @param 图片的地址 $url
 * @param 图片名称 $filename
 */
require_once 'Snoopy.php';
function grabImage($url,$filename = '',$type,$c=1) {
    $snoopy = new Snoopy();
	if($snoopy->fetch($url)){
	 	if($type=="si"){
    		$location = "/var/Movie/images/imgs/small_icon/".$filename;
    	} elseif ($type=="bi"){
    		$location = "/var/Movie/images/imgs/big_icon/".$filename;
    	}elseif ($type=="sg"){
    		$location = "/var/Movie/images/imgs/small_stage/".$filename;
    	}else {
    		$location = "/var/Movie/images/imgs/big_stage/".$filename;
        }
		$handle = fopen($location,"w");
		fwrite($handle,$snoopy->results);
	    fclose($handle);
	    return $filename;
	}else {
		$c++;
		if($c>4){
			return $filename;
		}
		grabImage($url,$filename,$type,$c);
		return $filename;
	}
}