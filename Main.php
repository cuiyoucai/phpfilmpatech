<?php
//error_reporting(0); 
require_once 'BaseTool.php';
require_once 'FilmBase.php';
require_once 'FilmReview.php';
require_once 'FilmComment.php';
$types = array(
//'爱情',
'喜剧',
'动画',
'经典',
'科幻',
'动作',
'青春',
'剧情',
'悬疑',
'惊悚',
'纪录片',
'犯罪',
'励志',
'文艺',
'搞笑',
'恐怖',
'战争',
'魔幻',
'黑色幽默',
'情色',
'传记',
'童年',
'暴力',
'感人',
'音乐',
'同志',
'黑帮',
'女性',
'浪漫',
'家庭',
'童话',
'史诗',
'cult',
'震撼',
);
$blackFilm = array("1788392");   //信息异常电影过滤你大爷
foreach ($types as $key=>$vl){
//	if($vl!='史诗'){日本鬼子
//		continue;
//	}  //德国的手表
	for($i=0;$i<=49;$i++){
		$pages = $i*20;
		$link = "http://movie.douban.com/tag/".$vl."?start=".$pages."&type=T";
		$html = getHtml($link);
		$pattern = '/class="nbg" href="http:\/\/movie.douban.com\/subject\/(.*?)\//si';
		preg_match_all($pattern,$html,$matches);
		if(!isset($matches[1][0])){
			break;
		}
		foreach ($matches[1] as $fid){
			if(isExit($fid) || in_array($fid,$blackFilm)){
				continue;
			}
			insertFilmDB(getDetail($fid),'f');
			insertFilmDB(getComment($fid),'c');
			insertFilmDB(getReview($fid),'r');
		}
	}
}
