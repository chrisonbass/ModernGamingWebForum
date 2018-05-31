<?php
namespace app\util;

use app\base\BaseObject;

class ArrayHelper extends BaseObject {
  public static function merge(){
    $set = array();
    $args = func_get_args();
    if ( !$args || count($args) < 2 ){
      throw new \Exception("merge requires a minimum of 2 parameters");
    }

    foreach ( $args as $arg ){
      if ( is_array($arg) ){
        foreach ( $arg as $k => $v ){
          if ( isset($set[$k]) && is_array($set[$k]) && is_array($v) ){
            $set[$k] = static::merge($set[$k], $v);
            continue;
          }
          $set[$k] = $v;
        }
      }
    }
    return $set;
  }
}
?>
