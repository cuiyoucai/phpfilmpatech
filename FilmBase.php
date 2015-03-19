<?php
require_once 'BaseTool.php';
require_once 'ImgTool.php';
/**
 * 获取电影详情
 * @param 电影ID $fid
 */
function getDetail($fid){
	$res = array();
	$res["fid"] = $fid;
	$link = "http://movie.douban.com/subject/".$fid."/";
	$html = getHtml($link);
	
	//电影名称
	$pattern = '/property="v:itemreviewed">(.*?)<\/span>/si';
	preg_match_all($pattern,$html,$matches);
	$temp = explode(" ",trim($matches[1][0]));
	$res["name"] = trim($temp[0]);
	
	//下载大图标
	$pattern = '/id="mainpic".*?src="http:\/\/img3.douban.com\/mpic\/s(.*?)\.jpg/s';
	preg_match_all($pattern,$html,$matches);
	$bt = $matches[1][0];
	$tpurl = "http://img3.douban.com/mpic/s".$bt.".jpg";
	$res["big_img"] = grabImage($tpurl,$fid."_big.jpg","bi");
	
	//下载小图标
	$tpurl = "http://img3.douban.com/spic/s".$bt.".jpg";
	$res["small_img"] = grabImage($tpurl,$fid."_small.jpg","si");
	
	//电影评分
	$pattern = '/property="v:average">(.*?)<\/strong>/si';
	preg_match_all($pattern,$html,$matches);
	$res["score"] = isset($matches[1][0])?(float)($matches[1][0]):0;
	$res["score_count"] = 100;
	
	//导演
	$pattern = '/rel="v:directedBy">(.*?)<\/a>/si';
	preg_match_all($pattern,$html,$matches);
	$res["director"] = isset($matches[1][0])?trim($matches[1][0]):"";
	
	//主演
	$pattern = '/rel="v:starring">(.*?)<\/a>/si';
	preg_match_all($pattern,$html,$matches);
	$res["performer"] = array();
	if (isset($matches[1][0])){
		foreach ($matches[1] as $key=>$ob){
			if($key==5){
				break;
			}
			$res["performer"][] = trim($ob);
		}
	}
	
	//类型
	$pattern = '/property="v:genre">(.*?)<\/span>/si';
	preg_match_all($pattern,$html,$matches);
	$res["type"] = array();
	if(isset($matches[1][0])){
		foreach ($matches[1] as $ob){
			$res["type"][] = trim($ob);
		}
	}
	
	//地区
	$pattern = '/地区:<\/span>(.*?)<br/si';
	preg_match_all($pattern,$html,$matches);
	$res["area"] = array();
	if(isset($matches[1][0])){
		$temp = explode("/",trim($matches[1][0]));
		foreach ($temp as $ob){
			$res["area"][] = trim($ob);
		}
	}
	
	//语言
	$pattern = '/语言:<\/span>(.*?)<br/si';
	preg_match_all($pattern,$html,$matches);
	$res["language"] = array();
	if($matches[1][0]){
		$temp = explode("/",trim($matches[1][0]));
		foreach ($temp as $ob){
			$res["language"][] = trim($ob);
		}
	}
	
	//上映日期
	$pattern = '/property="v:initialReleaseDate" content="(.*?)"/si';
	preg_match_all($pattern,$html,$matches);
	$res["datetime"] =isset($matches[1][0])?strtotime(trim($matches[1][0])):0;
	
	//片长
	$pattern = '/property="v:runtime".*?">(.*?)<\/span>/';
	preg_match_all($pattern,$html,$matches);
	$res["long"] = isset($matches[1][0])?trim($matches[1][0]):"";
	
	//别名
	$pattern = '/又名:<\/span>(.*?)<br/si';
	$res["other_name"] = array();
	preg_match_all($pattern,$html,$matches);
	if(isset($matches[1][0])){
		$temp = explode("/",trim($matches[1][0]));
		foreach ($temp as $ob){
			$res["other_name"][] = trim($ob);
		}
	}
	
	
	//剧情
	$res["introduce"] = getJuqing($html);
	
	//剧照
	$temp = getStage($html,$fid);
	$res["stage_img"] = $temp["s"];
    $res["big_stage_img"] = $temp["b"];
	
    return $res;
}

/**
 * 获取电影剧情
 * @param 网页内容 $ncont
 */
function getJuqing($ncont){
		$pattern = '/<span class="all hidden">(.*?)<\/span>/s';
		preg_match_all($pattern,$ncont,$matches);
		if (isset($matches[1][0])){
			return $matches[1][0];
		}
		$pattern = '/<span property="v:summary">(.*?)<\/span>/s';
		preg_match_all($pattern,$ncont,$matches);
		$d_str = $matches[1][0];
		if (isset($d_str)){
			$pattern = '/<span property="v:summary">(.*?)<span class="pl">/s';
			preg_match_all($pattern,$matches[0][0],$matches);
			if (isset($matches[1][0])){
				return $matches[1][0];
			}
			return $d_str;
		}
}

/**
 * 下载电影剧照
 * @param 网页内容 $html
 */
function getStage($html,$fid){
	$res = array(
		's'=>array(),
		'b'=>array(),
	);
	$pattern = '/<ul class="pic-col5 clearfix">(.*?)<\/ul>/si';
	preg_match_all($pattern,$html,$matches);
	if(isset($matches[1][0])){
		$pattern = '/photos\/photo\/(.*?)\//si';
		preg_match_all($pattern,$matches[1][0],$matches);
		foreach ($matches[1] as $key=>$ob){
			$surl = "http://img3.douban.com/view/photo/albumicon/public/p".$ob.".jpg";
			$burl = "http://img3.douban.com/view/photo/photo/public/p".$ob.".jpg";
			$res["s"][] =grabImage($surl,$fid."_".$key."_jz.jpg","sg");
			$res["b"][] =grabImage($burl,$fid."_".$key."_big.jpg","bg");
		}
	}
	return $res;
}
