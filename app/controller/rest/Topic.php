<?php
namespace app\controller\rest;

use app\App;
use app\model\Topic as TopicModel;
use app\model\User;
use app\model\Role;
use app\base\RestController;

class Topic extends RestController {
  public function modelClass(){
    return TopicModel::className();
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
