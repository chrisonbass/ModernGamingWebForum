<?php

namespace app\http;

use app\base\BaseObject;

class Request extends BaseObject {
  public function isGet(){
    return $_SERVER['REQUEST_METHOD'] === 'GET';
  }

  public function isPost(){
    return $_SERVER['REQUEST_METHOD'] === 'POST';
  }

  public function post(){
    return $_POST;
  }

  public function get(){
    return $_GET;
  }
}
