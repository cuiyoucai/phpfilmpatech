<?php
require_once 'ImgTool.php';
/**
 * 获取网页内容
 * @param 网页链接 $url
 */
function getHtml($url){
	$ch1 = curl_init();
	$curl_header = array(
      'Accept: */*',
   	  'User-Agent: Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.1 )',
    );
    curl_setopt($ch1, CURLOPT_HTTPHEADER, $curl_header);
	curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch1, CURLOPT_URL,$url);
	$res = curl_exec($ch1);
	curl_close($ch1);
	return $res;
}


/**
 * 电影信息存库
 * @param 电影信息 $data
 * @param 信息类型 $type
 */
function insertFilmDB($data,$type="f"){
$mongo = new Mongo("mongodb://192.168.1.11");
if($type=="f"){
	$collecion = $mongo->FilmStore->Film; 	
}elseif ($type=="c"){
	$collecion = $mongo->FilmStore->Comment; 
}elseif ($type=="r"){
	$collecion = $mongo->FilmStore->Review; 
}
$collecion->insert($data);
$mongo->close();
}

/**
 * 
 * 电影资源已存在性判断
 * @param 电影标识ID $fid
 */
function isExit($fid){
	$mongo = new Mongo("mongodb://192.168.1.11");
	$collecion = $mongo->FilmStore->Film;
	$res = $collecion->find(array(
		"fid"=>$fid
	));
	return (getCount($res)==0)?false:true;
}

/**
 * 获取MongoDB返回的数据集的规模
 * Enter description here ...
 * @param Mongo数据集 $res
 */
function getCount($res){
	$count = 0 ;
	foreach ($res as $ob){
		$count++;
	}
	return $count;	
}

/**
 * 
 * 过滤掉html标签
 * @param unknown_type $content
 */
function changStyle($content){
	$content = preg_replace('/<p.*?>/s',"356821.2546",$content);
	$content = preg_replace('/<\/p>/s',"263558.15454",$content);
	$content = preg_replace('/<br.*?>/s',"4482.36512",$content);
	$content = strip_tags($content);
	$content = preg_replace('/356821.2546/s',"<p>",$content);
	$content = preg_replace('/263558.15454/s',"</p>",$content);
	$content = preg_replace('/4482.36512/s',"<br>",$content);
	return iconv("UTF-8","UTF-8",$content);
}

/**
 * 
 * 数据的完整性检查
 * @param $data
 */
function checkData($data){
	$res = array(
		'flag'=>true,
		'ct'=>array()
	);
	$res["flag"] = array_key_exists("type",$data)&&array_key_exists("area",$data)&&array_key_exists("language",$data)
	&&array_key_exists("performer",$data)&&array_key_exists("other_name",$data);
	if(!array_key_exists("type",$data)){
		$res["ct"][] = "type";
	}
	if(!array_key_exists("area",$data)){
		$res["ct"][] = "area";
	}
	if(!array_key_exists("language",$data)){
		$res["ct"][] = "language";
	}
	if(!array_key_exists("performer",$data)){
		$res["ct"][] = "performer";
	}
	if(array_key_exists("other_name",$data)){
		$res["ct"][] = "other_name";
	}
	return $res;
}
/**
 * 
 * 解决部分电影图片下载失败
 * @param $fid 
 */
function DownLoadsg($fid){
	$link = "http://movie.douban.com/subject/".$fid."/";
	$html = getHtml($link);
	
	$pattern = '/id="mainpic".*?src="http:\/\/img5.douban.com\/mpic\/s(.*?)\.jpg/s';
	preg_match_all($pattern,$html,$matches);
	$bt = $matches[1][0];
	$tpurl = "http://img5.douban.com/mpic/s".$bt.".jpg";
	grabImage($tpurl,$fid."_big.jpg","bi");
	

//	$tpurl = "http://img5.douban.com/spic/s".$bt.".jpg";
//	grabImage($tpurl,$fid."_small.jpg","bi");
}



