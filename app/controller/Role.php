<?php
namespace app\controller;

use app\model\User;
use app\model\Role as RoleModel;

class Role extends Crud {
  public function modelClass(){
    return "app\\model\\Role";
  }

  public function getRequiredRole(){
    return RoleModel::adminRole();
  }
}
