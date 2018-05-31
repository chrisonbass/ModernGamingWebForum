<?php
namespace app\model;

use app\db\ActiveRecord;
use app\db\LikedRecord;
use app\model\User;

class Topic extends LikedRecord {
  public $board_id;
  public $created_by;
  public $created_at;
  public $title;
  public $body;

  public $comments;
  public $board;

  public function tableName(){
    return "topic";
  }

  public function init(){
    parent::init();
    if ( $this->id ){
      $this->comments = (new Comment())->all([$this,'filterComments']);
      if ( $this->board_id ){
        $this->board = new Board($this->board_id);
      }
    }
    $this->on(ActiveRecord::EVENT_BEFORE_SAVE, [$this, 'beforeSave']);
  }

  /**
   * Returns the creator of the
   * topic
   * @return app\model\User the user that created
   *                        this topic
   */
  public function getUser(){
    if ( $this->created_by ){
      $user = new User($this->created_by);
      if ( $user && $user->id ){
        return $user;
      }
    }
    return null;
  }

  /**
   * @Override customizes the label
   */
  public function getLabel(){
    if ( $this->id ){
      $label = $this->title;
      $user = $this->getUser();
      if ( $user && $user->id ){
        $label .= " : " . $user->username;
      }
      return $label;
    }
    return parent::getLabel();
  }

  /**
   * @Override custom validation
   */
  public function validate(){
    if ( !$this->title || !strlen($this->title) ){
      $this->addError("title", "The title cannot be blank");
    }
    if ( !$this->body || !strlen($this->body) ){
      $this->addError("body", "The body cannot be blank");
    }
    if ( !$this->created_by ){
      $this->addError("created_by", "Invalid user");
    }
    if ( !$this->board_id ){
      $this->addError("board_id", "Invalid board");
    }
  }

  /**
   * @Override filters the properties
   * so that the comments and board
   * properties aren't returned, because
   * they aren't store in the same CSV
   */
  public function getProperties(){
    $props = parent::getProperties();
    unset($props['comments']);
    unset($props['board']);
    return $props;
  }

  /**
   * This function listends for the
   * ActiveRecord::EVENT_BEFORE_SAVE event
   * on its self
   * @param app\base\Event the event
   */
  public function beforeSave($event){
    if ( !$this->id ){
      $this->created_at = date("Y-m-d H:i:s");
    }
  }
  /**
   * Filter method used by the 
   * getComments method
   * @return bool whether or not the 
   *              passed comment belongs
   *              to this topic
   */
  public function filterComments($comment){
    return $this->id == $comment->topic_id;
  }

  /**
   * Returns an array of app\model\Comment
   * that are attached to this Topic
   * @return array app\model\Comment
   */
  public function getComments(){
    if ( $this->comments ){
      return $this->comments;
    }
    $this->comments = (new Comment())->all([$this,'filterComments']);
    return $this->comments;
  }
}
