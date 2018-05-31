<?php
namespace app\controller;

use app\model\Role;

class UserRole extends Crud {
  public function modelClass(){
    return "app\\model\\UserRole";
  }

  public function getRequiredRole(){
    return Role::adminRole();
  } 

  public function getRelatedFields(){
    return array(
      "user_id" => array(
        "class_name" => "app\model\User",
      ),
      "role_id" => array(
        "class_name" => "app\model\Role",
      ),
    );
  }

}
