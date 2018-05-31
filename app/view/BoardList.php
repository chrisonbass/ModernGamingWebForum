<?php

namespace app\view;

use app\base\View;
use app\util\ArrayHelper;

class BoardList extends View {
  protected function getFile(){
    return "board-list"; 
  }

  protected function parseData($data){
    $d = array(
      "list" => array()
    );
    return ArrayHelper::merge($d, $data);
  }
}
