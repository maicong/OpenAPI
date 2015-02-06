<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
require_once '../login/login_check.php';
$css_url="../templates/".$met_skin."/css";
$img_url="../templates/".$met_skin."/images";
if($topara){
	$toparas=explode('|',$topara);
	Header("Location: ../column/parameter/parameter.php?module={$topara[0]}&anyid=29&lang={$lang}&class1={$toparas[1]}");
	met_setcookie("topara",'',time()-3600);
}
if($met_content_type == 0){
	$query = "select content_type from {$met_admin_table} where admin_id='{$metinfo_admin_name}'";
	$met_content_type1 = $db->get_one($query);
	$met_content_type = $met_content_type1['content_type'];
}
$query = "update $met_admin_table set content_type='$met_content_type' where admin_id='{$metinfo_admin_name}'";
$db->query($query);
if($met_content_type!=2){
	if($action=='search'&&$program){	
		foreach ($met_class1 as $key=>$val){
			if($val['module']<9 && !$val['if_in']){
				$contentlistes[] = $val;
			}
		}
		foreach($contentlistes as $key=>$val){
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
					break;
					case '7':
						$val['incurl']='message/inc.php?class1='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
						$val['conturl']='message/index.php?class1='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
						$val['set']="<div><a href='{$val[conturl]}'>{$lang_eidtmsg}</a></div>";
					break;
					case '8':
						$val['url']='feedback/inc.php?class1='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
						$val['conturl']='feedback/index.php?class1='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
						$val['set']="<div><a href='{$val[conturl]}'>{$lang_eidtfed}</a></div>";
					break;
				}
				$contentlist[] = $val;
				
			}
			
		foreach ($met_class2 as $key=>$val){
			foreach ($met_class2[$key] as $key1=>$val1){
				if($val['module']<9 && !$val['if_in']){
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
					break;
				}
				$contentlist[] = $val;
				}
		
		foreach ($met_class3 as $key=>$val){
			foreach ($met_class3[$key] as $key1=>$val1){
				if($val['module']<9 && !$val['if_in']){
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
						$column_types2=array();
						foreach($met_class2 as $key1=>$val1){
							foreach($val1 as $key11=>$val11){								
								if($val11[id]==$val[bigclass]){
									$column_types2=$met_class1[$key1];
								}
							}							
						}
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
						$column_types2=array();
						foreach($met_class2 as $key1=>$val1){
							foreach($val1 as $key11=>$val11){								
								if($val11[id]==$val[bigclass]){
									$column_types2=$met_class1[$key1];
								}
							}							
						}
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
						$column_types2=array();
						foreach($met_class2 as $key1=>$val1){
							foreach($val1 as $key11=>$val11){								
								if($val11[id]==$val[bigclass]){
									$column_types2=$met_class1[$key1];
								}
							}							
						}						
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
						$column_types2=array();
						foreach($met_class2 as $key1=>$val1){
							foreach($val1 as $key11=>$val11){								
								if($val11[id]==$val[bigclass]){
									$column_types2=$met_class1[$key1];
								}
							}							
						}
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
					break;
					case '7':
						$column_types2=array();
						foreach($met_class2 as $key1=>$val1){
							foreach($val1 as $key11=>$val11){								
								if($val11[id]==$val[bigclass]){
									$column_types2=$met_class1[$key1];
								}
							}							
						}						
						if($column_types2['module']!=$val['module']){
							$val['incurl']='message/inc.php?class1='.$val[bigclass].'&class2='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
							$val['conturl']='message/index.php?class1='.$val[bigclass].'&class2='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
							$val['set']="<div><a href='{$val[conturl]}'>{$lang_eidtmsg}</a></div>";
						}else{
							$val['incurl']='message/inc.php?class1='.$column_types2[id].'&class2='.$val[bigclass].'&class3='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
							$val['conturl']='message/index.php?class1='.$column_types2[id].'&class2='.$val[bigclass].'&class3='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
							$val['set']="<div><a href='{$val[conturl]}'>{$lang_eidtmsg}</a></div>";
						}
					break;
					case '8':
						$column_types1=array();
						$column_types2=array();
						foreach($met_class2 as $key1=>$val1){
							foreach($val1 as $key11=>$val11){								
								if($val11[id]==$val[bigclass]){
									$column_types2=$met_class1[$key1];
								}
							}							
						}						
						if($column_types2['module']!=$val['module']){
							$val['url']='feedback/inc.php?class1='.$val[bigclass].'&class2='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
							$val['conturl']='feedback/index.php?class1='.$val[bigclass].'&class2='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
							$val['set']="<div><a href='{$val[conturl]}'>{$lang_eidtfed}</a></div>";
						}else{
							$val['url']='feedback/inc.php?class1='.$column_types2[id].'&class2='.$val[bigclass].'&class3='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
							$val['conturl']='feedback/index.php?class1='.$column_types2[id].'&class2='.$val[bigclass].'&class3='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
							$val['set']="<div><a href='{$val[conturl]}'>{$lang_eidtfed}</a></div>";
						}
					break;
				}
				$contentlist[] = $val;
				}
		
	}else{
		if($class1){
			
			if($met_class[$class1]['isshow'])$contentlistes[]=$met_class[$class1];
			
			foreach($met_class2[$class1] as $key=>$val2){
				$contentlistes[] = $val2;
			}
			foreach($contentlistes as $key=>$val){
				if($val['module']==1){
				$c2 = count($met_class3[$val['id']]);
				$classname = $c2?"class='lt'":'';
				$classname1 = $c2&&$val['isshow']?"class='rt'":'';
				$val['url']='about/about.php?id='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
				$val['conturl']=$c2?"?anyid={$anyid}&lang={$lang}&module=1&class2={$val['id']}":$val[url];
				$val['set']="<div>";
				if($val['isshow'])$val['set'].="<p {$classname}><a href='{$val[url]}'>{$lang_eidtcont}</a></p>";
				if($val['isshow'] && $c2)$val['set'].='<span>-</span>';
				if($c2)$val['set'].="<p {$classname1}><a href='?anyid={$anyid}&lang={$lang}&module=1&class2={$val['id']}'>{$lang_subpart}</a></p>";
				$val['set'].='</div>';
				$contentlist[] = $val;
				}
				$column_types5=array();
				$column_types5=$db->get_one("select * from $met_column where id='$val[bigclass]'");
				if(($val['module']==2&&$val['bigclass']=='0')||($val['module']==2&&$column_types5[module]!=2&&$val['bigclass']!='0')){
				$val['url']='article/content.php?class1='.$val[id].'&action=add&lang='.$lang.'&anyid='.$anyid;	
				$val['conturl']='article/index.php?class1='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
				$val['set']="<div>
						<p class='lt'><a href='{$val[url]}'>{$lang_addinfo}</a></p><span>-</span><p class='rt'><a href='{$val[conturl]}'>{$lang_manager}</a></p>
						</div>";
				$contentlist[] = $val;
				}
				$column_types5=array();
				$column_types5=$db->get_one("select * from $met_column where id='$val[bigclass]'");
				if(($val['module']==3&&$val['bigclass']=='0')||($val['module']==3&&$column_types5[module]!=3&&$val['bigclass']!='0')){
				$val['url']='product/content.php?class1='.$val[id].'&action=add&lang='.$lang.'&anyid='.$anyid;
				$val['conturl']='product/index.php?class1='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
				$val['set']="<div>
							<p class='lt'><a href='{$val[url]}'>{$lang_addinfo}</a></p><span>-</span><p class='rt'><a href='{$val[conturl]}'>{$lang_manager}</a></p>
							</div>";
				$contentlist[] = $val;
				}
				$column_types5=array();
				$column_types5=$db->get_one("select * from $met_column where id='$val[bigclass]'");
				if(($val['module']==4&&$val['bigclass']=='0')||($val['module']==4&&$column_types5[module]!=4&&$val['bigclass']!='0')){
				$val['url']='download/content.php?class1='.$val[id].'&action=add&lang='.$lang.'&anyid='.$anyid;
				$val['conturl']='download/index.php?class1='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
				$val['set']="<div>
							<p class='lt'><a href='{$val[url]}'>{$lang_addinfo}</a></p><span>-</span>
							<p class='rt'><a href='{$val[conturl]}'>{$lang_manager}</a></p>
									</div>";
				$contentlist[] = $val;
				}
				$column_types5=array();
				$column_types5=$db->get_one("select * from $met_column where id='$val[bigclass]'");
				if(($val['module']==5&&$val['bigclass']=='0')||($val['module']==5&&$val['module']==$class1&&$val['bigclass']=='0')||($val['module']==5&&$column_types5[module]!=5&&$val['bigclass']!='0')||($val['module']==5&&$val['bigclass']!='0'&&$column_types5[foldername]!=$val[foldername])){
				$val['url']='img/content.php?class1='.$val[id].'&action=add&lang='.$lang.'&anyid='.$anyid;
				$val['conturl']='img/index.php?class1='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
				$val['set']="<div>
							<p class='lt'><a href='{$val[url]}'>{$lang_addinfo}</a></p><span>-</span>
							<p class='rt'><a href='{$val[conturl]}'>{$lang_manager}</a></p>
							</div>";
				$contentlist[] = $val;
				}
				if($val['module']==6){
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
				}
				if($val['module']==7){
				$val['incurl']='message/inc.php?class1='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
				$val['conturl']='message/index.php?class1='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
				$val['set']="<div><a href='{$val[conturl]}'>{$lang_eidtmsg}</a></div>";
				$sum1='';
				$sums=array();
				$sums=$db->get_one("select count(*) from $met_message where lang='$lang' and readok='0'");
				$sum1=$sums['count(*)'];
				if($sum1>99){
					$sum1="99+";
				}
				$val['sum']=$sum1;
				$contentlist[] = $val;
				}
				if($val['module']==8){
				$val['url']='feedback/inc.php?class1='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
				$val['conturl']='feedback/index.php?class1='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
				$val['set']="<div><a href='{$val[conturl]}'>{$lang_eidtfed}</a></div>";
				$sum1='';
				$sums=array();
				$sums=$db->get_one("select count(*) from $met_feedback where class1='$val[id]' and lang='$lang' and readok='0'");
				$sum1=$sums['count(*)'];
				if($sum1>99){
					$sum1="99+";
				}
				$val['sum']=$sum1;
				$contentlist[] = $val;
				}
			}
		}elseif($class2){
			$class1=$met_class[$class2]['bigclass'];
			if($met_class[$class2]['isshow'])$contentlistes[]=$met_class[$class2];
			foreach($met_class3[$class2] as $key=>$val2){
				if(!$val2['releclass']&&!$val2['if_in'])$contentlistes[] = $val2;
			}
			foreach($contentlistes as $key=>$val){
				$val['conturl']='about/about.php?id='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
				$val['set']="<div><p><a href='{$val[conturl]}'>{$lang_eidtcont}</a></p></div>";
				$contentlist[] = $val;
			}
		}else{
			foreach ($met_class1 as $key=>$val){
				if($val['module']<9 && !$val['if_in']){
					$contentlistes[] = $val;
				}
			}
			foreach($contentlistes as $key=>$val){
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
							if($val3[module]!=2&&$val3[module]!=0&&$val3[module]<100){
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
						foreach ($sum_count1 as $key=>$val5){
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
							if($val3[module]!=3&&$val3[module]!=0&&$val3[module]<100){
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
							if($val3[module]!=4&&$val3[module]!=0&&$val3[module]<100){
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
							$column_types5=array();
							$column_types5=$db->get_one("select * from $met_column where id='$val3[bigclass]'");
							if(($val3[module]!=5||$val3[foldername]!=$column_types5[foldername])&&$val3[module]!=0&&$val3[module]<100){
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
							if($val3[module]!=6&&$val3[module]!=0&&$val3[module]<100){
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
							if($val3[module]!=7&&$val3[module]!=0&&$val3[module]<100){
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
							if($val3[module]!=8&&$val3[module]!=0&&$val3[module]<100){
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
				$contentlist[] = $val;
				}
			}
		}
	}
}else{
	if($module){
		if($class1){
			if($met_class1[$class1]['isshow']){
				$met_class1[$class1]['conturl']='about/about.php?id='.$met_class1[$class1][id].'&lang='.$lang.'&anyid='.$anyid;
				$contentlist[0] = $met_class1[$class1];
			}
			foreach($met_class2[$class1] as $key=>$val){
				if($val['module']==$module){
					$val['conturl']='about/about.php?id='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
					if(count($met_class3[$val['id']]))$val['conturl']="?anyid={$anyid}&lang={$lang}&module=1&class2={$val['id']}";
					$contentlist[] = $val;
				}
			}
		}elseif($class2){
			if($met_class[$class2]['isshow']){
				$met_class[$class2]['conturl']='about/about.php?id='.$met_class[$class2][id].'&lang='.$lang.'&anyid='.$anyid;
				$contentlist[0] = $met_class[$class2];
			}
			foreach($met_class3[$class2] as $key=>$val){
				if($val['module']==$module){
					$val['conturl']='about/about.php?id='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
					$contentlist[] = $val;
				}
			}
		}else{
			switch($module){
				case 1:
					foreach($met_class1 as $key=>$val){
						if($val['module']==1){
							$val['conturl']='about/about.php?id='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
							if(count($met_class2[$val['id']]))$val['conturl']="?anyid={$anyid}&lang={$lang}&module=1&class1={$val['id']}";
							$contentlist[] = $val;
						}
					}
				break;
			}
		}
	}else{
		foreach($met_class1 as $key=>$val){
			if($val['module']==1){
				$md1[]=$val;
			}
		}
		if(count($met_classindex[1])!=0){
			$contentlist[1]['name']=$lang_modulemanagement1;
			$contentlist[1]['module']='1';
			$contentlist[1]['conturl']="about/index.php?module=1&lang=$lang&anyid=$anyid";
		}
		if(count($met_classindex[2])!=0){
			$contentlist[2]['name']=$lang_modulemanagement2;
			$contentlist[2]['module']='2';
			$contentlist[2]['conturl']="article/index.php?module=2&lang=$lang&anyid=$anyid";
			$contentlist[2]['url']="article/content.php?action=add&lang=$lang&anyid=$anyid";	
			$contentlist[2]['set']="<div>
				<p class='lt'><a href='{$contentlist[2][url]}'>{$lang_addinfo}</a></p><span>-</span><p class='rt'><a href='{$contentlist[2][conturl]}'>{$lang_manager}</a></p>
				</div>";
		}
		if(count($met_classindex[3])!=0){
			$contentlist[3]['name']=$lang_modulemanagement3;
			$contentlist[3]['module']='3';
			$contentlist[3]['conturl']="product/index.php?module=3&lang=$lang&anyid=$anyid";
			$contentlist[3]['url']="product/content.php?action=add&lang=$lang&anyid=$anyid";	
			$contentlist[3]['set']="<div>
				<p class='lt'><a href='{$contentlist[3][url]}'>{$lang_addinfo}</a></p><span>-</span><p class='rt'><a href='{$contentlist[3][conturl]}'>{$lang_manager}</a></p>
				</div>";
		}
		if(count($met_classindex[4])!=0){
			$contentlist[4]['name']=$lang_modulemanagement4;
			$contentlist[4]['module']='4';
			$contentlist[4]['conturl']="download/index.php?module=4&lang=$lang&anyid=$anyid";
			$contentlist[4]['url']="download/content.php?action=add&lang=$lang&anyid=$anyid";	
			$contentlist[4]['set']="<div>
				<p class='lt'><a href='{$contentlist[4][url]}'>{$lang_addinfo}</a></p><span>-</span><p class='rt'><a href='{$contentlist[4][conturl]}'>{$lang_manager}</a></p>
				</div>";
		}
		if(count($met_classindex[5])!=0){
			$contentlist[5]['name']=$lang_modulemanagement5;
			$contentlist[5]['module']='5';
			$contentlist[5]['conturl']="img/index.php?module=5&lang=$lang&anyid=$anyid";
			$contentlist[5]['url']="img/content.php?action=add&lang=$lang&anyid=$anyid";	
			$contentlist[5]['set']="<div>
				<p class='lt'><a href='{$contentlist[5][url]}'>{$lang_addinfo}</a></p><span>-</span><p class='rt'><a href='{$contentlist[5][conturl]}'>{$lang_manager}</a></p>
				</div>";
		}
		if(count($met_classindex[6])!=0){
			$sum=$db->get_one("select count(*) from $met_cv where lang='$lang' and readok='0'");
			if($sum['count(*)']>99){
				$sum['count(*)']="99+";
			}
			$contentlist[6]['sum']=$sum['count(*)'];
			$contentlist[6]['name']=$lang_modulemanagement6;
			$contentlist[6]['module']='6';
			$contentlist[6]['conturl']="job/index.php?class1={$met_classindex[6][0][id]}&lang={$lang}&anyid={$anyid}";
			$contentlist[6]['cvurl']="job/cv.php?class1={$met_classindex[6][0][id]}&lang={$lang}&anyid={$anyid}";
			$contentlist[6]['set']="<div>
				<p class='lt'><a href='{$contentlist[6]['conturl']}'>{$lang_manager}</a></p><span>-</span>
				<p class='rt'><a href='{$contentlist[6]['cvurl']}'>{$lang_cveditorTitle}</a></p>
				</div>
				";
		}	
		if(count($met_classindex[7])!=0){
			$sum=$db->get_one("select count(*) from $met_message where lang='$lang' and readok='0'");
			if($sum['count(*)']>99){
				$sum['count(*)']="99+";
			}
			$contentlist[7]['sum']=$sum['count(*)'];
			$contentlist[7]['name']=$lang_modulemanagement7;
			$contentlist[7]['module']='7';
			$contentlist[7]['conturl']="message/index.php?class1={$met_classindex[7][0][id]}&lang={$lang}&anyid={$anyid}";
		}
		if(count($met_classindex[8])!=0){
			$sum=$db->get_one("select count(*) from $met_feedback where lang='$lang' and readok='0'");
			if($sum['count(*)']>99){
				$sum['count(*)']="99+";
			}
			$contentlist[8]['sum']=$sum['count(*)'];
			$contentlist[8]['name']=$lang_modulemanagement8;
			$contentlist[8]['module']='8';
			$contentlist[8]['conturl']="feedback/index.php?class1={$met_classindex[8][0][id]}&lang={$lang}&anyid={$anyid}";
		}
	}
}
include template('content/content');
footer();
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>