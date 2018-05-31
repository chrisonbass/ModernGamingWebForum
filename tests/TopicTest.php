<?php

use PHPUnit\Framework\TestCase;
use app\App;
use app\model\Board;
use app\model\Topic;
use app\model\LikedBy;
use app\model\User;

final class TopicTest extends TestCase {
  private static $working_topic_id = null;
  private static $working_user = null;

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

  public function testDeletingCreatedTopic(){
    $topic = new Topic(static::$working_topic_id);
    $this->assertEquals(
      true,
      $topic->delete()
    );
  }

  public function testNoLikesAreAttachedToDeletedTopic(){
    $id = static::$working_topic_id;
    $t = new Topic();
    $likes = (new LikedBy())->all(function($like){
      return $like->object_model == $t->tableName() && $like->object_id == $id;
    } );
    $this->assertEmpty( $likes );
  }
}

