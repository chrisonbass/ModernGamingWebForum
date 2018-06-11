<?php
namespace app\base;

/**
 * BaseObject is the base class for
 * all objects within the main application.
 * It exposes simple methods for examining
 * an object.
 *
 * @package app\base
 * @author Mark Moss
 * @version 1.0
 * @access public
 */
class BaseObject {

  public function __construct(){
    $this->init();
  }

  /**
   * Method called when object is
   * constructed
   *
   * @return  null
   */
  public function init(){
  }

  /**
   * returns the fully qualified class
   * name of the object
   *
   * @return  string  the fully qualified
   *                  class name 
   */
  public static function className(){
    return get_called_class();
  }

  /**
   * returns true if method exists in
   * an object and false if not
   *
   * @param   string  The name of the method
   * @return  bool
   */
  public function hasMethod($method){
    return method_exists($this, $method);
  }

  /**
   * returns true if property exists in
   * an object and false if not
   *
   * @param   string  The name of the property
   * @return  bool
   */
  public function hasProperty($property){
    return property_exists($this, $property);
  }

  /**
   * returns the named properties of an object
   *
   * @return  array   an associative array of defined 
   *                  object accessible non-static properties
   *                  for the specified object in scope. 
   */
  public function getProperties(){
    return get_object_vars($this);
  }

  public function toArray(){
    return $this->getProperties();
  }
}
?>
