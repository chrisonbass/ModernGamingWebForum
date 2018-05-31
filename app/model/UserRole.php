<?php
namespace app\model;

use app\db\ActiveRecord;

class UserRole extends ActiveRecord {
  public $user_id;
  public $role_id;

  public function tableName(){
    return "user-role";
  }
}
