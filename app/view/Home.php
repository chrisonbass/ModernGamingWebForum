<?php

namespace app\view;

use app\base\View;
use app\util\ArrayHelper;

class Home extends View {
  protected function getFile(){
    return "home"; 
  }

  protected function parseData($data){
    $d = array(
      "title" => "Default Title",
      "links" => array()
    );
    return ArrayHelper::merge($d, $data);
  }
}
