<?php
namespace app\model;

use app\db\LikedRecord;
use app\db\ActiveRecord;
use app\model\User;
use app\model\Topic;

class Comment extends LikedRecord {
  public $topic_id;
  public $created_by;
  public $body;
  public $created_at;

  public function tableName(){
    return "comment";
  }

  public function init(){
    $this->on(ActiveRecord::EVENT_BEFORE_SAVE, [$this,'beforeSave']);
  }

  public function getUser(){
    if ( $this->created_by ){
      return new User($this->created_by);
    }
    return null;
  }

  public function getTopic(){
    if ( $this->topic_id ){
      return new Topic($this->topic_id);
    }
    return null;
  }
  public function beforeSave($event){
    if ( !$this->created_at ){
      $this->created_at = date("Y-m-d H:i:s");
    }
  }
}
