<?php
function unicode_encode($strLong) {
   $strArr = unicode_split($strLong); 
   $resUnicode = '';
   foreach ($strArr as $str)
   {
      $str = '&#' . base_convert(bin2hex(iconv("utf-8", "ucs-4", $str)), 16, 10) . ';';  
      $resUnicode .= $str;
   }
   return $resUnicode;
 }

 function unicode_split($str) {
   $strLong = '';
   if($strLong !== $str){
      $strLong =  preg_split('/(?<!^)(?!$)/u', $str);
   }  
   return $strLong;
 }