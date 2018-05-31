<?php

namespace app\view;

use app\util\ArrayHelper;
use app\base\View;

class Link extends View {
  protected function getFile(){
    return "link"; 
  }

  protected function parseData($c){
    $d = array(
      "label" => "",
      "url" => "",
      "id" => "",
      "target" => "",
      "class" => "",
    );
    $link = ArrayHelper::merge($d, $c);
    if ( $link['label'] == "" ){
      $link['label'] = $link['url'];
    }
    return $link;
  }
}
