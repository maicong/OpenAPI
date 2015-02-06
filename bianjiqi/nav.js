jQuery(document).ready(function($){
$body=(window.opera)?(document.compatMode=="CSS1Compat"?$('html'):$('body')):$('html,body');//修复Opera滑动异常地，加过就不需要重复加了。
$('#yi').click(function(){//点击事件
		$body.animate({scrollTop:0},100);//400毫秒滑动到顶部
});
$('#er').click(function(){
		$body.animate({scrollTop:$('#alertdiv1').offset().top},100);//直接取得页面高度，不再是手动指定页尾ID
});
 
$('#san').click(function(){
	$body.animate({scrollTop:$('#alertdiv2').offset().top},100);//滑动到id=comments元素，遇到不规范的主题需调整
});
}); 