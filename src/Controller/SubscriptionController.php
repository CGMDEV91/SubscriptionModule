<?php

namespace Drupal\subscription\Controller;

use Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException;
use Drupal\Component\Plugin\Exception\PluginNotFoundException;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\subscription\Application\SubscriptionCreator;
use Drupal\subscription\Entity\SubscriptionEntity;
use Drupal\Core\Access\AccessResult;

class SubscriptionController extends ControllerBase {

   public static function postSubscription($subscription) : void
  {
    $subscriptionRepository = SubscriptionEntity::create();
    $subscriptionCreator = new SubscriptionCreator($subscriptionRepository);

    $uid = \Drupal::currentUser()->id();
    $startDate = DrupalDateTime::createFromTimestamp(time())->render();
    $subscriptionStatus = 'active';

    $subscriptionCreator(
      $uid,
      $startDate,
      $subscription['months'],
      $subscription['card_number'],
      $subscription['auto_renew'],
      $subscriptionStatus
    );
  }

  /**
   * @throws InvalidPluginDefinitionException
   * @throws PluginNotFoundException
   */
  public function showSubscription() {

    $uid = \Drupal::currentUser()->id();
    $subscriptionRepository = SubscriptionEntity::create();
    $subscription =  $subscriptionRepository->getSubscription($uid);

    $currentDate = date('Y-m-d H:i:s');
    $remainingMonthsToRenew = $subscription->RemainingSubscriptionMonths($currentDate);
    $totalPrice = $subscription->TotalSubscriptionPrice();
    $nextRenewDate = $subscription->getNextRenewDate();

    return [
      '#theme' => 'subscription_details_template',
      '#subscription' => $subscription,
      '#user' => \Drupal::currentUser(),
      '#remaining' => $remainingMonthsToRenew,
      '#total_price' => $totalPrice,
      '#next_renew_date' => $nextRenewDate,
    ];
  }

  public function accessSubcriptionForm(){
    $status = _checkSubscriptionStatus();
    if($status || !\Drupal::currentUser()->isAuthenticated()) {
      return AccessResult::forbidden();
    }

    return AccessResult::allowed();
  }

  public function accessSubcriptionDetail(){
    $status = _checkSubscriptionStatus();
    if($status) {
      return AccessResult::allowed();
    }

    return AccessResult::forbidden();
  }
}
