<?php

namespace app\view;

use app\util\ArrayHelper;
use app\base\View;

class Csv extends View {
  protected function getFile(){
    return "csv"; 
  }

  protected function parseData($c){
    $d = array(
      "table" => ""
    );
    return ArrayHelper::merge($d, $c);
  }
}
