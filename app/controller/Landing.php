<?php
namespace app\controller;

// Utility Classes
use app\io\Csv;
use app\base\Controller;

// View Classes
use app\view\HtmlWrapper;
use app\view\MetaTag;
use app\view\Home;
use app\view\Link;
use app\view\Landing as LandingView;
use app\view\Table;

class Landing extends Controller {
  public function getTitle(){
    switch( $this->action ){
      case "Index":
      case "index":
        return "Modern Gaming Home";
      default:
        return parent::getTitle();
    }
  }

  public function headerIndex(){
    $tags = array(
      array(
        "name" => "description",
        "value" => "Capstone Course App"
      ),
    );
    foreach ( $tags as &$tag ){
      $tag = new MetaTag($tag);
    }
    return implode(' ', $tags);
  }

  public function actionIndex(){
    $title = "Capstone Course App";
    $links = array();
    foreach ( array(
      "user" => "Users",
      "board" => "Boards",
      "user-role" => "User Roles",
      "csv-editor" => "CSV Editor"
    ) as $type => $label ){
      $links[] = new Link( array(
        "url" => "index.php?controller={$type}",
        "label" => $label
      ) );
    }
    return new LandingView( array(
        "title" => $title,
        "links" => $links
      ),
      array(
      )
    );
  }
}
