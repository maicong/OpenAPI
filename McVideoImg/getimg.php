<?php
/** 
*	@name: 视频缩略图api
*	@author: 麦葱(MaiCong.me)
*	@update: 2014年5月15日
*/

//代码片段 - PHP168

//获取并生成缩略图
function vthumb($aid){
	global $db,$pre,$webdb;
	$path = ROOT_PATH."cache/vimg/$aid.jpg";
	if(file_exists($path) && filesize($path) != 0 ){
		$vtb[bimg] = $webdb[www_url]."/cache/vimg/$aid.jpg";
	}else{
		makepath(ROOT_PATH."cache/vimg/");
		if(filesize($path) == 0) del_file($path);
		$vdb=$db->get_one("SELECT R.*,A.* FROM {$pre}article A LEFT JOIN {$pre}reply R ON A.aid=R.aid WHERE A.aid='$aid' ORDER BY R.rid ASC LIMIT 1");
		preg_match('/\<embed.+?src="(.+?)".*?>/is', $vdb[content], $embed);
		$temp = explode('/', htmlspecialchars($embed[1]));
		$host = $temp[2];
		switch($host){
			case "player.56.com":
				preg_match("#http://player.56.com/v_(\w+)#i", $embed[1], $match);
				$info = json_decode(vcurl($host,"http://vxml.56.com/json/$match[1]/"),true);
				if($info[status] == 1){
					$vtb[bimg] = $info[info][bimg];
					write_file($path,vcurl($host,$vtb[bimg]));
				}else{
					$vtb[bimg] = $webdb[www_url].'/images/video.jpg';
				}
				break;
			case "player.youku.com":
				preg_match("#http://player.youku.com/player.php/sid/(\w+)/v.swf#i", $embed[1], $match);
				$info = json_decode(vcurl($host,"http://v.youku.com/player/getPlayList/VideoIDS/$match[1]/"),true);
				if(!empty($info[data][0])){
					$vtb[bimg] = $info[data][0][logo];
					write_file($path,vcurl($host,$vtb[bimg]));
				}else{
					$vtb[bimg] = $webdb[www_url].'/images/video.jpg';
				}
				break;
			case "www.tudou.com":
				preg_match("#http://www.tudou.com/(v|l)/([\w-]+)/#i", $embed[1], $match);
				$info = json_decode(vcurl($host,"http://api.tudou.com/v3/gw?method=item.info.get&appKey=myKey&format=json&itemCodes=$match[2]"),true);
				if(!empty($info[multiResult][results][0])){
					$vtb[bimg] = $info[multiResult][results][0][bigPicUrl];
					write_file($path,vcurl($host,$vtb[bimg]));
				}else{
					$vtb[bimg] = $webdb[www_url].'/images/video.jpg';
				}
				break;
			case "player.video.qiyi.com":
				preg_match("#http://player.video.qiyi.com/(\w+)/(\d+)/(\d+)/v_(\w+).swf#i", $embed[1], $match);
				if(!empty($match)){
					$cont = vcurl($host,"http://www.iqiyi.com/v_$match[4].html");
					preg_match('/itemprop="image" content="(.*?).jpg"/s',$cont,$info);
					if(empty($info)) {
						preg_match('/itemprop="thumbnailUrl" content=\'(.*?)\'/s',$cont,$info);
						$vtb[bimg] = $info[1];
					}else{
						$vtb[bimg] = !preg_match("/404/", implode(",",get_headers($vtb[bimg]))) ? $info[1]."_116_65.jpg" : $info[1]."_180_101.jpg";
					}
					write_file($path,vcurl($host,$vtb[bimg]));
				}else{
					$vtb[bimg] = $webdb[www_url].'/images/video.jpg';
				}
				break;
			case "player.pps.tv":
				preg_match("#http://player.pps.tv/player/sid/(\w+)/v.swf#i", $embed[1], $match);
				if(!empty($match)){
					$cont = vcurl($host,"http://v.pps.tv/play_$match[1].html");
					preg_match('/sharepic":"(.*?)","/s',$cont,$info);
					if(!empty($info)){
						$vtb[bimg] = stripslashes($info[1]);
						write_file($path,vcurl($host,$vtb[bimg]));
					}else{
						$vtb[bimg] = $webdb[www_url].'/images/video.jpg';
					}
				}else{
					$vtb[bimg] = $webdb[www_url].'/images/video.jpg';
				}
				break;
			case "static.video.qq.com":
				preg_match("#http://static.video.qq.com/TPout.swf\?vid=(\w+)#i", $embed[1], $match);
				$vtb[bimg] = "http://vpic.video.qq.com/$match[1]_ori_1.jpg";
				write_file($path,vcurl($host,$vtb[bimg]));
				break;
			case "resources.baomihua.com":
				preg_match("#http://resources.baomihua.com/(\d+).swf#i", $embed[1], $match);
				$vtb[bimg] = "http://img02.video.baomihua.com/x/$match[1].jpg";
				write_file($path,vcurl($host,$vtb[bimg]));
				break;
			default:
				$vtb[host] = $host;
				$vtb[bimg] = $webdb[www_url].'/images/video.jpg';
		}
	}
	return $vtb;
}

/** 
* Code End
*/