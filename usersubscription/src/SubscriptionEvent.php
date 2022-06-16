<?php

namespace Drupal\usersubscription;

use Symfony\Component\EventDispatcher\Event;

class SubscriptionEvent extends Event {

  const SUBMIT = 'event.submit';
  protected $referenceID;

  public function __construct($referenceID)
  {
    $this->referenceID = $referenceID;
  }

  public function getReferenceID()
  {
    return $this->referenceID;
  }

  public function subsEventDescription() {
    return "This is a user subscription event";
  }
}
