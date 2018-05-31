<?php

namespace app\base;

abstract class View extends BaseObject {
  private $data = null;
  private $file = null;
  protected $children = null;

  abstract protected function getFile();
  abstract protected function parseData($data);

  public function __construct($_data = array(), $children = array()){
    $__data = null;;
    if ( is_array($_data) ){
      $__data =  $_data;
    } else {
      $__data = array();
    }
    $this->data = $this->parseData($__data);
    $this->children = $children;
    $this->file = $this->getFile();
  } 

  public function render(){
    $temp_file = __DIR__ . "/../template/" . $this->file . ".php";
    if ( file_exists($temp_file) ){
      // Get buffer of currently rendered items
      //$buffer = ob_get_clean();
      ob_start();
      extract($this->data);
      $children = "";
      if ( $this->children ){
        if ( is_array($this->children) ){
          $children = implode(" ", $this->children);
        } else {
          $children = $this->children;
        }
      }
      include($temp_file);
      // return buffer with new output appended
      return "" . ob_get_clean();
    }
    return "<div>" . $temp_file  . " -- TEMPLATE NOT FOUND" . "</div>";
  }

  /**
   * Magic method to return our render
   * method whenever an instance is used
   * as a string
   * @return String the rendered view
   */
  public function __toString(){
    try {
      $str = $this->render();
      return $str;
    } catch ( Exception $e ){
      ob_start();
      echo "<h1>ERROR</h1>";
      echo "<pre>";
      print_r($e);
      echo "</pre>";
      return ob_get_clean();
    }
  }
}

?>
