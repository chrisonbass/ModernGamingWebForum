<?php

namespace app\model;

use app\base\Model;

class Register extends Model {
  public $username;
  public $password;
  public $password2;

  public function filterCheckIfUsernameExists($user){
    if ( $user->username && strlen($user->username) ){
      if ( strtolower(trim($user->username)) == strtolower(trim($this->username)) ){
        return true;
      }
    }
    return false;
  }

  public function validate(){
    // Validate UserName  
    $user = new User();
    $search = $user->all([$this, 'filterCheckIfUsernameExists']);
    if ( $search && count($search) ){
      $this->addError("username", "This username already exists.");
    } else if (strlen($this->username) < 3 ) {
      $this->addError("username", "Username must have at least 3 characters");
    }

    // Validate Passwords  
    if ( $this->password && $this->password2 ){
      if ( $this->password == $this->password2 ){
        if ( 
          !preg_match("/[A-Z]/", $this->password) ||
          !preg_match("/[a-z]/", $this->password) ||
          !preg_match("/[0-9]/", $this->password) ||
          strlen($this->password) < 8
        ){
          $this->addError(
            "password", 
            "Password must be at least 8 characters ".
            "and contain at least 1 uppercase letter, a".
            " lowercase letter, and a number."
          ); 
        }
      }
      else {
        $this->addError("password", "Passwords must match");
      }
    }
    else {
      $this->addError("password", "Passwords must match");
    }
  }
}
?>
