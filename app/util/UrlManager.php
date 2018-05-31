<?php
namespace app\util;

use app\App;
use app\base\BaseObject;
use app\controller\Landing;

class UrlManager extends BaseObject {
  public static function getController(){
    $controller = null;
    if ( isset($_GET['controller']) && strlen($_GET['controller']) ){
      $controller = Text::hyphenToSnakeCase($_GET['controller']);
    } 
    if ( !is_null($controller) && class_exists("app\\controller\\" . $controller) ){
      return "app\\controller\\" . $controller;
    }
    return null;
  } 

  public static function getAction(){
    $action = null;
    if ( isset($_GET['action']) && strlen($_GET['action']) ){
      $action = $_GET['action'];
    }
    if ( $action ){
      $action = Text::hyphenToSnakeCase($action);
    }
    return $action;
  }

  public static function getId(){
    if ( isset($_GET['id']) && strlen($_GET['id']) ){
      return $_GET['id'];
    } else if ( isset($_POST['id']) && strlen($_POST['id']) ){
      return $_POST['id'];
    }
  }
}
?>
