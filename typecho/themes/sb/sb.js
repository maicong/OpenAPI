jQuery(document).ready(function(){
	var music=document.getElementById("music");
$('#fuck').click(function(){
  	$("#you_are_sb").hide();
	$("#fuck").hide();
  	$("#sb_say").slideDown('slow');
});
$('#yuxiaoxi_shi_sb').click(function(){
  	$("#sb_say").hide();
  	$("#you_are_sb").slideDown('slow');
	$("#fuck").show();
});
$('#on').click(function(){
  	music.pause();
	$('#on').hide(200);
  	$('#off').css({"display":"inline-block"},300);
});
$('#off').click(function(){
  	music.play();
  	$('#off').hide(200);
  	$('#on').css({"display":"inline-block"},300);
});
   	setInterval(function() {
		str=lrc.split("[");
		var shijianshuzu=new Array();
		var gecishuzu=new Array();
		for(var i=0;i<str.length;i++){
		var shijian=str[i].split(']')[0];
		var geci=str[i].split(']')[1];
		var fen=shijian.split(":")[0];
		var miao=shijian.split(":")[1];
		var sec=parseInt(fen)*60+parseInt(miao);
		shijianshuzu[i]=sec;
		gecishuzu[i]=geci;
		}
	function getcurrent()
			{
				for(i=0;i<shijianshuzu.length;i++)
				{
					if(shijianshuzu[i]>=music.currentTime)
					{
						return i-1;
					}
				}
				return i-1;
			}
		var i=getcurrent();
		$('#bgmlrc').html(gecishuzu[i]);
	},0);
});
var duoshuoQuery = {short_name:"yuxi"};
  (function() {
	  var ds = document.createElement('script');
	  ds.type = 'text/javascript';ds.async = true;
	  ds.src = 'http://static.duoshuo.com/embed.js';
	  ds.charset = 'UTF-8';
	  (document.getElementsByTagName('head')[0] 
	  || document.getElementsByTagName('body')[0]).appendChild(ds);
})();