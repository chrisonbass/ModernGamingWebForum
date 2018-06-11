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
    } else if ( isset($_POST['controller']) && strlen($_POST['controller']) ){
      $controller = Text::hyphenToSnakeCase($_POST['controller']);
    }
    // Check if a rest controller
    if ( !is_null($controller) && preg_match("/^Rest/", $controller) ){
      $controller = preg_replace("/^Rest/","", $controller);
      $class_name = "app\\controller\\rest\\" . $controller;
      if ( class_exists($class_name) ){
        return $class_name;
      }
    }
    // Normal Controller
    $class_name = "app\\controller\\" . $controller;
    if ( !is_null($controller) && class_exists($class_name) ){
      return $class_name;
    }
    return null;
  } 

  public static function getAction(){
    $action = null;
    if ( isset($_GET['action']) && strlen($_GET['action']) ){
      $action = $_GET['action'];
    } else if ( isset($_POST['action']) && strlen($_POST['action']) ){
      $action = $_POST['action'];
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
    return null;
  }
}
?>
