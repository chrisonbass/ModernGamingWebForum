<?php

namespace app\view;

use app\base\View;
use app\util\ArrayHelper;

class Landing extends View {
  protected function getFile(){
    return "landing"; 
  }

  protected function parseData($data){
    $d = array();
    return ArrayHelper::merge($d, $data);
  }
}
