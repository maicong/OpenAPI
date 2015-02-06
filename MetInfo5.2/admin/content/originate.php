<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
require_once '../login/login_check.php';
require_once '../include/common.inc.php';
$css_url="../templates/".$met_skin."/css";
$img_url="../templates/".$met_skin."/images";

//$lang_searchresult
$id=unescape($id);
if($id!=null){
	echo "<style type='text/css'> 

*{margin:0;padding:0;} 
.ifr_div{width:600px;height:600px; position:relative;} 
.ifr_div img{ vertical-align:middle;} 
.proccess{border:0px solid;border-color:#000000;height:300px;line-height:300px;width:300px;text-align:center;background:#ffffff;margin:0;position:absolute;top:20%;left:36%;} 
.proccess1{border:0px solid;border-color:#000000;height:300px;width:300px;text-align:center;background:#ffffff;margin:0;position:absolute;top:25%;left:36%;} 
.proccess b{vertical-align:middle;background:url(http://ok22.org/upload/images/20110902143538381.gif) no-repeat 0 center;padding-left:35px;display:inline-block;} 

</style> 
    <div class='proccess' id='loading'><b>{$lang_search_inthe}。。。</b></div> 
";
}
		$anyid=29;
		$id=trim($id);
		foreach ($met_class1 as $key=>$val){
			if(strstr($val['name'], $id)&&$val['module']<9 && !$val['if_in']){
				$contentlistes[] = $val;
			}
		}
		foreach($contentlistes as $key=>$val){
				$sum='';
				$sum1='';
				$sum2='';
				$sum3='';
				$sum_count=array();
				switch($val['module']){
					case '1':
						$val['url']='about/content.php?id='.$val[id].'&lang='.$lang.'&anyid='.$anyid;	
							$val['conturl']='about/about.php?id='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
							$val['set']="<div>
									<p class='lt'><a href='{$val[url]}'>{$lang_addinfo}</a></p><span>-</span><p class='rt'><a href='{$val[conturl]}'>{$lang_manager}</a></p>
									</div>";
						
					break;
					case '2':
							$val['url']='article/content.php?class1='.$val[id].'&action=add&lang='.$lang.'&anyid='.$anyid;	
							$val['conturl']='article/index.php?class1='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
							$val['set']="<div>
									<p class='lt'><a href='{$val[url]}'>{$lang_addinfo}</a></p><span>-</span><p class='rt'><a href='{$val[conturl]}'>{$lang_manager}</a></p>
									</div>";
						
					break;
					case '3':
						$val['url']='product/content.php?class1='.$val[id].'&action=add&lang='.$lang.'&anyid='.$anyid;
						$val['conturl']='product/index.php?class1='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
						$val['set']="<div>
									<p class='lt'><a href='{$val[url]}'>{$lang_addinfo}</a></p><span>-</span><p class='rt'><a href='{$val[conturl]}'>{$lang_manager}</a></p>
									</div>";
							
					break;
					case '4':
						$val['url']='download/content.php?class1='.$val[id].'&action=add&lang='.$lang.'&anyid='.$anyid;
						$val['conturl']='download/index.php?class1='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
						$val['set']="<div>
									<p class='lt'><a href='{$val[url]}'>{$lang_addinfo}</a></p><span>-</span>
									<p class='rt'><a href='{$val[conturl]}'>{$lang_manager}</a></p>
									</div>";
						
					break;
					case '5':
						$val['url']='img/content.php?class1='.$val[id].'&action=add&lang='.$lang.'&anyid='.$anyid;
						$val['conturl']='img/index.php?class1='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
						$val['set']="<div>
									<p class='lt'><a href='{$val[url]}'>{$lang_addinfo}</a></p><span>-</span>
									<p class='rt'><a href='{$val[conturl]}'>{$lang_manager}</a></p>
									</div>";
						$sum_count=$db->get_all("select * from $met_column where lang='$lang' and bigclass='$val[id]'");
						
					break;
					case '6':
						$val['url']='job/content.php?class1='.$val[id].'&action=add&lang='.$lang.'&anyid='.$anyid;
						$val['conturl']='job/index.php?class1='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
						$val['incurl']='job/inc.php?lang='.$lang.'&anyid='.$anyid;
						$val['cvurl']='job/cv.php?class1='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
						$val['set']="<div>
									<p class='lt'><a href='{$val[conturl]}'>{$lang_manager}</a></p><span>-</span>
									<p class='rt'><a href='{$val[cvurl]}'>{$lang_cveditorTitle}</a></p>
									</div>
									";
						$sums=$db->get_one("select count(*) from $met_cv where lang='$lang' and readok='0'");
							$sum1=$sums['count(*)'];
							if($sum1>99){
								$sum1="99+";
							}
							$val['sum']=$sum1;
					break;
					case '7':
						$val['incurl']='message/inc.php?class1='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
						$val['conturl']='message/index.php?class1='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
						$val['set']="<div><a href='{$val[conturl]}'>{$lang_eidtmsg}</a></div>";
						$sums=$db->get_one("select count(*) from $met_message where lang='$lang' and readok='0'");
						$sum1=$sums['count(*)'];
						if($sum1>99){
							$sum1="99+";
						}
						$val['sum']=$sum1;
					break;
					case '8':
						$val['url']='feedback/inc.php?class1='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
						$val['conturl']='feedback/index.php?class1='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
						$val['set']="<div><a href='{$val[conturl]}'>{$lang_eidtfed}</a></div>";
						$sums=$db->get_one("select count(*) from $met_feedback where class1='$val[id]' and lang='$lang' and readok='0'");
						$sum1=$sums['count(*)'];
						if($sum1>99){
							$sum1="99+";
						}
						$val['sum']=$sum1;
					break;
				}
				$contentlist[] = $val;
				
			}
			
		foreach ($met_class2 as $key=>$val){
			foreach ($met_class2[$key] as $key1=>$val1){
				if(strstr($val1['name'], $id)&&$val1['module']<9 && !$val1['if_in']){
					$contentlistes1[] = $val1;
				}
			}
		}
		
		foreach($contentlistes1 as $key=>$val){
				switch($val['module']){
					case '1':
						$val['url']='about/content.php?id='.$val[id].'&lang='.$lang.'&anyid='.$anyid;	
							$val['conturl']='about/about.php?id='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
							$val['set']="<div>
									<p class='lt'><a href='{$val[url]}'>{$lang_addinfo}</a></p><span>-</span><p class='rt'><a href='{$val[conturl]}'>{$lang_manager}</a></p>
									</div>";
					break;
					case '2':	
						if(!$val[releclass]){
							$val['url']='article/content.php?class1='.$val[bigclass].'&class2='.$val[id].'&lang='.$lang.'&anyid='.$anyid;	
							$val['conturl']='article/index.php?class1='.$val[bigclass].'&class2='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
							$val['set']="<div>
									<p class='lt'><a href='{$val[url]}'>{$lang_addinfo}</a></p><span>-</span><p class='rt'><a href='{$val[conturl]}'>{$lang_manager}</a></p>
									</div>";
						}else{
							$val['url']='article/content.php?class1='.$val[id].'&lang='.$lang.'&anyid='.$anyid;	
							$val['conturl']='article/index.php?class1='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
							$val['set']="<div>
									<p class='lt'><a href='{$val[url]}'>{$lang_addinfo}</a></p><span>-</span><p class='rt'><a href='{$val[conturl]}'>{$lang_manager}</a></p>
									</div>";
						}
						
					break;
					case '3':
						if(!$val[releclass]){
							$val['url']='product/content.php?class1='.$val[bigclass].'&class2='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
							$val['conturl']='product/index.php?class1='.$val[bigclass].'&class2='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
							$val['set']="<div>
										<p class='lt'><a href='{$val[url]}'>{$lang_addinfo}</a></p><span>-</span><p class='rt'><a href='{$val[conturl]}'>{$lang_manager}</a></p>
										</div>";
						}else{
							$val['url']='product/content.php?class1='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
							$val['conturl']='product/index.php?class1='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
							$val['set']="<div>
										<p class='lt'><a href='{$val[url]}'>{$lang_addinfo}</a></p><span>-</span><p class='rt'><a href='{$val[conturl]}'>{$lang_manager}</a></p>
										</div>";
						}
					break;
					case '4':
						if(!$val[releclass]){
							$val['url']='download/content.php?class1='.$val[bigclass].'&class2='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
							$val['conturl']='download/index.php?class1='.$val[bigclass].'&class2='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
							$val['set']="<div>
										<p class='lt'><a href='{$val[url]}'>{$lang_addinfo}</a></p><span>-</span>
										<p class='rt'><a href='{$val[conturl]}'>{$lang_manager}</a></p>
										</div>";
						}else{
							$val['url']='download/content.php?class1='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
							$val['conturl']='download/index.php?class1='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
							$val['set']="<div>
										<p class='lt'><a href='{$val[url]}'>{$lang_addinfo}</a></p><span>-</span>
										<p class='rt'><a href='{$val[conturl]}'>{$lang_manager}</a></p>
										</div>";
						}
					break;
					case '5':
						if(!$val[releclass]){
							$val['url']='img/content.php?class1='.$val[bigclass].'&class2='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
							$val['conturl']='img/index.php?class1='.$val[bigclass].'&class2='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
							$val['set']="<div>
										<p class='lt'><a href='{$val[url]}'>{$lang_addinfo}</a></p><span>-</span>
										<p class='rt'><a href='{$val[conturl]}'>{$lang_manager}</a></p>
										</div>";
						}else{
							$val['url']='img/content.php?class1='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
							$val['conturl']='img/index.php?class1='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
							$val['set']="<div>
										<p class='lt'><a href='{$val[url]}'>{$lang_addinfo}</a></p><span>-</span>
										<p class='rt'><a href='{$val[conturl]}'>{$lang_manager}</a></p>
										</div>";
						}
					break;
					case '6':
						$val['url']='job/content.php?class1='.$val[id].'&action=add&lang='.$lang.'&anyid='.$anyid;
						$val['conturl']='job/index.php?class1='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
						$val['incurl']='job/inc.php?lang='.$lang.'&anyid='.$anyid;
						$val['cvurl']='job/cv.php?class1='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
						$val['set']="<div>
									<p class='lt'><a href='{$val[conturl]}'>{$lang_manager}</a></p><span>-</span>
									<p class='rt'><a href='{$val[cvurl]}'>{$lang_cveditorTitle}</a></p>
									</div>
									";
						$sum1='';
						$sums=array();
						$sums=$db->get_one("select count(*) from $met_cv where lang='$lang' and readok='0'");
						$sum1=$sums['count(*)'];
						if($sum1>99){
							$sum1="99+";
						}
						$val['sum']=$sum1;
						$contentlist[] = $val;
					break;
					case '7':
						if(!$val[releclass]){
							$val['incurl']='message/inc.php?class1='.$val[bigclass].'&class2='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
							$val['conturl']='message/index.php?class1='.$val[bigclass].'&class2='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
							$val['set']="<div><a href='{$val[conturl]}'>{$lang_eidtmsg}</a></div>";
						}else{
							$val['incurl']='message/inc.php?class1='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
							$val['conturl']='message/index.php?class1='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
							$val['set']="<div><a href='{$val[conturl]}'>{$lang_eidtmsg}</a></div>";
						}
						$sum1='';
						$sums=array();
						$sums=$db->get_one("select count(*) from $met_message where lang='$lang' and readok='0'");
						$sum1=$sums['count(*)'];
						if($sum1>99){
							$sum1="99+";
						}
						$val['sum']=$sum1;
					break;
					case '8':
						if(!$val[releclass]){
							$val['url']='feedback/inc.php?class1='.$val[bigclass].'&class2='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
							$val['conturl']='feedback/index.php?class1='.$val[bigclass].'&class2='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
							$val['set']="<div><a href='{$val[conturl]}'>{$lang_eidtfed}</a></div>";
						}else{
							$val['url']='feedback/inc.php?class1='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
							$val['conturl']='feedback/index.php?class1='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
							$val['set']="<div><a href='{$val[conturl]}'>{$lang_eidtfed}</a></div>";
						}
						$sum1='';
						$sums=array();
						$sums=$db->get_one("select count(*) from $met_feedback where class1='$val[id]' and lang='$lang' and readok='0'");
						$sum1=$sums['count(*)'];
						if($sum1>99){
							$sum1="99+";
						}
						$val['sum']=$sum1;
					break;
				}
				$contentlist[] = $val;
				}
		
		foreach ($met_class3 as $key=>$val){
			foreach ($met_class3[$key] as $key1=>$val1){
				if(strstr($val1['name'], $id)&&$val1['module']<9 && !$val1['if_in']){
					$contentlistes2[] = $val1;
				}
			}
		}
		
		foreach($contentlistes2 as $key=>$val){
				switch($val['module']){
					case '1':
						$val['url']='about/content.php?id='.$val[id].'&lang='.$lang.'&anyid='.$anyid;	
							$val['conturl']='about/about.php?id='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
							$val['set']="<div>
									<p class='lt'><a href='{$val[url]}'>{$lang_addinfo}</a></p><span>-</span><p class='rt'><a href='{$val[conturl]}'>{$lang_manager}</a></p>
									</div>";
					break;
					case '2':	
						$column_types1=array();
						$column_types2=array();
						$column_types1=$db->get_one("select * from $met_column where id='$val[bigclass]'");
						$column_types2=$db->get_one("select * from $met_column where id='$column_types1[bigclass]'");
						if($column_types2['module']!=$val['module']){
							$val['url']='article/content.php?class1='.$val[bigclass].'&class2='.$val[id].'&lang='.$lang.'&anyid='.$anyid;	
							$val['conturl']='article/index.php?class1='.$val[bigclass].'&class2='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
							$val['set']="<div>
									<p class='lt'><a href='{$val[url]}'>{$lang_addinfo}</a></p><span>-</span><p class='rt'><a href='{$val[conturl]}'>{$lang_manager}</a></p>
									</div>";
						}else{
							$val['url']='article/content.php?class1='.$column_types2[id].'&class2='.$val[bigclass].'&class3='.$val[id].'&lang='.$lang.'&anyid='.$anyid;	
							$val['conturl']='article/index.php?class1='.$column_types2[id].'&class2='.$val[bigclass].'&class3='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
							$val['set']="<div>
									<p class='lt'><a href='{$val[url]}'>{$lang_addinfo}</a></p><span>-</span><p class='rt'><a href='{$val[conturl]}'>{$lang_manager}</a></p>
									</div>";
						}
						
					break;
					case '3':
						$column_types1=array();
						$column_types2=array();
						$column_types1=$db->get_one("select * from $met_column where id='$val[bigclass]'");
						$column_types2=$db->get_one("select * from $met_column where id='$column_types1[bigclass]'");
						if($column_types2['module']!=$val['module']){
							$val['url']='product/content.php?class1='.$val[bigclass].'&class2='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
							$val['conturl']='product/index.php?class1='.$val[bigclass].'&class2='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
							$val['set']="<div>
										<p class='lt'><a href='{$val[url]}'>{$lang_addinfo}</a></p><span>-</span><p class='rt'><a href='{$val[conturl]}'>{$lang_manager}</a></p>
										</div>";
						}else{
							$val['url']='product/content.php?class1='.$column_types2[id].'&class2='.$val[bigclass].'&class3='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
							$val['conturl']='product/index.php?class1='.$column_types2[id].'&class2='.$val[bigclass].'&class3='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
							$val['set']="<div>
										<p class='lt'><a href='{$val[url]}'>{$lang_addinfo}</a></p><span>-</span><p class='rt'><a href='{$val[conturl]}'>{$lang_manager}</a></p>
										</div>";
						}
					break;
					case '4':
						$column_types1=array();
						$column_types2=array();
						$column_types1=$db->get_one("select * from $met_column where id='$val[bigclass]'");
						$column_types2=$db->get_one("select * from $met_column where id='$column_types1[bigclass]'");
						if($column_types2['module']!=$val['module']){
							$val['url']='download/content.php?class1='.$val[bigclass].'&class2='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
							$val['conturl']='download/index.php?class1='.$val[bigclass].'&class2='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
							$val['set']="<div>
										<p class='lt'><a href='{$val[url]}'>{$lang_addinfo}</a></p><span>-</span>
										<p class='rt'><a href='{$val[conturl]}'>{$lang_manager}</a></p>
										</div>";
						}else{
							$val['url']='download/content.php?class1='.$column_types2[id].'&class2='.$val[bigclass].'&class3='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
							$val['conturl']='download/index.php?class1='.$column_types2[id].'&class2='.$val[bigclass].'&class3='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
							$val['set']="<div>
										<p class='lt'><a href='{$val[url]}'>{$lang_addinfo}</a></p><span>-</span>
										<p class='rt'><a href='{$val[conturl]}'>{$lang_manager}</a></p>
										</div>";
						}
					break;
					case '5':
						$column_types1=array();
						$column_types2=array();
						$column_types1=$db->get_one("select * from $met_column where id='$val[bigclass]'");
						$column_types2=$db->get_one("select * from $met_column where id='$column_types1[bigclass]'");
						if($column_types2['module']!=$val['module']){
							$val['url']='img/content.php?class1='.$val[bigclass].'&class2='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
							$val['conturl']='img/index.php?class1='.$val[bigclass].'&class2='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
							$val['set']="<div>
										<p class='lt'><a href='{$val[url]}'>{$lang_addinfo}</a></p><span>-</span>
										<p class='rt'><a href='{$val[conturl]}'>{$lang_manager}</a></p>
										</div>";
						}else{
							$val['url']='img/content.php?class1='.$column_types2[id].'&class2='.$val[bigclass].'&class3='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
							$val['conturl']='img/index.php?class1='.$column_types2[id].'&class2='.$val[bigclass].'&class3='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
							$val['set']="<div>
										<p class='lt'><a href='{$val[url]}'>{$lang_addinfo}</a></p><span>-</span>
										<p class='rt'><a href='{$val[conturl]}'>{$lang_manager}</a></p>
										</div>";
						}
					break;
					case '6':
						$val['url']='job/content.php?class1='.$val[id].'&action=add&lang='.$lang.'&anyid='.$anyid;
						$val['conturl']='job/index.php?class1='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
						$val['incurl']='job/inc.php?lang='.$lang.'&anyid='.$anyid;
						$val['cvurl']='job/cv.php?class1='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
						$val['set']="<div>
									<p class='lt'><a href='{$val[conturl]}'>{$lang_manager}</a></p><span>-</span>
									<p class='rt'><a href='{$val[cvurl]}'>{$lang_cveditorTitle}</a></p>
									</div>
									";
						$sum1='';
						$sums=array();
						$sums=$db->get_one("select count(*) from $met_cv where lang='$lang' and readok='0'");
						$sum1=$sums['count(*)'];
						if($sum1>99){
							$sum1="99+";
						}
						$val['sum']=$sum1;
						$contentlist[] = $val;
					break;
					case '7':
						$column_types1=array();
						$column_types2=array();
						$column_types1=$db->get_one("select * from $met_column where id='$val[bigclass]'");
						$column_types2=$db->get_one("select * from $met_column where id='$column_types1[bigclass]'");
						if($column_types2['module']!=$val['module']){
							$val['incurl']='message/inc.php?class1='.$val[bigclass].'&class2='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
							$val['conturl']='message/index.php?class1='.$val[bigclass].'&class2='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
							$val['set']="<div><a href='{$val[conturl]}'>{$lang_eidtmsg}</a></div>";
						}else{
							$val['incurl']='message/inc.php?class1='.$column_types2[id].'&class2='.$val[bigclass].'&class3='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
							$val['conturl']='message/index.php?class1='.$column_types2[id].'&class2='.$val[bigclass].'&class3='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
							$val['set']="<div><a href='{$val[conturl]}'>{$lang_eidtmsg}</a></div>";
						}
						$sum1='';
						$sums=array();
						$sums=$db->get_one("select count(*) from $met_message where lang='$lang' and readok='0'");
						$sum1=$sums['count(*)'];
						if($sum1>99){
							$sum1="99+";
						}
						$val['sum']=$sum1;
					break;
					case '8':
						$column_types1=array();
						$column_types2=array();
						$column_types1=$db->get_one("select * from $met_column where id='$val[bigclass]'");
						$column_types2=$db->get_one("select * from $met_column where id='$column_types1[bigclass]'");
						if($column_types2['module']!=$val['module']){
							$val['url']='feedback/inc.php?class1='.$val[bigclass].'&class2='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
							$val['conturl']='feedback/index.php?class1='.$val[bigclass].'&class2='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
							$val['set']="<div><a href='{$val[conturl]}'>{$lang_eidtfed}</a></div>";
						}else{
							$val['url']='feedback/inc.php?class1='.$column_types2[id].'&class2='.$val[bigclass].'&class3='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
							$val['conturl']='feedback/index.php?class1='.$column_types2[id].'&class2='.$val[bigclass].'&class3='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
							$val['set']="<div><a href='{$val[conturl]}'>{$lang_eidtfed}</a></div>";
						}
						$sum1='';
						$sums=array();
						$sums=$db->get_one("select count(*) from $met_feedback where class1='$val[id]' and lang='$lang' and readok='0'");
						$sum1=$sums['count(*)'];
						if($sum1>99){
							$sum1="99+";
						}
						$val['sum']=$sum1;
					break;
				}
				$contentlist[] = $val;
				}

foreach ($contentlist as $key=>$val){
	$vimgurl = 'tubiao_'.$val[module].'.png';
	$metinfo.="<li class='contlist'>
			<div class='box'>
				<a href='{$val[conturl]}'>
					<img src='{$img_url}/metv5/{$vimgurl}?new' width='70' height='70' />";
	if($val[sum]){
		$metinfo.="<span class='cloumn_num'>{$val[sum]}</span>";
	}
	$metinfo.="<h2>{$val['name']}</h2>
				</a>
			</div>
		</li>";
}

			foreach ($met_class1 as $key=>$val){
				if($val['module']<9 && !$val['if_in']){
					$contentlistes1[] = $val;
				}
			}
			foreach($contentlistes1 as $key=>$val){
				$purview='admin_popc'.$val['id'];
				$purview=$$purview;
				$metcmspr=$metinfo_admin_pop=="metinfo" || $purview=='metinfo'?1:0;
				$metcmspr1=$val[classtype]==1 || $val[releclass]?1:0;
				$metcmspr=$metcmspr1?$metcmspr:1;
				if($metcmspr){
				$sum='';
				$sum1='';
				$sum2='';
				$sum3='';
				$sum_count=array();
				switch($val['module']){
					case '1':
						$c2 = count($met_class2[$val['id']]);
						if($val['releclass'])$c2 = count($met_class3[$val['id']]);
						$classname = $c2?"class='lt'":'';
						$classname1 = $c2&&$val['isshow']?"class='rt'":'';
						$val['url']='about/about.php?id='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
						$val['set']="<div>";
						if($val['isshow'])$val['set'].="<p {$classname}><a href='{$val[url]}'>{$lang_eidtcont}</a></p>";
						$classx = 'class1';
						if($val['releclass'] && $c2)$classx = 'class2';
						$val['conturl']=$c2?"?anyid={$anyid}&lang={$lang}&module=1&{$classx}={$val['id']}":$val[url];
						if($val['isshow'] && $c2)$val['set'].='<span>-</span>';
						if($c2)$val['set'].="<p {$classname1}><a href='{$val[conturl]}'>{$lang_subpart}</a></p>";
						$val['set'].='</div>';
						$sum_count=$db->get_all("select * from $met_column where lang='$lang' and bigclass='$val[id]'");
						foreach ($sum_count as $key=>$val5){
							if($val5[module]==6){
								$sums=array();
								$sums=$db->get_one("select count(*) from $met_cv where lang='$lang' and readok='0'");
								$sum1=$sums['count(*)'];
							}
							if($val5[module]==7){
								$sums=array();
								$sums=$db->get_one("select count(*) from $met_message where lang='$lang' and readok='0'");
								$sum2=$sums['count(*)'];
							}
							if($val5[module]==8){
								$sums=array();
								$sums=$db->get_one("select count(*) from $met_feedback where class1='$val5[id]' and lang='$lang' and readok='0'");
								$sum3=$sums['count(*)'];
							}
							$sum=$sum1+$sum2+$sum3;
							if($sum>99){
								$sum="99+";
							}
							}
							
							$val['sum']=$sum;
					break;
					case '2':
						$contentlistes1=array();
						$content_type=0;
						foreach($met_class2[$val[id]] as $key=>$val2){
							$contentlistes1[] = $val2;
						}
						foreach($contentlistes1 as $key=>$val3){
							if($val3[module]!=2){
								$content_type++;
							}
						}
						if($content_type>0){
							$c2 = count($met_class2[$val['id']]);
							if($val['releclass'])$c2 = count($met_class3[$val['id']]);
							$classname = $c2?"class='lt'":'';
							$classname1 = $c2&&$val['isshow']?"class='rt'":'';
							$val['url']='about/about.php?id='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
							$val['set']="<div>";
							if($val['isshow'])$val['set'].="<p {$classname}><a href='{$val[url]}'>{$lang_eidtcont}</a></p>";
							$classx = 'class1';
							if($val['releclass'] && $c2)$classx = 'class2';
							$val['conturl']=$c2?"?anyid={$anyid}&lang={$lang}&{$classx}={$val['id']}":$val[url];
							if($val['isshow'] && $c2)$val['set'].='<span>-</span>';
							if($c2)$val['set'].="<p {$classname1}><a href='{$val[conturl]}'>{$lang_subpart}</a></p>";
							$val['set'].='</div>';
						}else{
							$val['url']='article/content.php?class1='.$val[id].'&action=add&lang='.$lang.'&anyid='.$anyid;	
							$val['conturl']='article/index.php?class1='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
							$val['set']="<div>
									<p class='lt'><a href='{$val[url]}'>{$lang_addinfo}</a></p><span>-</span><p class='rt'><a href='{$val[conturl]}'>{$lang_manager}</a></p>
									</div>";
						}
						$sum_count=$db->get_all("select * from $met_column where lang='$lang' and bigclass='$val[id]'");
						foreach ($sum_count as $key=>$val5){
							if($val5[module]==6){
								$sums=array();
								$sums=$db->get_one("select count(*) from $met_cv where lang='$lang' and readok='0'");
								$sum1=$sums['count(*)'];
							}
							if($val5[module]==7){
								$sums=array();
								$sums=$db->get_one("select count(*) from $met_message where lang='$lang' and readok='0'");
								$sum2=$sums['count(*)'];
							}
							if($val5[module]==8){
								$sums=array();
								$sums=$db->get_one("select count(*) from $met_feedback where class1='$val5[id]' and lang='$lang' and readok='0'");
								$sum3=$sums['count(*)'];
							}
							$sum=$sum1+$sum2+$sum3;
							if($sum>99){
								$sum="99+";
							}
							}
							
							$val['sum']=$sum;
					break;
					case '3':
						$contentlistes1=array();
						$content_type=0;
						foreach($met_class2[$val[id]] as $key=>$val2){
							$contentlistes1[] = $val2;
						}
						foreach($contentlistes1 as $key=>$val3){
							if($val3[module]!=3){
								$content_type++;
							}
						}
						if($content_type>0){
							$c2 = count($met_class2[$val['id']]);
							if($val['releclass'])$c2 = count($met_class3[$val['id']]);
							$classname = $c2?"class='lt'":'';
							$classname1 = $c2&&$val['isshow']?"class='rt'":'';
							$val['url']='about/about.php?id='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
							$val['set']="<div>";
							if($val['isshow'])$val['set'].="<p {$classname}><a href='{$val[url]}'>{$lang_eidtcont}</a></p>";
							$classx = 'class1';
							if($val['releclass'] && $c2)$classx = 'class2';
							$val['conturl']=$c2?"?anyid={$anyid}&lang={$lang}&{$classx}={$val['id']}":$val[url];
							if($val['isshow'] && $c2)$val['set'].='<span>-</span>';
							if($c2)$val['set'].="<p {$classname1}><a href='{$val[conturl]}'>{$lang_subpart}</a></p>";
							$val['set'].='</div>';
						}else{
						$val['url']='product/content.php?class1='.$val[id].'&action=add&lang='.$lang.'&anyid='.$anyid;
						$val['conturl']='product/index.php?class1='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
						$val['set']="<div>
									<p class='lt'><a href='{$val[url]}'>{$lang_addinfo}</a></p><span>-</span><p class='rt'><a href='{$val[conturl]}'>{$lang_manager}</a></p>
									</div>";
						}
						$sum_count=$db->get_all("select * from $met_column where lang='$lang' and bigclass='$val[id]'");
						foreach ($sum_count as $key=>$val5){
							if($val5[module]==6){
								$sums=array();
								$sums=$db->get_one("select count(*) from $met_cv where lang='$lang' and readok='0'");
								$sum1=$sums['count(*)'];
							}
							if($val5[module]==7){
								$sums=array();
								$sums=$db->get_one("select count(*) from $met_message where lang='$lang' and readok='0'");
								$sum2=$sums['count(*)'];
							}
							if($val5[module]==8){
								$sums=array();
								$sums=$db->get_one("select count(*) from $met_feedback where class1='$val5[id]' and lang='$lang' and readok='0'");
								$sum3=$sums['count(*)'];
							}
							$sum=$sum1+$sum2+$sum3;
							if($sum>99){
								$sum="99+";
							}
							}
							
							$val['sum']=$sum;
					break;
					case '4':
						$contentlistes1=array();
						$content_type=0;
						foreach($met_class2[$val[id]] as $key=>$val2){
							$contentlistes1[] = $val2;
						}
						foreach($contentlistes1 as $key=>$val3){
							if($val3[module]!=4){
								$content_type++;
							}
						}
						if($content_type>0){
							$c2 = count($met_class2[$val['id']]);
							if($val['releclass'])$c2 = count($met_class3[$val['id']]);
							$classname = $c2?"class='lt'":'';
							$classname1 = $c2&&$val['isshow']?"class='rt'":'';
							$val['url']='about/about.php?id='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
							$val['set']="<div>";
							if($val['isshow'])$val['set'].="<p {$classname}><a href='{$val[url]}'>{$lang_eidtcont}</a></p>";
							$classx = 'class1';
							if($val['releclass'] && $c2)$classx = 'class2';
							$val['conturl']=$c2?"?anyid={$anyid}&lang={$lang}&{$classx}={$val['id']}":$val[url];
							if($val['isshow'] && $c2)$val['set'].='<span>-</span>';
							if($c2)$val['set'].="<p {$classname1}><a href='{$val[conturl]}'>{$lang_subpart}</a></p>";
							$val['set'].='</div>';
						}else{
						$val['url']='download/content.php?class1='.$val[id].'&action=add&lang='.$lang.'&anyid='.$anyid;
						$val['conturl']='download/index.php?class1='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
						$val['set']="<div>
									<p class='lt'><a href='{$val[url]}'>{$lang_addinfo}</a></p><span>-</span>
									<p class='rt'><a href='{$val[conturl]}'>{$lang_manager}</a></p>
									</div>";
						}
						$sum_count=$db->get_all("select * from $met_column where lang='$lang' and bigclass='$val[id]'");
						foreach ($sum_count as $key=>$val5){
							if($val5[module]==6){
								$sums=array();
								$sums=$db->get_one("select count(*) from $met_cv where lang='$lang' and readok='0'");
								$sum1=$sums['count(*)'];
							}
							if($val5[module]==7){
								$sums=array();
								$sums=$db->get_one("select count(*) from $met_message where lang='$lang' and readok='0'");
								$sum2=$sums['count(*)'];
							}
							if($val5[module]==8){
								$sums=array();
								$sums=$db->get_one("select count(*) from $met_feedback where class1='$val5[id]' and lang='$lang' and readok='0'");
								$sum3=$sums['count(*)'];
							}
							$sum=$sum1+$sum2+$sum3;
							if($sum>99){
								$sum="99+";
							}
							}
							
							$val['sum']=$sum;
					break;
					case '5':
						$contentlistes1=array();
						$content_type=0;
						foreach($met_class2[$val[id]] as $key=>$val2){
							$contentlistes1[] = $val2;
						}
						foreach($contentlistes1 as $key=>$val3){
							if($val3[module]!=5){
								$content_type++;
							}
						}
						if($content_type>0){
							$c2 = count($met_class2[$val['id']]);
							if($val['releclass'])$c2 = count($met_class3[$val['id']]);
							$classname = $c2?"class='lt'":'';
							$classname1 = $c2&&$val['isshow']?"class='rt'":'';
							$val['url']='about/about.php?id='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
							$val['set']="<div>";
							if($val['isshow'])$val['set'].="<p {$classname}><a href='{$val[url]}'>{$lang_eidtcont}</a></p>";
							$classx = 'class1';
							if($val['releclass'] && $c2)$classx = 'class2';
							$val['conturl']=$c2?"?anyid={$anyid}&lang={$lang}&{$classx}={$val['id']}":$val[url];
							if($val['isshow'] && $c2)$val['set'].='<span>-</span>';
							if($c2)$val['set'].="<p {$classname1}><a href='{$val[conturl]}'>{$lang_subpart}</a></p>";
							$val['set'].='</div>';
						}else{
						$val['url']='img/content.php?class1='.$val[id].'&action=add&lang='.$lang.'&anyid='.$anyid;
						$val['conturl']='img/index.php?class1='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
						$val['set']="<div>
									<p class='lt'><a href='{$val[url]}'>{$lang_addinfo}</a></p><span>-</span>
									<p class='rt'><a href='{$val[conturl]}'>{$lang_manager}</a></p>
									</div>";
						}
						$sum_count=$db->get_all("select * from $met_column where lang='$lang' and bigclass='$val[id]'");
						foreach ($sum_count as $key=>$val5){
							if($val5[module]==6){
								$sums=array();
								$sums=$db->get_one("select count(*) from $met_cv where lang='$lang' and readok='0'");
								$sum1=$sums['count(*)'];
							}
							if($val5[module]==7){
								$sums=array();
								$sums=$db->get_one("select count(*) from $met_message where lang='$lang' and readok='0'");
								$sum2=$sums['count(*)'];
							}
							if($val5[module]==8){
								$sums=array();
								$sums=$db->get_one("select count(*) from $met_feedback where class1='$val5[id]' and lang='$lang' and readok='0'");
								$sum3=$sums['count(*)'];
							}
							$sum=$sum1+$sum2+$sum3;
							if($sum>99){
								$sum="99+";
							}
							}
							
							$val['sum']=$sum;
					break;
					case '6':
						$contentlistes1=array();
						$content_type=0;
						foreach($met_class2[$val[id]] as $key=>$val2){
							$contentlistes1[] = $val2;
						}
						foreach($contentlistes1 as $key=>$val3){
							if($val3[module]!=6){
								$content_type++;
							}
						}
						if($content_type>0){
							$c2 = count($met_class2[$val['id']]);
							if($val['releclass'])$c2 = count($met_class3[$val['id']]);
							$classname = $c2?"class='lt'":'';
							$classname1 = $c2&&$val['isshow']?"class='rt'":'';
							$val['url']='about/about.php?id='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
							$val['set']="<div>";
							if($val['isshow'])$val['set'].="<p {$classname}><a href='{$val[url]}'>{$lang_eidtcont}</a></p>";
							$classx = 'class1';
							if($val['releclass'] && $c2)$classx = 'class2';
							$val['conturl']=$c2?"?anyid={$anyid}&lang={$lang}&{$classx}={$val['id']}":$val[url];
							if($val['isshow'] && $c2)$val['set'].='<span>-</span>';
							if($c2)$val['set'].="<p {$classname1}><a href='{$val[conturl]}'>{$lang_subpart}</a></p>";
							$val['set'].='</div>';
						}else{
						$val['url']='job/content.php?class1='.$val[id].'&action=add&lang='.$lang.'&anyid='.$anyid;
						$val['conturl']='job/index.php?class1='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
						$val['incurl']='job/inc.php?lang='.$lang.'&anyid='.$anyid;
						$val['cvurl']='job/cv.php?class1='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
						$val['set']="<div>
									<p class='lt'><a href='{$val[conturl]}'>{$lang_manager}</a></p><span>-</span>
									<p class='rt'><a href='{$val[cvurl]}'>{$lang_cveditorTitle}</a></p>
									</div>
									";
						}
						$sums=$db->get_one("select count(*) from $met_cv where lang='$lang' and readok='0'");
						$sum1=$sums['count(*)'];
						if($sum1>99){
							$sum1="99+";
						}
						$val['sum']=$sum1;
					break;
					case '7':
					$contentlistes1=array();
						$content_type=0;
						foreach($met_class2[$val[id]] as $key=>$val2){
							$contentlistes1[] = $val2;
						}
						foreach($contentlistes1 as $key=>$val3){
							if($val3[module]!=7){
								$content_type++;
							}
						}
						if($content_type>0){
							$c2 = count($met_class2[$val['id']]);
							if($val['releclass'])$c2 = count($met_class3[$val['id']]);
							$classname = $c2?"class='lt'":'';
							$classname1 = $c2&&$val['isshow']?"class='rt'":'';
							$val['url']='about/about.php?id='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
							$val['set']="<div>";
							if($val['isshow'])$val['set'].="<p {$classname}><a href='{$val[url]}'>{$lang_eidtcont}</a></p>";
							$classx = 'class1';
							if($val['releclass'] && $c2)$classx = 'class2';
							$val['conturl']=$c2?"?anyid={$anyid}&lang={$lang}&{$classx}={$val['id']}":$val[url];
							if($val['isshow'] && $c2)$val['set'].='<span>-</span>';
							if($c2)$val['set'].="<p {$classname1}><a href='{$val[conturl]}'>{$lang_subpart}</a></p>";
							$val['set'].='</div>';
						}else{
						$val['incurl']='message/inc.php?class1='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
						$val['conturl']='message/index.php?class1='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
						$val['set']="<div><a href='{$val[conturl]}'>{$lang_eidtmsg}</a></div>";
						}
						$sums=$db->get_one("select count(*) from $met_message where lang='$lang' and readok='0'");
						$sum1=$sums['count(*)'];
						if($sum1>99){
							$sum1="99+";
						}
						$val['sum']=$sum1;
					break;
					case '8':
						$contentlistes1=array();
						$content_type=0;
						foreach($met_class2[$val[id]] as $key=>$val2){
							$contentlistes1[] = $val2;
						}
						foreach($contentlistes1 as $key=>$val3){
							if($val3[module]!=8){
								$content_type++;
							}
						}
						if($content_type>0){
							$c2 = count($met_class2[$val['id']]);
							if($val['releclass'])$c2 = count($met_class3[$val['id']]);
							$classname = $c2?"class='lt'":'';
							$classname1 = $c2&&$val['isshow']?"class='rt'":'';
							$val['url']='about/about.php?id='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
							$val['set']="<div>";
							if($val['isshow'])$val['set'].="<p {$classname}><a href='{$val[url]}'>{$lang_eidtcont}</a></p>";
							$classx = 'class1';
							if($val['releclass'] && $c2)$classx = 'class2';
							$val['conturl']=$c2?"?anyid={$anyid}&lang={$lang}&{$classx}={$val['id']}":$val[url];
							if($val['isshow'] && $c2)$val['set'].='<span>-</span>';
							if($c2)$val['set'].="<p {$classname1}><a href='{$val[conturl]}'>{$lang_subpart}</a></p>";
							$val['set'].='</div>';
						}else{
						$val['url']='feedback/inc.php?class1='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
						$val['conturl']='feedback/index.php?class1='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
						$val['set']="<div><a href='{$val[conturl]}'>{$lang_eidtfed}</a></div>";
						}
						$sums=$db->get_one("select count(*) from $met_feedback where class1='$val[id]' and lang='$lang' and readok='0'");
						$sum1=$sums['count(*)'];
						if($sum1>99){
							$sum1="99+";
						}
						$val['sum']=$sum1;
					break;
				}
				$contentlist1[] = $val;
				}
			}
$metinfo1="";
foreach ($contentlist1 as $key=>$val){
	$vimgurl = 'tubiao_'.$val[module].'.png';
	$metinfo1.="<li class='contlist'>
			<div class='box'>
				<a href='{$val[conturl]}'>
					<img src='{$img_url}/metv5/{$vimgurl}?new' width='70' height='70' />";
	if($val[sum]){
		$metinfo1.="<span class='cloumn_num'>{$val[sum]}</span>";
	}
		$metinfo1.="<h2>{$val['name']}</h2>
				</a>
			</div>
		</li>";
}
if($id!=null){
	echo "<script language='JavaScript'> 
    document.getElementById('loading').style.display='none'; 
    </script> ";
	if($metinfo){
		echo $metinfo;
	}else{
		echo "<div class='proccess1' ><img src='../../upload/image/Noresults.png' width='150' height='150' /><br>{$lang_search_Noresults}</div>";
	}
}else{
		echo "<script language='JavaScript'> 
    document.getElementById('loading').style.display='none'; 
    </script> ";
		echo $metinfo1;
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>