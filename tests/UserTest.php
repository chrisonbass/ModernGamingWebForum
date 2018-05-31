<?php

use PHPUnit\Framework\TestCase;
use app\model\User;

final class UserTest extends TestCase
{
    public function testInstanceCanBeCreated(){
        $this->assertInstanceOf(
            User::class,
            new User 
        );
    }

    public function testUserConstructionFromId(){
        $id = "5b022f56bffdb";
        $user = new User($id);
        $this->assertEquals(
          $id,
          $user->id
        );
    }

    /*
    public function testCanBeUsedAsString(): void
    {
        $this->assertEquals(
            'user@example.com',
            Email::fromString('user@example.com')
        );
    }
     */
}

