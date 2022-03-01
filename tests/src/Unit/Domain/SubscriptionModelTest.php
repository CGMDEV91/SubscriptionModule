<?php

namespace Drupal\Tests\subscription\Unit;

use Drupal\Tests\UnitTestCase;
use Drupal\subscription\Domain\Subscription;

/**
 *
 * @group subscription
 *
 * FROM ROOT DIRECTORY RUN THE TESTS WITH:
 * ./vendor/bin/phpunit -c web/core web/modules/custom/subscription/tests/src/Unit/Domain/SubscriptionTest.php --testdox
 *
 * WE COULD RUN THE TESTS WITH FLAGS AND CALL GROUPED TESTS ( the flag --testdox provide us more information about each test of the group/class )
 *  From root directory--> ./vendor/bin/phpunit -c web/core/ --testsuite unit --group=subscription --testdox
 */
class SubscriptionModelTest extends UnitTestCase {

  /**
   * Data provider for testRemainingSubscriptionMonths()
   */
  public function provideTestRemainingSubscriptionMonths(){
    return[
      //EXPECTED, //PARAM_1 //PARAM_2
      [0, '22-01-2022', 1],
      [4, '22-03-2022', 5],
      [5, '22-11-2022', 6],
    ];
  }

  /**
   * @dataProvider  provideTestRemainingSubscriptionMonths
   */
  public function testRemainingSubscriptionMonths($expected, $startDate, $months){

    $subscription = new Subscription(1, $startDate, $months, '5402-0526-8024-2034', 1, 'active');
    $currentDate = date('Y-m-d H:i:s');
    $remainingMonts = $subscription->RemainingSubscriptionMonths($currentDate);

    $this->assertEquals($expected, $remainingMonts);
  }

  /**
   * Data provider for testTotalSubscriptionPrice()
   */
  public function provideTestTotalSubscriptionPrice(){
    return[
      //EXPECTED, //PARAM_1
      [29, $subscription = new Subscription(1, '22-01-2022', 1, '5402-0526-8024-2034', 1, 'active')],
      [87, $subscription = new Subscription(1, '22-01-2022', 3, '5402-0526-8024-2034', 1, 'active')],
      [174, $subscription = new Subscription(1, '22-01-2022', 6, '5402-0526-8024-2034', 1, 'active')],
    ];
  }
  /**
   * @dataProvider  provideTestTotalSubscriptionPrice
   */
  public function testTotalSubscriptionPrice($expected, Subscription $subscription){

    $totalPrice = $subscription->TotalSubscriptionPrice();
    $this->assertEquals($expected, $totalPrice);
  }

  /**
   * Data provider for testGetNextRenewDate()
   */
  public function provideTestGetNextRenewDate(){
    return[
      //EXPECTED, //PARAM_1
      ['14-03-2022', $subscription = new Subscription(1, '14-02-2022', 1, '5402-0526-8024-2034', 1, 'active')],
      ['14-05-2022', $subscription = new Subscription(1, '14-02-2022', 3, '5402-0526-8024-2034', 1, 'active')],
      ['14-08-2022', $subscription = new Subscription(1, '14-02-2022', 6, '5402-0526-8024-2034', 1, 'active')],
    ];
  }
  /**
   * @dataProvider  provideTestGetNextRenewDate
   */
  public function testGetNextRenewDate($expected,Subscription $subscription){

    $nextRenewDate = $subscription->getNextRenewDate();
    $this->assertEquals($expected, $nextRenewDate);
  }
}
