<?php
namespace app\base;

use app\App;
use app\util\Text;
use app\view\HtmlWrapper;
use app\view\Alert;
use app\model\User;
use app\model\Role;

abstract class Controller extends BaseObject {
  public $action = "index";

  public function __construct(){
    parent::__construct();
  } 

  public function getTitle(){
    return "Default Title";
  }

  public function action(){
    $app = App::app();
    if ( !$this->canPerform() ){
      $app->setMessage("You don't have permission to view this page.", "danger");  
      header("location: index.php");
      exit;
    }
    if ( !strlen($this->action) ){
      throw new \Exception("Invalid Action Name");
    }
    $actionMethod = "action" . $this->action;
    $headerAction = "header" . $this->action;
    $body = "";
    $header = "";
    $title = $this->getTitle();

    if ( method_exists($this, $actionMethod) ){
      $body = $this->$actionMethod();
    }
    if ( method_exists($this, $headerAction) ){
      $header = $this->$headerAction();
    }

    $user = User::getLoggedInUser();

    $alert = $app->getMessage();
    if ( $alert ){
      $alert = new Alert($alert);
    } else {
      $alert = "";
    }

    $view = new HtmlWrapper(array(
      "head" => $header,
      "body" => $body,
      "user" => $user,
      "alert" => $alert,
      "title" => $this->getTitle()
    ) );
    echo $view;
  }

  public function getRequiredRole(){
    return null;
  }

  public function canPerform(){
    $user = User::getLoggedInUser();
    $role = $this->getRequiredRole();
    if ( $role && $role->id ){
      if ( $user && $user->id ){
        return $user->hasRole($role);
      }
      return false;
    }
    return true;
  }
}

?>
