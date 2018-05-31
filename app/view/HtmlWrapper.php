<?php

namespace app\view;

use app\util\ArrayHelper;
use app\base\View;

class HtmlWrapper extends View {
  protected function getFile(){
    return "html-wrapper";
  }

  protected function parseData($a = array()){
    $b = array(
      "title" => "",
      "head" => "",
      "body" => "",
      "message" => "",
      "alert" => "",
      "user" => null
    );
    $html = ArrayHelper::merge($b, $a);

    return $html;
  }
}
