<?php

namespace app\view;

use app\base\View;

class Table extends View {
  protected function getFile(){
    return "table"; 
  }

  protected function parseData($c){
    $d = array(
      "columns" => array(),
      "rows" => array(),
      "footer" => null
    );
    foreach ( $d as $k => $v ){
      if ( isset($c[$k]) ){
        $d[$k] = $c[$k];
      }
    }
    return $d;
  }
}
