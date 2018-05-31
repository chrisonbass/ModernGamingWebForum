<?php
namespace app\controller;

use app\model\Role;

class Comment extends Crud {
  public function modelClass(){
    return "app\\model\\Comment";
  }

  public function getRelatedFields(){
    return array(
      "topic_id" => array(
        "class_name" => "app\model\Topic",
      )
    );
  }

  public function getRequiredRole(){
    switch ( $this->action ){
      case "Index":
      case "View":
      case "Edit":
      case "Delete":
      case "Create":
        return Role::adminRole();
    }
    return parent::getRequiredRole();
  }
}
