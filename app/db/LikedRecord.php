<?php
namespace app\db;

use app\model\LikedBy;

abstract class LikedRecord extends ActiveRecord {

  public function init(){
    parent::init();
    // Register Delete Listener to Remove
    // Likes related to this object
    $this->on(
      ActiveRecord::EVENT_AFTER_DELETE, 
      [$this, "afterDelete"]
    );
  }

  public function afterDelete(){
    $likes = $this->getLikes();
    foreach ( $likes as $like ){
      $like->delete();
    }
  }

  public function likeFilter($record = null){
    if ( $record instanceof LikedBy ){
      if ( $record->object_model == $this->tableName() && $record->object_id == $this->id ){
        return true;
      }
    }
    return false;
  }

  public function getLikes(){
    $like = new LikedBy();
    return $like->all([$this, 'likeFilter']);
  }
}

