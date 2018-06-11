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
      "can_like_topic" => false,
      "creator" => null,
      "like_count" => 0
    );
    return ArrayHelper::merge($d, $data);
  }
}
