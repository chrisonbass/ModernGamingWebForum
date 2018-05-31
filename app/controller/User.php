<?php
namespace app\controller;

use app\model\Role;

class User extends Crud {
  public function modelClass(){
    return "app\\model\\User";
  }

  public function getRequiredRole(){
    $admin = Role::adminRole();
    $role = Role::userRole();
    switch ( $this->action ){
      case "Edit":
      case "Delete":
      case "Create":
        return $admin;
      case "View":
        return $role;
    }
    return parent::getRequiredRole();
  }
}
