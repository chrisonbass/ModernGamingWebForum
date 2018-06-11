<?php

namespace app;

// Utility Classes
use app\http\Request;
use app\io\Csv;
use app\util\ArrayHelper;
use app\util\UrlManager;
use app\controller\Landing;

class App {
  private static $app = null;
  public $request;
  public $controller;
  public $action;
  public $config;

  public function __construct($_config = array()){
    $this->request = new Request();
    $this->action = UrlManager::getAction();
    $this->controller = UrlManager::getController();
    $this->id = UrlManager::getId();

    if ( !$this->action ){
      $this->action = "Index";
    }

    if ( !$this->controller ){
      $this->controller = Landing::className();
    }

    $this->config = $_config;
    if ( !is_array($this->config) ){
      $this->config = array();
    }
  }

  public function getController(){
    $controller = $this->controller;
    if ( $controller && class_exists($controller) ){
      $controller = new $controller();
      return $controller;
    }
    throw new \Exception("Invalid Request: {$controller} does not exist.");
  }

  public function run(){
    $controller = $this->getController();
    $controller->action = $this->action;
    $controller->action();
  }

  public static function app(){
    if ( is_null(static::$app) ){
      static::$app = new App();
    }
    return static::$app;
  }
  public function dbName(){
    if ( isset($this->config['db-name']) ){
      return $this->config['db-name'];
    }
    return "db";
  }

  public function setMessage($message, $type="info"){
    $message = array(
      "body" => $message,
      "type" => $type
    );
    switch ( $message['type'] ){
      case "primary":
      case "secondary":
      case "success":
      case "danger":
      case "warning":
      case "info":
      case "light":
      case "dark":
        $message['type'] = "alert-" . $message['type'];
        break;
      default:
        $message['type'] = "alert-info";
        break;
    }
    $_SESSION['message'] = $message;
  }

  public function getMessage(){
    $message = array(
      "body" => "",
      "type" => "alert-info"
    );
    if ( isset($_SESSION['message']) ){
      $msg = ArrayHelper::merge($message, $_SESSION['message']);
      unset($_SESSION['message']);
      return $msg;
    }
    return false;
  }
}
