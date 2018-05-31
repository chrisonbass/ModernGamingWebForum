<?php
namespace app\model;

use app\db\ActiveRecord;

class LikedBy extends ActiveRecord {
  public $user_id;
  public $object_model;
  public $object_id;

  public function tableName(){
    return "liked-by";
  }
}
