<?php
/** 
*	@name: 视频缓存版api
*	@author: 麦田一根葱(MaiCong.me)
*	@update: 2014年3月28日
*	@help: http://www.yuxiaoxi.com/2014-03-28-mcvapi-code-share.html
*/

// 禁用错误报告
error_reporting(0); 

//数据缓存路径
define("M_CPATH", "videocache/");

// 数据缓存文件类型
define("M_CMIME", ".json");

// 数据缓存时效(秒)
define("M_CTIME", 1800);

//申请AppKey：http://www.bilibili.tv/account/getapi
define("M_APPSECRET", "abcdefghijklmnopqrstuvwxyz"); /* App-Secret */
define("M_APPKEY", "zyxwvutsrqponm");/* App-Key */

// 创建文件夹并赋权限
if(!is_dir(M_CPATH)) { 
	mkdir(M_CPATH, 0755) ; 
}

// bilibili sign
function get_sign($params, $key) {
	$_data = array();
	ksort($params);
	reset($params);
	foreach ($params as $k => $v) {
		$_data[] = $k . '=' . rawurlencode($v);
	}
	$_sign = implode('&', $_data);
	return array('sign' => strtolower(md5($_sign . $key)),'params' => $_sign);
}

// 数据缓存
function mcv_save($type, $url, $array) {
	$path = M_CPATH."{$type}_{$url}".M_CMIME;
	$save = fopen($path,"w");
	fwrite($save,serialize($array));
	fclose($save);
}

//处理网址
$url = isset($_GET['url']) ? $_GET['url'] : null;
$temp = explode('/', htmlspecialchars($url));
$host = $temp[2];

//允许的网址(目前只支持这两个视频网站)
$urlarr = array("v.youku.com","www.bilibili.tv","bilibili.kankanews.com");

//错误提示信息
$error1 = array ('status'=>'-1','msg'=>'请带上视频地址','help'=>'http://www.yuxiaoxi.com/2014-03-07-wordpress-maicong-video.html#status-1');
$error2 = array ('status'=>'-2','msg'=>'无法解析该视频地址','help'=>'http://www.yuxiaoxi.com/2014-03-07-wordpress-maicong-video.html#status-2');
$error3 = array ('status'=>'-3','msg'=>'视频地址参数不正确','help'=>'http://www.yuxiaoxi.com/2014-03-07-wordpress-maicong-video.html#status-3');
$error4 = array ('status'=>'-4','msg'=>'该视频不存在或者已被删除','help'=>'http://www.yuxiaoxi.com/2014-03-07-wordpress-maicong-video.html#status-4');

//保存记录 {时间@来路@视频地址}
if(isset($_SERVER['HTTP_REFERER'])){
	$referer = htmlspecialchars($_SERVER['HTTP_REFERER']);
	$time = date("Y-m-d H:i:s", time());
	$rcont = "{$time}@{$referer}@$url\r\n";
	$rpath = M_CPATH."_usereferer".M_CMIME;
	$rsave = fopen($rpath,"a");
	fwrite($rsave,$rcont);
	fclose($rsave);
}

//处理数据
if($url == null){
	$videos = $error1;
}else{
	$saveurl = md5($url);  /* url md5加密 */
	if($host == $urlarr[0]){
		$find = preg_match("#http://v\.youku\.com/v_show/id_(?<id>[a-z0-9_=\-]+)#i", $url, $match);
		if($find) {
			$filelist = file_get_contents("http://v.youku.com/player/getPlayList/VideoIDS/".$match['id']);
			$result = json_decode($filelist,true);
			$json = $result['data'][0];
			if(!empty($json)){
				$hour = floor( $json["seconds"]/3600 );
				$hour = $hour > 0 ? "{$hour}:" : "";
				$time = $hour . gmstrftime('%M:%S', $json["seconds"]);	
				$offsite = "http://youkuvideos.cdn.duapp.com/loader.swf?VideoIDS=".$match['id'];
				$oriurl = "http://player.youku.com/embed/".$match['id'];
				$fileinfo = file_get_contents("http://v.youku.com/QVideo/~ajax/getVideoPlayInfo?id=".$json['videoid']."&sid=".$json['show']['showid']."&type=vv");
				$info = json_decode($fileinfo, true);
				$play = $info['vv'];
				$ykvideo = array ('status'=>'1','type'=>'youku','play'=>$play,'title'=>$json['title'],'pic'=>$json['logo'],'time'=>$time,'offsite'=>$offsite,'onsite'=>$url,'oriurl'=>$oriurl);	 /* 未缓存数据 */
				$path = M_CPATH."youku_{$saveurl}".M_CMIME;
				if(!file_exists($path)) {
					mcv_save("youku", $saveurl, $ykvideo);
					$videos = $ykvideo;
				}else{
					$now=time();
					$mtime = filemtime($path);
					if($now - $mtime > M_CTIME){
						mcv_save("youku", $saveurl, $ykvideo);
						$videos = $ykvideo;
					}else{
						$opt = unserialize(file_get_contents($path));
						$videos  = array ('status'=>'1','type'=>'youku','play'=>$opt['play'],'title'=>$opt['title'],'pic'=>$opt['pic'],'time'=>$opt['time'],'offsite'=>$opt['offsite'],'onsite'=>$opt['onsite'],'oriurl'=>$opt['oriurl']); /* 已缓存数据 */
					}
				}
			}else{
				$videos = $error4;
			}
		}else{
			$videos = $error3;
		}
	}elseif($host == $urlarr[1] || $host == $urlarr[2]){
		$find = preg_match("#http://(www\.bilibili\.tv|bilibili\.kankanews\.com)/video/av(?<av>\d+)#i", $url, $match);
		if($find) {
			$params = array("type"=>"json","appkey"=>M_APPKEY,"id"=>$match['av']);
			$sign = get_sign($params,M_APPSECRET);
			$filelist = file_get_contents("http://api.bilibili.tv/view?".$sign['params']."&sign=".$sign['sign']);
			$json = json_decode($filelist,true);
			if($json['cid']){
				$fileint = file_get_contents("http://interface.bilibili.cn/player?id=cid:".$json['cid']."&aid=".$match['av']);
				preg_match_all('/<([a-z]+)>(.*?)<(\/[a-z]+)>/', $fileint, $int, PREG_SET_ORDER);
				$blvideo = array ('status'=>'1','type'=>'bilibili','play'=>$int[11][2],'title'=>$json['title'],'pic'=>$json['pic'],'time'=>$int[15][2],'offsite'=>$json['offsite'],'onsite'=>$url,'oriurl'=>$int[7][2]); /* 未缓存数据 */
				$path = M_CPATH."bilibili_{$saveurl}".M_CMIME;
				if(!file_exists($path)) {
					mcv_save("bilibili", $saveurl, $blvideo);
					$videos = $blvideo;
				}else{
					$now=time();
					$mtime = filemtime($path);
					if($now - $mtime > M_CTIME){
						mcv_save("bilibili", $saveurl, $blvideo);
						$videos = $blvideo;
					}else{
						$opt = unserialize(file_get_contents($path));
						$videos  = array ('status'=>'1','type'=>'bilibili','play'=>$opt['play'],'title'=>$opt['title'],'pic'=>$opt['pic'],'time'=>$opt['time'],'offsite'=>$opt['offsite'],'onsite'=>$opt['onsite'],'oriurl'=>$opt['oriurl']); /* 已缓存数据 */
					}
				}
			}else{
				$videos = $error4;
			}
		}else{
			$videos = $error3;
		}
	}else{
		$videos = $error2;
	}
}

//以json格式输出
header('Content-type:text/json');
echo json_encode($videos);

?>