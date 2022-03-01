<?php

namespace Drupal\Tests\subscription\Unit;

use Drupal\subscription\Domain\Subscription;
use Drupal\subscription\Domain\SubscriptionRepository;

class InMemorySubscriptionRepository implements SubscriptionRepository
{
  private array $subscriptions;

  public function saveSubscription(Subscription $subscription): void
  {
    $this->subscriptions[$subscription->getUserId()] = $subscription;
  }

  public function getSubscription($uid): Subscription
  {
    return $this->subscriptions[$uid];
  }
}
