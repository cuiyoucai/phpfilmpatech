<?php
require_once 'BaseTool.php';
/**
 * 获取电影影评
 * @param unknown_type $fid
 */
function getReview($fid){
	$res = array();
	$res["fid"] = $fid;
	$links = "http://movie.douban.com/subject/".$fid."/reviews";
	$html = getHtml($links);
	
	$pattern = '/<ul class="tlst clearfix" style="clear:both">.*?<a title="(.*?)".*?<span class="starb">.*?>(.*?)<.*?<span class="allstar(\d?)0".*?<div id="review_.*?_short" class="review-short">(.*?)<br.*?>.*?<span class="fleft">(.*?)&nbsp; &nbsp;(.*?)\/.*?(\d+)/si';
    preg_match_all($pattern,$html,$matches);
    $res["comment"] = array(); 
    $res["count"] = 0;  
	if(isset($matches[0])){
	$res["count"] = count($matches[0]);
		foreach ($matches[0] as $key=>$vl){
			$every = array();
			$every["cid"] = $fid."_".$key;
			$every["title"] = trim($matches[1][$key]);   //标题
			$every["name"] = trim($matches[2][$key]);   //评论人名称
			$every["time"] = trim($matches[5][$key]);   //发表评论时间
			$every["vote"] = is_numeric(trim($matches[6][$key]))?(int)trim($matches[6][$key]):0;  //支持人数
			$every["oppose"] = is_numeric(trim($matches[6][$key]))?(int)trim($matches[7][$key]):0;
			$every["star"] = trim($matches[3][$key]);  //星级
			$every["content"] = $matches[4][$key];    //评论内容
			$pattern = '/<a class="pl" href="(.*?)"/si';
			preg_match_all($pattern,$matches[4][$key],$matches1);
			if(isset($matches1[1][0])){
				$html = getHtml($matches1[1][0]);
				$pattern = '/<span property="v:description">(.*?)<\/span>/si';
				preg_match_all($pattern,$html,$matches2);
				$every["content"] = isset($matches2[1][0])?$matches2[1][0]:".....";
			}
			$res["comment"][] = $every;
		}
	}
	return $res;
}
