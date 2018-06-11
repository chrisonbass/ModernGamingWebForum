<?php
namespace app\controller\rest;

use app\App;
use app\model\Comment as CommentModel;
use app\model\User;
use app\model\Role;
use app\base\RestController;

class Comment extends RestController {
  public function modelClass(){
    return CommentModel::className();
  }

  public function getRequiredRole(){
    switch ( strtolower($this->action . "") ){
      case "like":
        return Role::userRole(); 
        break;
    }
    return parent::getRequiredRole();
  }

  public function actionLike(){
    $model = $this->model;
    $user = User::getLoggedInUser();
    if ( $model && $model->id && $user && $user->id ){
      if ( $model->addLike($user) ){
        return [
          "like_count" => count($model->getLikes())
        ];
      }
    }
    return [];
  }
}
