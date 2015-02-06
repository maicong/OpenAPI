<?php
$dataoptimize_html='html';//模板文件类型，可以为htm,html,php
$metinfouiok=0;//是否开启UI，开启后将加载UI样式和JS文件
$metadmin[categorynamemark]=0; //栏目名称修饰名称
$metadmin[categorymarkimage]=0; //栏目标识图片
$metadmin[categoryimage]=1; //栏目图片
$metadmin[homecontent]=1; //首页内容
$metadmin[newsimage]=1; //图片文章
$metadmin[newscom]=1; //推荐文章
$metadmin[productnew]=0; //最新产品
$metadmin[productcom]=1; //推荐产品
$metadmin[imgnew]=0; //最新图片
$metadmin[imgcom]=1; //推荐图片
$metadmin[downloadnew]=0; //最新下载
$metadmin[downloadcom]=1; //推荐下载

/* 截取内容 */
function cut_content($content, $length, $charset = 'utf-8') {
    if(mb_strlen($content, 'utf-8') > $length){
        $content = mb_substr($content, 0, $length, $charset) . "...";
    }
    return $content;
}

/* 获取内容中的图片 */
function get_content_img($classid, $title = false) {
    global $class_list;
    $content = $class_list[$classid]['content'];
    $title   = $class_list[$classid]['name'];
    $output  = preg_match_all('/\<img.+?src="(.+?)".*?\/>/is', $content, $matches, PREG_SET_ORDER);
    if (count($matches) > 0) {
        foreach ($matches as $key => $value) {
            if ($title) {
                $src[$key] = array($title . $key, $value[1]);
            } else {
                $src[$key] = $value[1];
            }
        }
        return $src;
    }
    return false;
}

/* 格式代码 */
function clear_html($str) {
    $str = str_replace(array('&nbsp;', '&amp;', '&quot;', '&#039;', '&ldquo;', '&rdquo;', '&mdash;', '&lt;', '&gt;', '&middot;', '&hellip;'), array(' ', '&', '"', "'", '“', '”', '—', '<', '>', '·', '…'), $str);
    $str = preg_replace('/\s+/iU', '', $str);
    $str = preg_replace("/\<[a-z]+(.*)\>/iU", "", $str);
    $str = preg_replace("/\<\/[a-z]+\>/iU", "", $str);
    $str = str_replace(array(' ', '  ', chr(13), chr(10), '&nbsp;'), array('', '', '', '', ''), $str);
    $str = htmlspecialchars(strip_tags($str), ENT_QUOTES);
    return $str;
}