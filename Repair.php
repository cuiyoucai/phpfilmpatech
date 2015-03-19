<?php
require_once 'BaseTool.php';
require_once 'ImgTool.php';
require_once 'FilmBase.php';
require_once 'FilmReview.php';
require_once 'FilmComment.php';
$mongo = new Mongo("mongodb://192.168.1.11");
$collecion = $mongo->FilmStore->Film;
$rl = $collecion->find();
$count = 0;
foreach ($rl as $ob){
	$fid = $ob["fid"];
//	foreach ($ob["comment"] as $key=>$oc){
//			$collecion->update(array("fid"=>$fid),array('$set'=>array(     
//				"comment.$key.vote"=>(int)$oc["vote"],
//				"comment.$key.oppose"=>0
//			)));
//			$count++;
//	}
//	$collecion->update(array("fid"=>$fid),array('$set'=>array(     
//				"datetime"=>(int)$ob["datetime"],
//			)));
$rl = checkData($ob);
if(!$rl["flag"]){
	foreach ($rl["ct"] as $oc){
		if($oc=="type"){
			$collecion->update(array("fid"=>$fid),array('$set'=>array(     
				"type"=>array(),
			)));
		}
		if($oc=="area"){
			$collecion->update(array("fid"=>$fid),array('$set'=>array(     
				"area"=>array(),
			)));
		}
		if($oc=="language"){
			$collecion->update(array("fid"=>$fid),array('$set'=>array(     
				"language"=>array(),
			)));
		}
		if($oc=="performer"){
			$collecion->update(array("fid"=>$fid),array('$set'=>array(     
				"performer"=>array(),
			)));
		}
		if($oc=="other_name"){
			$collecion->update(array("fid"=>$fid),array('$set'=>array(     
				"other_name"=>array(),
			)));
		}
	}
		$count++;
	}		
}



//	if($ct==0){
//			$result = getComment($fid);
//			$collecion->update(array("fid"=>$fid),array('$set'=>array(     
//				//"comment"=>$result["comment"],
//				"count"=>$result["count"]
//			)));
//			$count++;
//	}
//$dh = opendir("/var/Movie/images/imgs/big_icon");
//while (($file=readdir($dh))!==false){
//	if($file!="." && $file!=".."){
//		$temp = explode("_",$file);
//		$fid = $temp[0];
//		if(filesize("/var/Movie/images/imgs/big_icon/".$file)==162){
//			DownLoadsg($fid);
//			$count++;
//		}
//	}
//}
//closedir($dh);
echo $count."Changed!";