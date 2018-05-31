<?php

namespace app\view;

use app\util\ArrayHelper;
use app\base\View;

class Alert extends View {
  protected function getFile(){
    return "alert"; 
  }

  protected function parseData($c){
    $d = array(
      "type" => "",
      "body" => "",
    );
    return ArrayHelper::merge($d, $c);
  }
}
