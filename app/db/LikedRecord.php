<?php
namespace app\db;

use app\model\LikedBy;
use app\model\User;
use app\util\Text;
use app\model\Role;

abstract class LikedRecord extends ActiveRecord {

  public function init(){
    parent::init();
    // Register Delete Listener to Remove
    // Likes related to this object
    $this->on(
      ActiveRecord::EVENT_AFTER_DELETE, 
      [$this, "handleAfterLikableObjectDelete"]
    );
  }

  public function handleAfterLikableObjectDelete(){
    $likes = $this->getLikes();
    foreach ( $likes as $like ){
      $like->delete();
    }
  }

  public function likeFilter($record = null){
    if ( $record instanceof LikedBy ){
      if ( $record->object_model == $this->tableName() && $record->object_id == $this->id ){
        return true;
      }
    }
    return false;
  }

  public function getLikes(){
    $like = new LikedBy();
    return $like->all([$this, 'likeFilter']);
  }

  public function addLike(User $user){
    if ( !$this->id ){
      throw new \Exception("Can't add like to an unsaved object. " . $this->className());
    }
    $role = Role::userRole();
    if ( $user && $user->hasRole($role) ){
      $likes = $this->getLikes();
      $previous = null;
      foreach ( $likes as $like ){
        if ( $like->user_id == $user->id ){
          $previous = $like;
        }
      }
      if ( !$previous ){
        $newLike = new LikedBy();
        $newLike->user_id = $user->id;
        $newLike->object_model = $this->tableName();
        $newLike->object_id = $this->id;
        return $newLike->save();
      } else {
        return $previous->delete();
      }
    }
    throw new \Exception("addLike expects parameter 1 to be a valid user with proper roles");
  }
}

