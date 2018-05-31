<?php
namespace app\model;

use app\db\ActiveRecord;

class Board extends ActiveRecord {
  public $name;
  public $description;
  public $topics;

  public function tableName(){
    return "board";
  }

  public function init(){
    parent::init();
    $this->topics = null;

    if ( $this->id ){
      $this->topics = $this->getTopics();
    }
  }

  public function getLabel(){
    if ( $this->id ){
      return $this->name . " - " . substr($this->description, 0, 25);
    }
    return parent::getLabel();
  }

  public function filterTopics($topic){
    return $this->id == $topic->board_id;
  }

  public function getProperties(){
    $props = parent::getProperties();
    unset($props['topics']);
    return $props;
  }

  public function sortTopicsByDateASC($a,$b){
    $at = strtotime($a->created_at);
    $bt = strtotime($b->created_at);
    if ( $at == $bt ){
      return 0;
    }
    return ( $at < $bt ) ? -1 : 1;
  }

  public function sortTopicsByDateDESC($a,$b){
    $at = strtotime($a->created_at);
    $bt = strtotime($b->created_at);
    if ( $at == $bt ){
      return 0;
    }
    return ( $at > $bt ) ? -1 : 1;
  }

  public function getLatestTopic(){
    $topics = $this->getTopics();
    if ( count($topics) ){
      usort($topics, [$this, 'sortTopicsByDateDESC']);
      return reset($topics);
    }
    return null;
  }

  public function getTopics(){
    if ( $this->topics ){
      return $this->topics;
    }
    $this->topics = (new Topic())->all([$this,'filterTopics']);
    return $this->topics;
  }
}
