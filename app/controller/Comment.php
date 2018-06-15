<?php
namespace app\controller;

use app\model\Role;
use app\view\CommentDelete;
use app\model\User;

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

  public function getDeleteForwardLocation(){
    if ( $this->model && $this->model->topic_id ){
      return "index.php?controller=topic&action=view&id=" . $this->model->topic_id;
    }
    return "index.php?controller=board";
  }

  public function getDeleteView(){
    return CommentDelete::className();
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

  public function canPerform(){
    $user = User::getLoggedInUser();
    $model = $this->model;
    switch( $this->action ){
      case "Delete":
        if ( $model->id && $model->created_by && $user && $user->id ){
          return $user->id == $model->created_by;
        }
        return false;
        break;
    }
    return parent::canPerform();
  }
}
