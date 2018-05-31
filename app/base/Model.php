<?php

namespace app\base;

class Model extends BaseObject { 
  protected $__events = array();
  protected $__errors = array();

  public function init(){
    parent::init();
  }

  public function on($event, $callable){
    if ( is_callable($callable) ){
      $event_str = Event::parseEventString($event);
      if ( !is_null($event_str) ){
        if ( !isset($this->__events[$event_str]) ){
          $this->__events[$event_str] = array();
        }
        if ( !in_array($callable, $this->__events[$event_str]) ){
          $this->__events[$event_str][] = $callable;
        }
        return;
      }
      throw new \Exception("Inavalid event provided");
    }
    throw new \Exception("Inavalid callback provided");
  }

  public function off($event, $callable = null){
    $e_str = Event::parseEventString($event);
    if ( isset($this->__events[$e_str]) ){
      if ( is_null($callable) ){
        $this->__events[$e_str] = array();
      } else {
        foreach ( $this->__events[$e_str] as $index => $handler ){
          if ( $handler === $callable ){
            array_splice($this->__events[$e_str], $index, 1);
            break;
          }
        }
      }
    }
  }

  public function getProperties(){
    $props = parent::getProperties();
    unset($props['__events']);
    unset($props['__errors']);
    return $props;
  }

  public function trigger(Event $event){
    if ( !$event->sender ){
      $event->sender = $this;
    }
    if ( isset($this->__events[$event->name]) ){
      foreach ( $this->__events[$event->name] as $callable ){
        if ( is_callable($callable) ){
          call_user_func($callable, $event);
        }
      }
    }
  }

  public function addError($property, $error_message){
    if ( !isset($this->__errors[$property]) ){
      $this->__errors[$property] = [];
    }
    $this->__errors[$property][] = $error_message;
  }

  public function hasErrors(){
    return $this->__errors && count($this->__errors);
  }

  public function getErrors(){
    return $this->__errors;
  }

  public function validate(){
  }
}
