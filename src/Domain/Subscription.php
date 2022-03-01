<?php

namespace Drupal\subscription\Domain;

class Subscription{

    private int $uid;
    private string $startDate;
    private int $durationMonths;
    private string $cardNumber;
    private int $renewStatus;
    private string $subscriptionStatus;

  public function __construct($uid, $startDate, $durationMonths, $cardNumber, $renewStatus, $subscriptionStatus) {
        $this->uid = $uid;
        $this->startDate = $startDate;
        $this->durationMonths = $durationMonths;
        $this->cardNumber = $cardNumber;
        $this->renewStatus = $renewStatus;
        $this->subscriptionStatus = $subscriptionStatus;
    }

    public function getUserId(): int
    {
      return $this->uid;
    }

    public function getStartDate(): string
    {
        return $this->startDate;
    }

    public function getDurationMonths(): int
    {
        return $this->durationMonths;
    }

  public function getCardNumber(): string
  {
    return $this->cardNumber;
  }

  public function getRenewStatus(): int
  {
    return $this->renewStatus;
  }

  public function getSubscriptionStatus(): string
  {
    return $this->subscriptionStatus;
  }

  public function RemainingSubscriptionMonths($currentDate){

    $startDateTimeStamp =  strtotime($this->getStartDate());
    $startDate = date('Y-m-d H:i:s', $startDateTimeStamp);

    // Creates DateTime objects
    $startDate = date_create($startDate);
    $currentDate = date_create($currentDate);

    // Calculates the difference between DateTime objects and get the month
    $interval = date_diff($startDate, $currentDate);
    $interval =  reset($interval);
    $difference = date("m",strtotime($interval));

    $months = $this->getDurationMonths();

    return $months - $difference;

  }

  public function TotalSubscriptionPrice()
  {
    $SUBSCRIPTION_PRICE_PER_MONTH = 29;
    return $SUBSCRIPTION_PRICE_PER_MONTH * $this->getDurationMonths();
  }

  public function getNextRenewDate(){
    $date = $this->getStartDate();
    $months = $this->getDurationMonths();
    $startDate = date("d-m-Y",strtotime($date . ' + ' . $months . 'months'));
    return $startDate;
  }

}
