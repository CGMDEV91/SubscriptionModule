<?php

namespace Drupal\Tests\subscription\Unit;

use Drupal\subscription\Application\SubscriptionCreator;
use Drupal\Tests\UnitTestCase;
use Drupal\subscription\Domain\Subscription;
use Drupal\Tests\subscription\Unit\InMemorySubscriptionRepository;

/**
 *
 * @group subscription
 *
 * FROM ROOT DIRECTORY RUN THE TESTS WITH:
 * ./vendor/bin/phpunit -c web/core web/modules/custom/subscription/tests/src/Unit/Application/GetSubscriptionTest.php --testdox
 *
 * WE COULD RUN THE TESTS WITH FLAGS AND CALL GROUPED TESTS ( the flag --testdox provide us more information about each test of the group/class )
 *  Form root directory--> ./vendor/bin/phpunit -c web/core/ --testsuite unit --group=subscription --testdox
 */
class GetSubscriptionTest extends UnitTestCase
{
  private InMemorySubscriptionRepository $subscriptionRepository;
  private SubscriptionCreator $subscriptionCreator;

  public function setUp(): void
  {
    $this->subscriptionRepository = new InMemorySubscriptionRepository();
    $this->subscriptionCreator = new SubscriptionCreator($this->subscriptionRepository);
  }

  /**
   * Data provider for testGetSubscription()
   */
  public function provideTestGetSubscription(){

    $subscription1 = new Subscription(1, '22-01-2022', 1, '5402-0526-8024-2034', 1, 'active');
    $subscription2 = new Subscription(2, '22-01-2022', 3, '5402-0526-8024-2034', 1, 'active');
    $subscription3 = new Subscription(3, '22-01-2022', 6, '5402-0526-8024-2034', 1, 'active');

    return[
            //EXPECTED, //PARAM_1
      [$subscription1, $subscription1],
      [$subscription2, $subscription2],
      [$subscription3, $subscription3],
    ];
  }

  /**
   * @dataProvider  provideTestGetSubscription
   */
  public function testGetSubscription($expectedSubscription, Subscription $subscription)
  {
    $this->subscriptionCreator->__invoke(
      $subscription->getUserId(),
      $subscription->getStartDate(),
      $subscription->getDurationMonths(),
      $subscription->getCardNumber(),
      $subscription->getRenewStatus(),
      $subscription->getSubscriptionStatus(),
    );

    $subscription = $this->subscriptionRepository->getSubscription($subscription->getUserId());

    $this->assertEquals($expectedSubscription, $subscription);
  }

  /**
   * Data provider for testRemainingSubscriptionMonths()
   */
  public function provideTestRemainingSubscriptionMonths(){

    $subscription1 = new Subscription(1, '22-01-2022', 1, '5402-0526-8024-2034', 1, 'active');
    $subscription2 = new Subscription(2, '22-02-2022', 5, '5402-0526-8024-2034', 1, 'active');
    $subscription3 = new Subscription(3, '22-03-2022', 6, '5402-0526-8024-2034', 1, 'active');

    return[
      //EXPECTED, //PARAM_1
      [0, $subscription1],
      [4, $subscription2],
      [5, $subscription3],
    ];
  }

  /**
   * @dataProvider  provideTestRemainingSubscriptionMonths
   */
  public function testRemainingSubscriptionMonths($expectedMonts, Subscription $subscription){
    $this->subscriptionCreator->__invoke(
      $subscription->getUserId(),
      $subscription->getStartDate(),
      $subscription->getDurationMonths(),
      $subscription->getCardNumber(),
      $subscription->getRenewStatus(),
      $subscription->getSubscriptionStatus(),
    );

    $subscription = $this->subscriptionRepository->getSubscription($subscription->getUserId());

    $currentDate = date('Y-m-d H:i:s');
    $remainingSubscriptionMonths = $subscription->RemainingSubscriptionMonths($currentDate);

    $this->assertEquals($expectedMonts, $remainingSubscriptionMonths);
  }


  /**
   * Data provider for testTotalSubscriptionPrice()
   */
  public function provideTestTotalSubscriptionPrice(){

    $subscription1 = new Subscription(1, '22-01-2022', 1, '5402-0526-8024-2034', 1, 'active');
    $subscription2 = new Subscription(2, '22-02-2022', 3, '5402-0526-8024-2034', 1, 'active');
    $subscription3 = new Subscription(3, '22-03-2022', 6, '5402-0526-8024-2034', 1, 'active');

    return[
      //EXPECTED, //PARAM_1
      [29, $subscription1],
      [87, $subscription2],
      [174, $subscription3],
    ];
  }

  /**
   * @dataProvider  provideTestTotalSubscriptionPrice
   */
  public function testTotalSubscriptionPrice($expectedTotalPrice, Subscription $subscription){
    $this->subscriptionCreator->__invoke(
      $subscription->getUserId(),
      $subscription->getStartDate(),
      $subscription->getDurationMonths(),
      $subscription->getCardNumber(),
      $subscription->getRenewStatus(),
      $subscription->getSubscriptionStatus(),
    );

    $subscription = $this->subscriptionRepository->getSubscription($subscription->getUserId());

    $totalSubscriptionPrice = $subscription->TotalSubscriptionPrice();

    $this->assertEquals($expectedTotalPrice, $totalSubscriptionPrice);
  }

  /**
   * Data provider for testTestGetNextRenewDate()
   */
  public function provideTestGetNextRenewDate(){

    $subscription1 = new Subscription(1, '14-02-2022', 1, '5402-0526-8024-2034', 1, 'active');
    $subscription2 = new Subscription(2, '14-02-2022', 3, '5402-0526-8024-2034', 1, 'active');
    $subscription3 = new Subscription(3, '14-02-2022', 6, '5402-0526-8024-2034', 1, 'active');

    return[
      //EXPECTED, //PARAM_1
      ['14-03-2022', $subscription1],
      ['14-05-2022', $subscription2],
      ['14-08-2022', $subscription3],
    ];
  }

  /**
   * @dataProvider  provideTestGetNextRenewDate
   */
  public function testTestGetNextRenewDate($expectedRenewDate, Subscription $subscription){
    $this->subscriptionCreator->__invoke(
      $subscription->getUserId(),
      $subscription->getStartDate(),
      $subscription->getDurationMonths(),
      $subscription->getCardNumber(),
      $subscription->getRenewStatus(),
      $subscription->getSubscriptionStatus(),
    );

    $subscription = $this->subscriptionRepository->getSubscription($subscription->getUserId());

    $nextRenewDate = $subscription->getNextRenewDate();

    $this->assertEquals($expectedRenewDate, $nextRenewDate);
  }
}


