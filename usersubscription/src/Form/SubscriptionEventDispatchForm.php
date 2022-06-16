<?php

/**
 * @file
 * Contains \Drupal\usersubscription\Form\SubscriptionEventDispatchForm.
 */

namespace Drupal\usersubscription\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\usersubscription\SubscriptionEvent;


/**
 * Class SubscriptionEventDispatchForm.
 *
 * @package Drupal\usersubscription\Form
 */
class SubscriptionEventDispatchForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'subscription_event_dispatch_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    

    $form['dispatch'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Subscribe'),
    );
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $dispatcher = \Drupal::service('event_dispatcher');
    $event = new SubscriptionEvent($form_state->getValue('name'));
    $dispatcher->dispatch(SubscriptionEvent::SUBMIT, $event);
  }
}
