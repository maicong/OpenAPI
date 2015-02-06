<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved.
$index="index";
require_once 'common.inc.php';
require_once 'head.php';
$index=array();
$index[index]='index';
$index[content]=$met_index_content;
$index[lang]=$lang;
$index[news_no]=$index_news_no;
$index[product_no]=$index_product_no;
$index[download_no]=$index_download_no;
$index[img_no]=$index_img_no;
$index[job_no]=$index_job_no;
$index[link_ok]=$index_link_ok;
$index[link_img]=$index_link_img;
$index[link_text]=$index_link_text;
$show['description']=$met_description;
$show['keywords']=$met_keywords;
require_once '../public/php/methtml.inc.php';
include template('404');
//$metinfonow=$met_member_force;
$met_webhtm=1;
$html_filename='../404';
footer();
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>