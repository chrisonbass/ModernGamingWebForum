<?php

namespace app\view;

use app\base\View;
use app\util\ArrayHelper;

class SummaryPage extends View {
  protected function getFile(){
    return "summary-page"; 
  }

  protected function parseData($data){
    $d = array(
      "properties" => array()
    );
    return ArrayHelper::merge($d, $data);
  }
}
