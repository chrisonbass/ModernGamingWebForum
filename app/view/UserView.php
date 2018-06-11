<?php

namespace app\view;

use app\util\ArrayHelper;
use app\base\View;
use app\model\User;

class UserView extends View {
  protected function getFile(){
    return "user-view"; 
  }

  protected function parseData($c){
    $d = array(
      "model" => new User(),
      "latest_topic" => null,
      "latest_comment" => null
    );
    return ArrayHelper::merge($d, $c);
  }
}
