<?php

namespace app\view;

use app\util\ArrayHelper;
use app\base\View;
use app\model\Comment;

class CommentDelete extends View {
  protected function getFile(){
    return "comment-delete"; 
  }

  protected function parseData($c){
    $d = array(
      "model" => null
    );
    $data = ArrayHelper::merge($d, $c);
    if ( isset($data['model']) && $model = $data['model'] ){
      if ( $model instanceof Comment && $model->id ){
        $data['topic'] = $model->getTopic();
      } else {
        $data['model'] = null;
      }
    } else {
      $data['model'] = null;
    }
    return $data;
  }
}
