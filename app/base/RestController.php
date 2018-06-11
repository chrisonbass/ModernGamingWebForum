<?php
namespace app\base;

use app\App;
use app\db\ActiveRecord;
use app\util\UrlManager;

abstract class RestController extends Controller {
  public $model;

  abstract public function modelClass();

  public function action(){
    $app = App::app();
    $modelClass = static::modelClass();
    $id = UrlManager::getId();
    if ( is_null($id) ){
      $this->model = new $modelClass();
    } else {
      $model = new $modelClass($id);
      if ( $model && $model->id ){
        $this->model = $model;
      } else {
        $this->model = new $modelClass();
      }
    }
    header('Content-Type: application/json');
    if ( !$this->canPerform() ){
      http_response_code(403);
      echo json_encode([
        "status" => "fail",
        "message" => "You don't have permission to view this page."
      ]);
      exit;
    }
    if ( !strlen($this->action) ){
      http_response_code(403);
      echo json_encode([
        "status" => "fail",
        "message" => "Bad Request"
      ]);
      exit;
    }
    $actionMethod = "action" . $this->action;
    $body = [];

    if ( method_exists($this, $actionMethod) ){
      $body = $this->$actionMethod();
    }

    echo json_encode([
      "status" => "success",
      "result" => $body
    ]);
    exit;  
  }

  public function actionIndex(){
    $className = static::modelClass();
    $model = $this->model;
    $data = [];
    if ( is_null($model->id) ){
      $model = new $className();
      if ( $model instanceof ActiveRecord ){
        foreach ( $model->all() as $record ){
          $data[] = $record->toArray();
        }
      }
      return $data;
    } else {
      return [$model->toArray()];
    }
    return [];
  }
}
