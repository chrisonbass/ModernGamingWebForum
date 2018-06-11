<?php
namespace app\model;

use app\db\ActiveRecord;

class User extends ActiveRecord {
  public $username;
  public $password;

  public function tableName(){
    return "user";
  }

  public function getLabel(){
    if ( $this->id && $this->username ){
      return $this->username;
    }
    return parent::getLabel();
  }

  public function filterUserRoles($userRole){
    if ( $this->id == $userRole->user_id ){
      return true;
    }
  }

  public function hasRole(Role $role){
    $roles = (new UserRole())->all([$this,'filterUserRoles']);
    foreach ( $roles as $uRole ){
      if ( $uRole->role_id == $role->id ){
        return true;
      }
    }
    return false;
  }

  public static function getLoggedInUser(){
    if ( isset($_SESSION['user']) ){
      $user = unserialize($_SESSION['user']);
      if ( $user && $user->id ){
        return $user;
      }
    }
    return null;
  }

  public function filterUserComments($comment){
    if ( $this->id == $comment->created_by ){
      return true;
    }
  }

  public function getComments(){
    return (new Comment())->all([$this,"filterUserComments"]);
  }

  public function filterUserTopics($topic){
    if ( $this->id == $topic->created_by ){
      return true;
    }
  }

  public function getTopics(){
    return (new Topic())->all([$this,"filterUserTopics"]);
  }

}
