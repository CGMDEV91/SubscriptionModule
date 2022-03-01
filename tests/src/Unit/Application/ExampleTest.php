<?php

namespace Drupal\Tests\subscription\Unit;

use Drupal\Tests\UnitTestCase;

/**
 *
 * @group subscription
 */
class ExampleTest extends UnitTestCase
{
  public function setUp(): void
  {
    //code
  }
  /**
   * Data provider for testExample()
   */
  public function provideTestExample(){

    $name1 = "Jhon";
    $name2 = "William";
    $name3 = "Jackie";

    return[
      //EXPECTED //PARAM_1
      ["Jhon" , $name1],
      ["William", $name2],
      ["Jackie", $name3],
    ];
  }
  /**
   * @dataProvider  provideTestExample
   */
  public function testExample($expected, $name){
    $this->assertEquals($expected, $name);
  }

  public function tearDown(): void
  {
    //code
  }
}

