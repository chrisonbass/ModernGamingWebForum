<?php
use PHPUnit\Framework\TestCase;
use app\util\Text;
use app\model\UserRole;

final class TextUtilTest extends TestCase {
  public function testHyphenToSnakeCase(){
    $value = "user-role";
    $expect = "UserRole";
    $this->assertEquals(
      $expect,
      Text::hyphenToSnakeCase($value)
    );
  }

  public function testClassNameIsConvertedToPrettyName(){
    $expect = "User Role";
    $this->assertEquals(
      $expect,
      Text::classNameToName(UserRole::className())
    );
  }

  public function testClassNameIsConvertedToHyphenCased(){
    $expect = "user-role";
    $this->assertEquals(
      $expect,
      Text::classToModelName(UserRole::className())
    );
  }

  public function testModelFiedNameIsConvertedToReadableLabel(){
    $value = "user_name_yep";
    $expect = "User Name Yep";
    $this->assertEquals(
      $expect,
      Text::modelFieldToLabel($value)
    );
  }
}

