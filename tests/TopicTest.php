<?php

use PHPUnit\Framework\TestCase;
use app\model\Board;
use app\model\Topic;
use app\model\Comment;
use app\model\LikedBy;
use app\model\User;

final class TopicTest extends TestCase {
  private static $working_topic_id = null;
  private static $working_user = null;
  private static $working_comment_id = null;

  public function testCreatingTopic(){
    $user_id = "5b022f56bffdb";
    $board_id = "5b00cc56409f3";
    $now = date("Y-m-d H:i:s");
    $title = "Test Title";
    $body = "test body";

    $user = static::$working_user = new User($user_id);
    $board = new Board($board_id);

    $topic = new Topic();
    $topic->board_id = $board->id;
    $topic->created_by = $user->id;
    $topic->created_at = $board->id;
    $topic->title = $title;
    $topic->body = $body;

    static::$working_topic_id = $topic->save();

    $this->assertNotNull( static::$working_topic_id );
  }

  public function testLikingTopic(){
    $this->assertNotNull(static::$working_topic_id);
    $this->assertNotNull(static::$working_user);

    $topic = new Topic(static::$working_topic_id);
    $user = static::$working_user; 

    $topic->addLike($user);
    $likes = $topic->getLikes();
    $this->assertNotEmpty( $likes );
  }

  public function testLikingAgainToRemoveLikeFromTopic(){
    $topic = new Topic(static::$working_topic_id);
    $user = static::$working_user; 
    $topic->addLike($user);
    $likes = $topic->getLikes();
    $this->assertEmpty($likes);
  }

  public function testAddCommentToTopic(){
    $topic = new Topic(static::$working_topic_id);
    $user = static::$working_user; 

    $this->assertNotNull($topic->id);
    $this->assertNotNull($user);

    $comment = new Comment();
    $comment->created_by = $user->id;
    $comment->topic_id = $topic->id;
    $comment->body = "Testing comment";
    $cid = static::$working_comment_id = $comment->save();

    $this->assertNotNull($cid);

    $value = (new Topic(static::$working_topic_id))->getComments();
    $this->assertNotEmpty($value);
  }

  public function testLikingAComment(){
    $topic = new Topic(static::$working_topic_id);
    $user = static::$working_user; 
    $comment = new Comment(static::$working_comment_id);
    $this->assertEquals(
      $user->id,
      $comment->created_by
    );
    $comment->addLike($user);
    $this->assertNotEmpty($comment->getLikes());
  }

  public function testLikingAgainBeforeDeletingTopic(){
    $topic = new Topic(static::$working_topic_id);
    $user = static::$working_user; 
    $topic->addLike($user);
    $this->assertNotEmpty($topic->getLikes());
  }

  public function testDeletingCreatedTopic(){
    $topic = new Topic(static::$working_topic_id);
    $this->assertEquals(
      true,
      $topic->delete()
    );
  }

  public function testNoLikesAreAttachedToDeletedTopic(){
    $likes = (new LikedBy())->all(function($like){
      $t = new Topic();
      return $like->object_model == $t->tableName() && $like->object_id == static::$working_topic_id;
    } );
    $this->assertEmpty( $likes );
  }

  public function testNoCommentsAreAttachedToDeletedTopic(){
    $comments = (new Comment())->all(function($comment){
      return $comment->topic_id == static::$working_topic_id;
    } );
    $this->assertEmpty( $comments );
  }

  public function testNoLikesAreAttachedToNowDeletedComments(){
    $likes = (new LikedBy())->all(function($like){
      $c = new Comment();
      return $like->object_model == $c->tableName() && $like->object_id == static::$working_comment_id;
    } );
    $this->assertEmpty( $likes );
  }

}

