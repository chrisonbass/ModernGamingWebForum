<?php
namespace app\controller;

use app\App;
use app\model\Role;
use app\model\User as UserModel;
use app\model\Topic;
use app\model\Comment;
use app\view\UserView;

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
        break;
    }
    return parent::getRequiredRole();
  }

  public function sortByTimeCreated($a,$b){
    $c = strtotime($a->created_at);
    $d = strtotime($b->created_at);
    if ($c == $d) {
      return 0;
    }
    return ($c < $d) ? -1 : 1;
  }

  public function actionView(){
    $app = App::app();
    $model = new UserModel($app->id);
    $latest_topic = null;
    $latest_comment = null;
    $comment_count = 0;
    $topic_count = 0;
    if ( $model && $model->id ){
      $comments = $model->getComments();
      $topics = $model->getTopics();
      $topic_count = count($topics);
      $comment_count = count($comments);
      usort( $comments, [$this, "sortByTimeCreated"] );
      usort( $topics, [$this, "sortByTimeCreated"] );
      $latest_comment = end($comments);
      $latest_topic = end($topics);
    }
    return new UserView( array(
      "model" => $model,
      "latest_comment" => $latest_comment,
      "latest_topic" => $latest_topic,
      "topic_count" => $topic_count,
      "comment_count" => $comment_count
    ) );
  }
}
