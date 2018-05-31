<?php
namespace app\controller;

use app\App;
use app\base\Controller;
use app\model\User;
use app\model\Login as LoginModel;
use app\view\Home;
use app\view\Alert;
use app\view\Login as LoginView;

class Login extends Controller {
  
  public function getTitle(){
    switch ( $this->action ){
      case "Index":
        return "Log into your account";

      default:
        return parent::getTitle();
    }
  }


  public function actionIndex(){
    $app = App::app();
    $model = new LoginModel();
    // Handle Submited form
    if ( $app->request->isPost() ){
      $post = $app->request->post();
      if ( isset($post['submit']) && $post['submit'] == "cancel" ){
        header("location: index.php");
        exit;
      }
      if ( isset($post['username']) ){
        $model->username = $post['username'];
      }
      if ( isset($post['password']) ){
        $model->password = $post['password'];
      }
      $model->validate();
      if ( !$model->hasErrors() ){
        $model->login();
        $app->setMessage("You are now logged into your account.", "success");
        header("location: index.php");
        exit;
      } else {
        $app->setMessage("There is an error with your login information.", "danger");
      }
    }
    $body = new LoginView(array("model" => $model));
    $alert = $app->getMessage(); 
    if ( $alert ){
      $alert = new Alert($alert);
      $body = $alert . $body;
    }
    return new Home(array(
      "title" => "Log into your account",
    ), $body);
  }

  public function actionLogout(){
    $user = User::getLoggedInUser();
    $app = App::app();
    if ( $user && $user->id ){
      (new LoginModel())->logout();
      $app->setMessage("You have been logged out.", "success");
      header("location: index.php");
      exit;
    }
  }
}
