<?php

namespace app\view;

use app\base\View;
use app\util\ArrayHelper;

class BoardPage extends View {
  protected function getFile(){
    return "board-page"; 
  }

  protected function parseData($data){
    $d = array(
      "board" => null,
      "can_create_topic" => false,
    );
    return ArrayHelper::merge($d, $data);
  }
}
