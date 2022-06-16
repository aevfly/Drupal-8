<?php

/**
 * @file
 * Contains \Drupal\usersubscription\SubscriptionEventSubScriber.
 */

namespace Drupal\usersubscription\EventSubscriber;

use Drupal\Core\Config\ConfigCrudEvent;
use Drupal\Core\Config\ConfigEvents;
use Drupal\usersubscription\SubscriptionEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\user\Entity\User;


/**
 * Class SubscriptionEventSubScriber.
 *
 * @package Drupal\usersubscription
 */
class SubscriptionEventSubScriber implements EventSubscriberInterface {

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events[ConfigEvents::SAVE][] = array('onSavingConfig', 800);
    $events[SubscriptionEvent::SUBMIT][] = array('assignRole', 800);
    return $events;

  }

  /**
   * Subscriber Callback for the event.
   * @param SubscriptionEvent $event
   */
  public function assignRole(SubscriptionEvent $event) {
    
    $user = User::load(\Drupal::currentUser()->id());
    $user->addRole('subscription');
    $user->save();
    
    $messenger = \Drupal::messenger();
    $messenger->addMessage("User role 'Subscription' assinged");
  }

  /**
   * Subscriber Callback for the event.
   * @param ConfigCrudEvent $event
   */
  public function onSavingConfig(ConfigCrudEvent $event) {
    $messenger = \Drupal::messenger();
    $messenger->addMessage('You have saved configuration');
  }
}
