<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
error_reporting(E_ERROR | E_PARSE);
$met_webkeys=trim(file_get_contents(substr(dirname(__FILE__), 0, -6).'/config/config_safe.php'));
$met_webkeys=str_replace('<?php/*','',$met_webkeys);
$met_webkeys=str_replace('*/?>','',$met_webkeys);
class Captcha
{
 var  $mCheckCodeNum  = 4;

  var $mCheckCode   = '';
 
 var $mCheckImage  = '';

 var$mDisturbColor  = '';

  var $mCheckImageWidth = '80';

  var $mCheckImageHeight  = '20';
  
  //font
  var $FNT="../../font/verdana.ttf";

 function OutFileHeader()
 {
  header ("Content-type: image/png");
 }


function CreateCheckCode()
 {
  global $met_webkeys;
  $this->mCheckCode = str_replace(array('0','O'),'A',strtoupper(substr(md5(rand()),0,$this->mCheckCodeNum)));
  $auth=$this->authcode($this->mCheckCode,'ENCODE', md5($met_webkeys));
  setcookie("met_capcha",$auth,time()+3600,'/');
  return $this->mCheckCode;
 }


 function CreateImage()
 {
  $this->mCheckImage = @imagecreate ($this->mCheckImageWidth,$this->mCheckImageHeight);
  imagecolorallocate($this->mCheckImage, 200, 200, 200);
  return $this->mCheckImage;
 }

 /**
 *
 */
 function SetDisturbColor()
 {
  for ($i=0;$i<=128;$i++)
  {
   $this->mDisturbColor = imagecolorallocate ($this->mCheckImage, rand(0,255), rand(0,255), rand(0,255));
   imagesetpixel($this->mCheckImage,rand(2,128),rand(2,38),$this->mDisturbColor);
  }
 }

 /**
 *
 * @set image size
 */
function SetCheckImageWH($width,$height)
 {
  if($width==''||$height=='')return false;
  $this->mCheckImageWidth  = $width;
  $this->mCheckImageHeight = $height;
  return true;
 }

 /**
 * @write code to Image
 */
function WriteCheckCodeToImage()
 {
  for ($i=0;$i<=$this->mCheckCodeNum;$i++)
  {
   $bg_color = imagecolorallocate ($this->mCheckImage, rand(0,255), rand(0,128), rand(0,255));
   $x = floor($this->mCheckImageWidth/$this->mCheckCodeNum)*$i;
   $y = rand(0,$this->mCheckImageHeight-15);
   imagechar ($this->mCheckImage, 5, $x, $y, $this->mCheckCode[$i], $bg_color);
  }
 }

 /**
 *
 * @Out Image
 *
 */
 function OutCheckImage()
 {
  $this ->OutFileHeader();
  $this ->CreateCheckCode();
  $this ->CreateImage();
  $this ->SetDisturbColor();
  $this ->WriteCheckCodeToImage();
  imagepng($this->mCheckImage);
  imagedestroy($this->mCheckImage);
 }
 /**
 *
 * @chech Code
 *
 */
 function CheckCode($code)
 {
  global $met_webkeys;
  if(!empty($code)&&$this->authcode($_COOKIE['met_capcha'],'DECODE', md5($met_webkeys))===$code){
	return true; 
  }else{
	return false;
  }
 }
 function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {

        $ckey_length = 4; 

        $key = md5($key ? $key : UC_KEY);
        $keya = md5(substr($key, 0, 16));
        $keyb = md5(substr($key, 16, 16));
        $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';

        $cryptkey = $keya.md5($keya.$keyc);
        $key_length = strlen($cryptkey);

        $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
        $string_length = strlen($string);

        $result = '';
        $box = range(0, 255);

        $rndkey = array();
        for($i = 0; $i <= 255; $i++) {
            $rndkey[$i] = ord($cryptkey[$i % $key_length]);
        }

        for($j = $i = 0; $i < 256; $i++) {
            $j = ($j + $box[$i] + $rndkey[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }

        for($a = $j = $i = 0; $i < $string_length; $i++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }

        if($operation == 'DECODE') {
            if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
                return substr($result, 26);
            } else {
                return '';
            }
        } else {
            return $keyc.str_replace('=', '', base64_encode($result));
        }

    } 
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>