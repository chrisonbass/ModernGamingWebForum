<?php

namespace app\view;

use app\util\ArrayHelper;
use app\base\View;
use app\model\Topic;

class TopicDelete extends View {
  protected function getFile(){
    return "topic-delete"; 
  }

  protected function parseData($c){
    $d = array(
      "model" => null
    );
    $data = ArrayHelper::merge($d, $c);
    if ( isset($data['model']) && $model = $data['model'] ){
      if ( $model instanceof Topic && $model->id ){
        $data['board'] = $model->getBoard();
      } else {
        $data['model'] = null;
      }
    } else {
      $data['model'] = null;
    }
    return $data;
  }
}
