<?php

namespace app\model;

use app\base\Model;

class Login extends Model {
  public $username;
  public $password;

  const ENCRYPT_KEY = "c8fREhQZpF3auwzB";

  public function filterFindUserByUsername($user){
    if ( $user->username == $this->username ){
      return true;
    }
    return false;
  }

  public function validate(){
    $user = new User();
    $search = $user->all([$this,'filterFindUserByUsername']);
    $found_user = null;
    if ( $search && count($search) == 1 ){
      $found_user = $search[0];
    }
    if ( $found_user && $found_user->id ){
      if ( md5(Login::ENCRYPT_KEY . $this->password) != $found_user->password ){
        $this->addError("password", "Invalid password");
      } 
    } else {
      $this->addError("username", "Invalid username");
    }
  }

  public function login(){
    $this->validate();
    if ( !$this->hasErrors() ){
      $search = (new User())->all([$this,'filterFindUserByUsername']);
      if ( $search && $user = reset($search) ){
        $_SESSION['user'] = serialize($user);
        return true;
      }
    }
    return false;
  }

  public function logout(){
    unset($_SESSION['user']);
  }
}
?>
