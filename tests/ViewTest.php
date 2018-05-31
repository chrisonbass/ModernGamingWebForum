<?php

use PHPUnit\Framework\TestCase;
use app\App;
use app\controller\Landing;
use app\controller\Board;
use app\controller\Topic;

final class ViewTest extends TestCase {
  public function testAppControllerIsConstructedProperly(){
    $_GET = array();
    $app = new App();
    $this->assertInstanceOf(
      Landing::class,
      $app->getController()
    );

    $_GET = array();
    $_GET['controller'] = "board";
    $app = new App();
    $this->assertInstanceOf(
      Board::class,
      $app->getController()
    );

    $_GET = array();
    $_GET['controller'] = "topic";
    $app = new App();
    $this->assertInstanceOf(
      Topic::class,
      $app->getController()
    );
  }

  public function testHomeOutputIsValid(){
    $_GET = array();
    static::CustomTest($this, "home-output");
  }

  public function testBoardListPageIsValid(){
    $_GET = array();
    $_GET['controller'] = "board";
    static::CustomTest($this, "board-index");
  }

  public function testGeneralBoardPageIsValid(){
    $_GET = array();
    $_GET['controller'] = "board";
    $_GET['action'] = "view";
    $_GET['id'] = "5b00cc56409f3";
    static::CustomTest($this, "board-general");
  }

  public function testTopicViewIsValid(){
    $_GET = array();
    $_GET['controller'] = "topic";
    $_GET['action'] = "view";
    $_GET['id'] = "5b0279f02ad33";
    static::CustomTest($this, "topic-view");
  }

  private static function CustomTest($ref, $file){
    $expect = file_get_contents(__DIR__ . '/' . $file . '.txt');
    $ref->assertGreaterThan(200, strlen($expect));
    $ref->expectOutputString($expect); 

    $app = new App();
    $app->run();
  }
}

