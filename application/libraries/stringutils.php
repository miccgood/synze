<?php

class StringUtils {
    
    public static function isBlankOrNull($str){
        $null = is_null($str) ;
        $string = is_string($str) ;
        $blank =  $str === "" ;
        return ($null || ($string && $blank));
    }
    
    public static function isStringNotBlankOrNull($str){
        return (is_string($str) && $str !== "" && !is_null($str));
    }
    
    public static function subStringAndGetByIndex($str, $delimeter, $index){
         $mediaType = explode($delimeter, $str);
         return $mediaType[$index];
    }
    
}

