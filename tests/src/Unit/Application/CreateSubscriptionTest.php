<?php

namespace Drupal\Tests\subscription\Unit;

use Drupal\subscription\Application\SubscriptionCreator;
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
class CreateSubscriptionTest extends UnitTestCase
{
  private InMemorySubscriptionRepository $subscriptionRepository;
  private SubscriptionCreator $subscriptionCreator;

  public function setUp(): void
  {
    $this->subscriptionRepository = new InMemorySubscriptionRepository();
    $this->subscriptionCreator = new SubscriptionCreator($this->subscriptionRepository);
  }


  /**
   * Data provider for testCreateSubscription()
   */
  public function provideTestCreateSubscription(){

    $subscription1 = new Subscription(1, '22-01-2022', 1, '5402-0526-8024-2034', 1, 'active');
    $subscription2 = new Subscription(2, '22-02-2023', 3, '5402-0526-8024-2035', 1, 'active');
    $subscription3 = new Subscription(3, '22-03-2024', 6, '5402-0526-8024-2036', 1, 'active');

    return[
      //PARAM_1
      [$subscription1],
      [$subscription2],
      [$subscription3],
    ];
  }

  /**
   * @dataProvider  provideTestCreateSubscription
   */
  public function testCreateSubscription(Subscription $subscription)
  {
    $this->subscriptionCreator->__invoke(
      $subscription->getUserId(),
      $subscription->getStartDate(),
      $subscription->getDurationMonths(),
      $subscription->getCardNumber(),
      $subscription->getRenewStatus(),
      $subscription->getSubscriptionStatus()
    );

    $subscription = $this->subscriptionRepository->getSubscription($subscription->getUserId());

    $this->assertInstanceOf(Subscription::class, $subscription);
  }
}
