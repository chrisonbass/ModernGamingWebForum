<?php

namespace app\view;

use app\util\ArrayHelper;
use app\base\View;

class Input extends View {
  protected function getFile(){
    return "input"; 
  }

  protected function parseData($c){
    $d = array(
      "name" => "",
      "id" => "",
      "value" => "",
      "type" => "text",
      "bs_class" => "",
      "button_type" => "",
      "options" => array()
    );
    $input = ArrayHelper::merge($d, $c);
    switch ( $input['type'] ){
      case "select":
      case "button":
      case "hidden":
      case "text":
      case "number":
      case "email":
        break;
      default:
        $input['type'] = "text";
        break;
    }
    if ( isset($input['bs_class']) && strlen($input['bs_class']) ){
      $type = $input['bs_class'];
      $type = preg_replace("/^btn\-/", "", $input['bs_class']);
      switch ( $type ){
        case "info":
        case "success":
        case "warning":
        case "primary":
        case "danger":
          $input['bs_class'] = "btn-" . $type;
          break;
        default:
          $input['bs_class'] = "";
          break;
      }
    }
    return $input;
  }
}
