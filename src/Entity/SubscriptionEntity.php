<?php

namespace Drupal\subscription\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityStorageException;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\subscription\Domain\Subscription;
use Drupal\subscription\Domain\SubscriptionRepository;

/**
 * @ContentEntityType(
 *   id = "subscription",
 *   label = @Translation("Subscription"),
 *   base_table = "subscription",
 *   entity_keys = {
 *     "id" = "id",
 *     "uuid" = "uuid",
 *     "uid" = "uid",
 *     "start_date" = "start_date",
 *     "duration_months" = "duration_months",
 *     "card_number" = "card_number",
 *     "renew_status" = "renew_status",
 *     "subscription_status" = "subscription_status",
 *   },
 * )
 */

class SubscriptionEntity extends ContentEntityBase implements SubscriptionRepository  {

  public static function baseFieldDefinitions(EntityTypeInterface $entity_type): array
  {
    // Get the field definitions for 'id' and 'uuid' from the parent.
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['uid'] = BaseFieldDefinition::create('string')
      ->setRequired(true);

    $fields['start_date'] = BaseFieldDefinition::create('string')
      ->setRequired(true);

    $fields['duration_months'] = BaseFieldDefinition::create('integer')
      ->setRequired(true);

    $fields['card_number'] = BaseFieldDefinition::create('string')
      ->setRequired(true);

    $fields['renew_status'] = BaseFieldDefinition::create('integer')
      ->setRequired(true)->setDefaultValue(0);

    $fields['subscription_status'] = BaseFieldDefinition::create('string')
      ->setRequired(true);

    return $fields;
  }

  /**
   * @throws EntityStorageException
   */
  public function saveSubscription(Subscription $subscription): void
  {
    $this->set('uid', $subscription->getUserId());
    $this->set('start_date', $subscription->getStartDate());
    $this->set('duration_months', $subscription->getDurationMonths());
    $this->set('card_number', $subscription->getCardNumber());
    $this->set('renew_status', $subscription->getRenewStatus());
    $this->set('subscription_status', $subscription->getSubscriptionStatus());
    $this->save();
  }

  public function getSubscription($uid): Subscription
  {
    $subscriptionEntityLoad = \Drupal::entityTypeManager()
      ->getStorage('subscription')
      ->loadByProperties(['uid' => $uid]);

    $subscriptionEntityLoad = reset($subscriptionEntityLoad);

    $uid = $subscriptionEntityLoad->get('uid')->value;
    $startDateTimeStamp =  strtotime($subscriptionEntityLoad->get('start_date')->value);
    $startDate = date("d-m-Y", $startDateTimeStamp);
    $durationMonths = $subscriptionEntityLoad->get('duration_months')->value;
    $cardNumber = $subscriptionEntityLoad->get('card_number')->value;
    $renewStatus = $subscriptionEntityLoad->get('renew_status')->value;

    $activation = _checkSubscriptionStatus();
    $subscriptionStatus = $activation ? 'Active' : 'Inactive';

    return new Subscription($uid, $startDate, $durationMonths, $cardNumber, $renewStatus, $subscriptionStatus);
  }
}
