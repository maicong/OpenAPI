<?php
/**
*	@name: 缓存版视频页
*	@author: 麦葱(MaiCong.me)
*	@update: 2014年3月7日
*	@help: http://www.yuxiaoxi.com/2014-03-07-wordpress-maicong-video.html
*/

// 禁用错误报告
error_reporting(0);

// 检测并创建文件夹
if(!is_dir(ABSPATH . 'videocache/')) { 
	mkdir(ABSPATH . 'videocache/', 0755) ; 
}

// 数据缓存
function mcv_save($url, $str) {
	$path = ABSPATH . "videocache/{$url}.json";
	$save = fopen($path,"w");
	fwrite($save,$str);
	fclose($save);
}

// curl抓取
function curl_file($url) {
	$ch = curl_init("http://www.yuxiaoxi.com/mcvapi?url=".$url); /* 接口文件是mcv_api.php*/
	curl_setopt($ch, CURLOPT_TIMEOUT, 30);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ;
	curl_setopt($ch, CURLOPT_REFERER, "http://www.yuxiaoxi.com/");
	$file = curl_exec($ch);
	curl_close($ch);
	return $file;
}

function mcv_videos($url) {
	$saveurl = md5($url);
	$path = ABSPATH . "videocache/{$saveurl}.json";
	$tpldir = get_bloginfo('template_directory');
	$content = "<li class=\"loading\"><div class=\"pts\">loading</div><a href=\"javascript:;\" class=\"videoPic\"><img src=\"{$tpldir}/images/mcv_loading.gif\" alt=\"null\" class=\"load\" /><time>00:00</time></a><a href=\"javascript:;\" data-onsite=\"{$url}\" class=\"title\" title=\"正在更新视频...\">正在更新视频...</a></li>\r\n\t";
	if(!file_exists($path)) {
		mcv_save(md5($url), curl_file($url));
		return $content;
	}else{
		$now=time();
		$ftime = filemtime($path);
		if($now - $ftime > 3600) { /* 缓存文件有效期(秒) */
			mcv_save(md5($url), curl_file($url));
			return $content;
		}else{
			$filepath = file_get_contents($path);
			$json = json_decode($filepath, true);
			if($json['status'] == 1){
				return "<li class=\"{$json['type']}\"><div class=\"pts\">{$json['play']} pts</div><a href=\"javascript:;\" data-offsite=\"{$json['offsite']}\" data-oriurl=\"{$json['oriurl']}\" class=\"videoPic\"><img src=\"{$json['pic']}\" alt=\"{$json['title']}\" /><time>{$json['time']}</time></a><a href=\"javascript:;\" data-onsite=\"{$json['onsite']}\" class=\"title\" title=\"{$json['title']}\">{$json['title']}</a></li>";
			}else{
				return "<li class=\"null\"><div class=\"pts\">null</div><a href=\"javascript:;\" class=\"videoPic\"><img src=\"{$tpldir}/images/mcv_sad.png\" alt=\"null\" class=\"load\" /><time>00:00</time></a><a href=\"javascript:;\" data-onsite=\"{$url}\" class=\"title\" title=\"无法解析该视频\">无法解析该视频</a></li>\r\n\t";
			}
		}
	}
}

//注册wordpress短代码[mcv][/mcv]
function mcv_shortcode($atts, $content=null) {
	extract(shortcode_atts(array(), $atts));
	$content = esc_url($content);
	return mcv_videos($content);
}
add_shortcode('mcv','mcv_shortcode');


/** 
* Code End
*/