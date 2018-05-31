<?php
namespace app\controller;

use app\App;
use app\base\Controller;
use app\model\User;
use app\model\UserRole;
use app\model\Role;
use app\model\Login;
use app\model\Register as RegisterModel;
use app\view\Home;
use app\view\Alert;
use app\view\Register as RegisterView;

class Register extends Controller {
  
  public function getTitle(){
    switch ( $this->action ){
      case "Index":
        return "Register new Account";

      default:
        return parent::getTitle();
    }
  }


  public function actionIndex(){
    $app = App::app();
    $model = new RegisterModel();
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
      if ( isset($post['password2']) ){
        $model->password2 = $post['password2'];
      }
      $model->validate();
      if ( !$model->hasErrors() ){
        $user = new User();
        $user->username = $model->username;
        $user->password = md5(Login::ENCRYPT_KEY . $model->password);
        if ( $user->save() ){
          $role = Role::userRole();
          if ( $role && $role->id ){
            $userRole = new UserRole();
            $userRole->user_id = $user->id;
            $userRole->role_id = $role->id;
            $userRole->save();
          }
          $app->setMessage("Your account has been created.  You can now login.", "success");
          header("location: index.php");
          exit;
        }
      } else {
        $app->setMessage("Your form contains errors.", "danger");
      }
    }
    return new Home(array(
      "title" => "Register Account",
    ), new RegisterView(array("model" => $model)));
  }
}
