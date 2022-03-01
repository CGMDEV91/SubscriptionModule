<?php

namespace Drupal\subscription\Application;

use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\subscription\Domain\SubscriptionRepository;
use Drupal\subscription\Domain\Subscription;

final class SubscriptionCreator
{
  private SubscriptionRepository $repository;

  public function __construct(SubscriptionRepository $repository){
    $this->repository =  $repository;
  }

  public function __invoke($uid, $startDate, $durationMonths, $cardNumber, $renewStatus, $subscriptionStatus){

    $subscription = new Subscription($uid, $startDate, $durationMonths, $cardNumber, $renewStatus, $subscriptionStatus);
    $this->repository->saveSubscription($subscription);
  }
}

