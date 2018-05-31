<?php

namespace app\view;

use app\base\View;
use app\util\ArrayHelper;

class Register extends View {
  protected function getFile(){
    return "register"; 
  }

  protected function parseData($data){
    $d = array(
      "model" => null
    );
    return ArrayHelper::merge($d, $data);
  }
}
