<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
require_once '../login/login_check.php';
if($met_pseudo||$pseudo_download){
	if(!$pseudo_download){
		if($lang==$met_index_type && file_exists("../../index.htm"))@unlink("../../index.htm");
		if($lang==$met_index_type && file_exists("../../index.html"))@unlink("../../index.html");
		if(file_exists("../../index_".$lang.".htm"))@unlink("../../index_".$lang.".htm");
		if(file_exists("../../index_".$lang.".html"))@unlink("../../index_".$lang.".html");
	}
	$nowpath=explode('/',$_SERVER["PHP_SELF"]);
	$cunt=count($nowpath)-3;
	for($i=0;$i<$cunt;$i++){
		$metbase.= $nowpath[$i].'/';
	}
	if(stristr($_SERVER['SERVER_SOFTWARE'],'Apache')){
		$htaccess = 'RewriteEngine on'."\n";
		$htaccess.= '# 是否显示根目录下文件列表'."\n";
		$htaccess.= 'Options -Indexes'."\n";
		$htaccess.= 'RewriteBase '.$metbase."\n";
		$htaccess.= 'RewriteRule ^(.*)\.(asp|aspx|asa|asax|dll|jsp|cgi|fcgi|pl)(.*)$ /404.html'."\n";
		$htaccess.= '# Rewrite 系统规则请勿修改'."\n";
		$htaccess.= 'RewriteRule ^index-([a-zA-Z0-9_^\x00-\xff]+).html$ index.php?lang=$1&pseudo_jump=1'."\n";
		$htaccess.= 'RewriteRule ^([a-zA-Z0-9_^\x00-\xff]+)/list-([a-zA-Z0-9_^\x00-\xff]+)-([a-zA-Z0-9_^\x00-\xff]+).html$ $1/index.php?lang=$3&metid=$2&list=1&pseudo_jump=1'."\n";
		$htaccess.= 'RewriteRule ^([a-zA-Z0-9_^\x00-\xff]+)/list-([a-zA-Z0-9_^\x00-\xff]+)-([0-9_]+)-([a-zA-Z0-9_^\x00-\xff]+).html$ $1/index.php?lang=$4&metid=$2&list=1&page=$3&pseudo_jump=1'."\n";
		$htaccess.= 'RewriteRule ^([a-zA-Z0-9_^\x00-\xff]+)/jobcv-([a-zA-Z0-9_^\x00-\xff]+)-([a-zA-Z0-9_^\x00-\xff]+).html$ $1/cv.php?lang=$3&selectedjob=$2&pseudo_jump=1'."\n";
		$htaccess.= 'RewriteRule ^([a-zA-Z0-9_^\x00-\xff]+)/product-list-([a-zA-Z0-9_^\x00-\xff]+).html$ $1/product.php?lang=$2&pseudo_jump=1'."\n";
		$htaccess.= 'RewriteRule ^([a-zA-Z0-9_^\x00-\xff]+)/img-list-([a-zA-Z0-9_^\x00-\xff]+).html$ $1/img.php?lang=$2&pseudo_jump=1'."\n";
		$htaccess.= 'RewriteRule ^([a-zA-Z0-9_^\x00-\xff]+)/([a-zA-Z0-9_^\x00-\xff^\x00-\xff]+)-([a-zA-Z0-9_^\x00-\xff]+).html$ $1/index.php?lang=$3&metid=$2&pseudo_jump=1'."\n";
		$htaccess.= 'RewriteRule ^tag/([\s\S]+)-([a-zA-Z0-9_^\x00-\xff]+)$ search/search.php?class1=&class2=&class3=&searchtype=0&searchword=$1&lang=$2'."\n";
		$httpdurl='../../.htaccess';
		//if(!is_writable('../../.htaccess'))@chmod('../../.htaccess',0777);
		$fp = fopen('../../.htaccess',w);
		fputs($fp, $htaccess);
		fclose($fp);
	}
	else if(stristr($_SERVER['SERVER_SOFTWARE'],'nginx')){
		$htaccess.= 'rewrite ^/index-([a-zA-Z0-9_^x00-xff]+).html$ /index.php?lang=$1&pseudo_jump=1;'."\n";
		$htaccess.= 'rewrite ^/([a-zA-Z0-9_^x00-xff]+)/list-([a-zA-Z0-9_^x00-xff]+)-([a-zA-Z0-9_^x00-xff]+).html$ /$1/index.php?lang=$3&metid=$2&list=1&pseudo_jump=1;'."\n";
		$htaccess.= 'rewrite ^/([a-zA-Z0-9_^x00-xff]+)/list-([a-zA-Z0-9_^x00-xff]+)-([0-9_]+)-([a-zA-Z0-9_^x00-xff]+).html$ /$1/index.php?lang=$4&metid=$2&list=1&page=$3&pseudo_jump=1;'."\n";
		$htaccess.= 'rewrite ^/([a-zA-Z0-9_^x00-xff]+)/jobcv-([a-zA-Z0-9_^x00-xff]+)-([a-zA-Z0-9_^x00-xff]+).html$ /$1/cv.php?lang=$3&selectedjob=$2&pseudo_jump=1;'."\n";
		$htaccess.= 'rewrite ^/([a-zA-Z0-9_^x00-xff]+)/product-list-([a-zA-Z0-9_^x00-xff]+).html$ /$1/product.php?lang=$2&pseudo_jump=1;'."\n";
		$htaccess.= 'rewrite ^/([a-zA-Z0-9_^x00-xff]+)/img-list-([a-zA-Z0-9_^x00-xff]+).html$ /$1/img.php?lang=$2&pseudo_jump=1;'."\n";
		$htaccess.= 'rewrite ^/([a-zA-Z0-9_^x00-xff]+)/([a-zA-Z0-9_^x00-xff]+)-([a-zA-Z0-9_^x00-xff]+).html$ /$1/index.php?lang=$3&metid=$2&pseudo_jump=1;'."\n";
		$htaccess.= 'rewrite ^/tag/([\s\S]+)-([a-zA-Z0-9_^\x00-\xff]+)$ /search/search.php?class1=&class2=&class3=&searchtype=0&searchword=$1&lang=$2;'."\n";
		$httpdurl='../../.htaccess';
		$fp = fopen('../../.htaccess',w);
		fputs($fp, $htaccess);
		fclose($fp);
	}
	else if(stristr($_SERVER['SERVER_SOFTWARE'],'IIS/7')){
		$web = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
		$web.= '<configuration>'."\n";
		$web.= '<system.webServer>'."\n";
		$web.= '<rewrite>'."\n";
		$web.= '<rules>'."\n";
		$web.= '<rule name="rule1" stopProcessing="true">'."\n";
		$web.= '<match url="^index-([a-zA-Z0-9_\u4e00-\u9fa5]+).html" />'."\n";
		$web.= '<action type="Rewrite" url="index.php?lang={R:1}&amp;pseudo_jump=1" />'."\n";
		$web.= '</rule>'."\n";
		$web.= '<rule name="rule2" stopProcessing="true">'."\n";
		$web.= '<match url="^([a-zA-Z0-9_\u4e00-\u9fa5]+)/list-([a-zA-Z0-9_\u4e00-\u9fa5]+)-([a-zA-Z0-9_\u4e00-\u9fa5]+).html" />'."\n";
		$web.= '<action type="Rewrite" url="{R:1}/index.php?lang={R:3}&amp;metid={R:2}&amp;list=1&amp;pseudo_jump=1" />'."\n";
		$web.= '</rule>'."\n";
		$web.= '<rule name="rule3" stopProcessing="true">'."\n";
		$web.= '<match url="^([a-zA-Z0-9_\u4e00-\u9fa5]+)/list-([a-zA-Z0-9_\u4e00-\u9fa5]+)-([0-9_]+)-([a-zA-Z0-9_\u4e00-\u9fa5]+).html" />'."\n";
		$web.= '<action type="Rewrite" url="{R:1}/index.php?lang={R:4}&amp;metid={R:2}&amp;list=1&amp;page={R:3}&amp;pseudo_jump=1" />'."\n";
		$web.= '</rule>'."\n";
		$web.= '<rule name="rule4" stopProcessing="true">'."\n";
		$web.= '<match url="^([a-zA-Z0-9_\u4e00-\u9fa5]+)/jobcv-([a-zA-Z0-9_\u4e00-\u9fa5]+)-([a-zA-Z0-9_\u4e00-\u9fa5]+).html" />'."\n";
		$web.= '<action type="Rewrite" url="{R:1}/cv.php?lang={R:3}&amp;selectedjob={R:2}&amp;pseudo_jump=1" />'."\n";
		$web.= '</rule>'."\n";
		$web.= '<rule name="rule5" stopProcessing="true">'."\n";
		$web.= '<match url="^([a-zA-Z0-9_\u4e00-\u9fa5]+)/product-list-([a-zA-Z0-9_\u4e00-\u9fa5]+).html" />'."\n";
		$web.= '<action type="Rewrite" url="{R:1}/product.php?lang={R:2}&amp;pseudo_jump=1" />'."\n";
		$web.= '</rule>'."\n";
		$web.= '<rule name="rule6" stopProcessing="true">'."\n";
		$web.= '<match url="^([a-zA-Z0-9_\u4e00-\u9fa5]+)/img-list-([a-zA-Z0-9_\u4e00-\u9fa5]+).html" />'."\n";
		$web.= '<action type="Rewrite" url="{R:1}/img.php?lang={R:2}&amp;pseudo_jump=1" />'."\n";
		$web.= '</rule>'."\n";
		$web.= '<rule name="rule7" stopProcessing="true">'."\n";
		$web.= '<match url="^([a-zA-Z0-9_\u4e00-\u9fa5]+)/([a-zA-Z0-9_\u4e00-\u9fa5]+)-([a-zA-Z0-9_\u4e00-\u9fa5]+).html" />'."\n";
		$web.= '<action type="Rewrite" url="{R:1}/index.php?lang={R:3}&amp;metid={R:2}&amp;pseudo_jump=1" />'."\n";
		$web.= '</rule>'."\n";
		$web.= '<rule name="rule8" stopProcessing="true">'."\n";
		$web.= '<match url="^tag/([\s\S]+)-([a-zA-Z0-9_\u4e00-\u9fa5]+)" />'."\n";
		$web.= '<action type="Rewrite" url="search/search.php?class1=&amp;class2=&amp;class3=&amp;searchtype=0&amp;searchword={R:1}&amp;lang={R:2}" />'."\n";
		$web.= '</rule>'."\n";
		$web.= '</rules>'."\n";
		$web.= '</rewrite>'."\n";
		$web.= '</system.webServer>'."\n";
		$web.= '</configuration>'."\n";
		$httpdurl='../../web.config';
		//if(!is_writable('../../.htaccess'))@chmod('../../.htaccess',0777);
		$fp = fopen('../../web.config',w);
		fputs($fp, $web);
		fclose($fp);		
	}
	else{
		$httpd = '[ISAPI_Rewrite]'."\n";
		$httpd.= '# 3600 = 1 hour'."\n";
		$httpd.= 'CacheClockRate 3600'."\n";
		$httpd.= 'RepeatLimit 32'."\n";
		$httpd.= 'RewriteRule '.$metbase.'index-([a-zA-Z0-9_^\x00-\xff]+).html '.$metbase.'index.php\?lang=$1&pseudo_jump=1'."\n";
		$httpd.= 'RewriteRule '.$metbase.'([a-zA-Z0-9_^\x00-\xff]+)/list-([a-zA-Z0-9_^\x00-\xff]+)-([a-zA-Z0-9_^\x00-\xff]+).html '.$metbase.'$1/index.php\?lang=$3&metid=$2&list=2&pseudo_jump=1'."\n";
		$httpd.= 'RewriteRule '.$metbase.'([a-zA-Z0-9_^\x00-\xff]+)/list-([a-zA-Z0-9_^\x00-\xff]+)-([0-9_]+)-([a-zA-Z0-9_^\x00-\xff]+).html '.$metbase.'$1/index.php\?lang=$4&metid=$2&list=1&page=$3&pseudo_jump=1'."\n";
		$httpd.= 'RewriteRule '.$metbase.'([a-zA-Z0-9_^\x00-\xff]+)/jobcv-([a-zA-Z0-9_^\x00-\xff]+)-([a-zA-Z0-9_^\x00-\xff]+).html '.$metbase.'$1/cv.php\?lang=$3&selectedjob=$2&pseudo_jump=1'."\n";
		$httpd.= 'RewriteRule '.$metbase.'([a-zA-Z0-9_^\x00-\xff]+)/product-list-([a-zA-Z0-9_^\x00-\xff]+).html '.$metbase.'$1/product.php\?lang=$2&pseudo_jump=1'."\n";
		$httpd.= 'RewriteRule '.$metbase.'([a-zA-Z0-9_^\x00-\xff]+)/img-list-([a-zA-Z0-9_^\x00-\xff]+).html '.$metbase.'$1/img.php\?lang=$2&pseudo_jump=1'."\n";
		$httpd.= 'RewriteRule '.$metbase.'([a-zA-Z0-9_^\x00-\xff]+)/([a-zA-Z0-9_^\x00-\xff^\x00-\xff]+)-([a-zA-Z0-9_^\x00-\xff]+).html '.$metbase.'$1/index.php\?lang=$3&metid=$2&pseudo_jump=1'."\n";
		$httpd.= 'RewriteRule '.$metbase.'tag/([\s\S]+)-([a-zA-Z0-9_^\x00-\xff]+) '.$metbase.'search/search.php\?class1=&class2=&class3=&searchtype=0&searchword=$1&lang=$2'."\n";
		$metbase=explode('/',$metbase);
		$basenum=count($metbase);
		for($i=0;$i<$basenum;$i++){
			if($metbase[$i]!='')$little.='../';
		}
		$httpdurl = $little.'../../httpd.ini';
		//if(!is_writable($httpdurl))@chmod($httpdurl,0777);
		$fp = fopen($httpdurl,w);
		fputs($fp, $httpd);
		fclose($fp);
	}
	if($pseudo_download){
		include $depth."../include/pclzip.lib.php";
		$archive = new PclZip('metinfo_pseudo_rule.zip');
		$archive->create($httpdurl,PCLZIP_OPT_REMOVE_PATH,$little.'../../');
		@file_unlink($httpdurl);
		header("Content-type:application/zip;");
		$ua = $_SERVER["HTTP_USER_AGENT"];
		$title="{$lang_rewriteruledownload2}({$_SERVER['SERVER_SOFTWARE']}).zip";
		if(preg_match("/MSIE/", $ua)){
			header('Content-Disposition: attachment; filename="' . urlencode($title) . '"');
		}else{
			header('Content-Disposition: attachment; filename="' . $title . '"');
		}
		readfile("metinfo_pseudo_rule.zip");
		@file_unlink("metinfo_pseudo_rule.zip");
	}
}elseif($depsdo=='deleteall'){
	if(file_exists('../../httpd.ini'))@unlink('../../httpd.ini');
	if(file_exists('../../.htaccess'))@unlink('../../.htaccess');
	if(file_exists('../../web.config'))@unlink('../../web.config');
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>