<?php

namespace app\view;

use app\base\View;

class MetaTag extends View {
  protected function getFile(){
    return "meta-tag"; 
  }

  protected function parseData($data){
    $d = array(
      "name" => "",
      "value" => ""
    );
    foreach ( $d as $k => $f ){
      if ( $data && isset($data[$k]) ){
        $d[$k] = $data[$k];
      }
    }
    return $d;
  }
}
