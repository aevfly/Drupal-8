<?php

namespace Drupal\adminpath\Routing;

use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;

/**
 * Listens to the dynamic route events.
 */
class RouteSubscriber extends RouteSubscriberBase {

  /**
   * {@inheritdoc}
   */
  protected function alterRoutes(RouteCollection $collection) {
    //	Change path from /user/login to /global/login
    if ($route = $collection->get('user.login')) {
      $route->setPath('/global/login');
    }
  }

}
