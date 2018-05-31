<?php

namespace app\base;

class Event extends BaseObject {
  public $name;
  public $sender;
  public $data;
  public $handled;

  public function __construct($name, $data = array(), $sender = null){
    parent::__construct();
    $this->name = $name;
    $this->data = $data;
    $this->sender = $sender;
  }

  public function init(){
    parent::init();
    $this->handled = false;
  }

  public static function parseEventString($event){
    if ( $event instanceof Event ){
      return $event->name;
    } else if ( is_string($event) ){
      return $event;
    }
    return null;
  }
}
