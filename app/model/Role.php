<?php
namespace app\model;

use app\db\ActiveRecord;

class Role extends ActiveRecord {
  public $type;
  private static $__adminRole = null;
  private static $__userRole = null;

  public function tableName(){
    return "role";
  }

  public function getLabel(){
    if ( $this->id && $this->type ){
      return $this->type . " : " . $this->id;
    }
    return parent::getLabel();
  }

  public static function adminRole(){
    $role =  static::$__adminRole;
    if ( $role && $role->id ){
      return $role;
    }
    $role = (new Role())->all(function($p_role){
      if ( $p_role->type == "admin_user" ){
       return true; 
      }
      return false;
    });
    if ( count($role) ){
      $role = reset($role);
    }
    if ( $role && $role->id ){
      static::$__adminRole = $role;
      return static::$__adminRole;
    }
    return null;
  }

  public static function userRole(){
    $role =  static::$__userRole;
    if ( $role && $role->id ){
      return $role;
    }
    $role = (new Role())->all(function($p_role){
      if ( $p_role->type == "registered_user" ){
       return true; 
      }
      return false;
    });
    if ( count($role) ){
      $role = reset($role);
    }
    if ( $role && $role->id ){
      static::$__userRole = $role;
      return static::$__userRole;
    }
    return null;
  }
}
