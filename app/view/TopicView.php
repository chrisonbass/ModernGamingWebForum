<?php

namespace app\view;

use app\base\View;
use app\util\ArrayHelper;
use app\model\Topic as TopicModel;

class TopicView extends View {
  protected function getFile(){
    return "topic-view"; 
  }

  protected function parseData($data){
    $d = array(
      "model" => new TopicModel(),
      "user" => null,
      "can_delete_topic" => false,
      "creator" => null,
    );
    return ArrayHelper::merge($d, $data);
  }
}
