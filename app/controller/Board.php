<?php
namespace app\controller;

use app\model\Board as BoardModel;
use app\view\BoardList;
use app\view\BoardPage;
use app\model\User;
use app\model\Role;
use app\App;

class Board extends Crud {
  public function modelClass(){
    return "app\\model\\Board";
  }

  public function actionIndex(){
    return new BoardList(array(
      "list" => (new BoardModel())->all()
    ) );
  }

  public function getTitle(){
    if ( $this->action == "View" ){
      return "";
    }
    return parent::getTitle();
  }
  
  public function actionView(){
    $app = App::app();
    $data = $app->request->get();
    $model = null;
    if ( isset($data['id']) ){
      $id = $data['id'];
      $model = new BoardModel($id);
    }
    if ( $model && $model->id ){
      $user = User::getLoggedInUser();
      $role = Role::userRole();
      $can_topic = false;
      if ( $user && $user->id ){
        if ( $user->hasRole($role) ){
          $can_topic = true;
        }
      }
      return new BoardPage(array(
        "board" => $model,
        "can_create_topic" => $can_topic
      ) );
    }
    $app->setMessage("Invalid Board", "danger");
    header("location: index.php?controller=board");
    exit;
  }

  public function getRequiredRole(){
    $user = User::getLoggedInUser();
    $role = Role::adminRole();
    switch ( $this->action ){
      case "Edit":
      case "Delete":
      case "Create":
        return $role;
    }
    return parent::getRequiredRole();
  }
}
