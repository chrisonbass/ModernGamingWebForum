<?php

namespace app\view;

use app\base\View;
use app\util\ArrayHelper;

class Login extends View {
  protected function getFile(){
    return "login"; 
  }

  protected function parseData($data){
    $d = array(
      "model" => null
    );
    return ArrayHelper::merge($d, $data);
  }
}
