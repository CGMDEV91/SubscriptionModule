<?php

namespace Drupal\subscription\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\subscription\Controller\SubscriptionController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Datetime\DrupalDateTime;
use \Drupal\Core\Url;

class SubscriptionForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'subscription_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $date = DrupalDateTime::createFromTimestamp(time());
    $date = $date->format('d-m-Y');

    $form['subscription'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Complete the fields'),
    ];

    $form['subscription']['start_date'] = [
      '#markup' => t("<strong>Start subscription date: </strong>" . $date),
    ];

    $form['subscription']['months'] = [
      '#type' => 'select',
      '#title' => $this
        ->t('Select subscription Time'),
      '#options' => [
        '1' => $this
          ->t('1 Month'),
        '2' => $this
          ->t('2 Months'),
        '3' => $this
          ->t('3 Months'),
        '4' => $this
          ->t('4 Months'),
        '5' => $this
          ->t('5 Months'),
        '6' => $this
          ->t('6 Months'),
      ],
    ];

    $form['subscription']['auto_renew'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Do yo want autorenew the subscription?'),
    ];

    $form['subscription']['card_number'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Card Number'),
      '#size' => 30,
    ];


    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Subscribe'),
      '#button_type' => 'primary',
    ];

    $form['actions']['cancel'] = [
      '#type' => 'submit',
      '#value' => $this->t('Cancel'),
      '#submit' => ['_cancelFormAction'],
      '#button_type' => 'primary',
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $cardNumber = $form_state->getValue('card_number');

    $valid = _cardNumberValidator($cardNumber,'all');

    if(!$valid){
      $form_state->setErrorByName('card_number', $this->t('Incorrect Card Number, try again.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $subscription = [
      'months' => $form_state->getValue('months'),
      'card_number' => $form_state->getValue('card_number'),
      'auto_renew' => $form_state->getValue('auto_renew'),
    ];

    SubscriptionController::postSubscription($subscription);

    \Drupal::messenger()->addMessage(t("You have submitted a new Subscription!"));

    global $base_url;
    $route_provider = \Drupal::service('router.route_provider');
    $url = reset($route_provider->getRouteByName('subscription_controller_detail.content'));
    $response = new RedirectResponse($base_url . $url);
    $response->send();
    drupal_flush_all_caches();
  }

}
