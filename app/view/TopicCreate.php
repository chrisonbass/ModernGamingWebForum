<?php

namespace app\view;

use app\base\View;
use app\model\Topic;
use app\util\ArrayHelper;

class TopicCreate extends View {
  protected function getFile(){
    return "topic-create"; 
  }

  protected function parseData($data){
    $d = array(
      "model" => new Topic()
    );
    return ArrayHelper::merge($d, $data);
  }
}
