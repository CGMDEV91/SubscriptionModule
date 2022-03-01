<?php

namespace Drupal\subscription\Domain;

use Drupal\subscription\Domain\Subscription;

interface SubscriptionRepository {
  public function saveSubscription(Subscription $subscription): void;

  public function getSubscription($uid): Subscription;
}
