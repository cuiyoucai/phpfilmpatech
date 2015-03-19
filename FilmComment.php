<?php
require_once 'BaseTool.php';
/**
 * 获取电影短评
 * @param unknown_type $fid
 */
function getComment($fid){
	$res = array();
	$res["fid"] = $fid;
	$links = "http://movie.douban.com/subject/".$fid."/comments";
	$html = getHtml($links);
	$pattern = '/<div class="clearfix subject-comment-item".*?<span class="votes pr5">(.*?)<\/span>.*?<span class="fleft">.*?>(.*?)<.*?<span class="fleft ml8">(.*?)<\/span>.*?<p class="w490">(.*?)<\/p>/si';
	preg_match_all($pattern,$html,$matches);
	$res["comment"] = array();
	$res["count"] = 0;
    if(isset($matches[0])){
    	$res["count"] = count($matches[0]);
    	foreach ($matches[0] as $key=>$ob){
    		$every = array();
    		$every["cid"] = $fid."_".$key; 		
    		$every["name"] = trim($matches[2][$key]);    //评论人名称 
    		$every["time"] = trim($matches[3][$key]);       //评论日期
    		$every["vote"] = (int)trim($matches[1][$key]);           //支持人数
	   		$every["content"] = changStyle($matches[4][$key]);    //评论内容  		
   			$res["comment"][] = $every;
    	}
    }
	return $res;
}