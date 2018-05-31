<?php

namespace app\util;

use app\base\BaseObject;

class Text extends BaseObject {
  public static function hyphenToSnakeCase($text){
    $text = " " . $text;
    $d = strtolower(trim($text));
    $d = strtoupper(substr($d, 0, 1)) . substr($d, 1);
    $d = preg_replace_callback("/\-(\w)/", function ($match){
      return strtoupper($match[1]);
    }, $d);
    return $d;
  }

  public static function classNameToName($text){
    $text = preg_split("/\\\\/", $text);
    $text = end($text);
    $text = preg_replace("/([a-z])([A-Z])/", "$1 $2", $text);
    return $text;
  }

  public static function classToModelName($text){
    $name = static::classNameToName($text);
    return strtolower(preg_replace("/\s/", "-", $name) );
  }

  public static function modelFieldToLabel($text){
    $text = preg_split("/_/", $text);
    $str = "";
    foreach ( $text as $txt ){
      $str .= " " . strtoupper(substr($txt,0,1)) . substr($txt, 1);
    }
    return trim($str);
  }
}
