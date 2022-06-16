<?php

/**
 * Display content in block.
 *
 * @Block(
 *   id = "ablock",
 *   admin_label = @Translation("Display content in block"),
 *   category = @Translation("Block")
 * )
 */

namespace Drupal\blockauto\Plugin\Block;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\node\NodeInterface;
use Drupal\node\Entity\Node;	
use Drupal\Core\Cache\Cache;


class ablock extends BlockBase {
  /**
   * {@inheritdoc}
   */
  public function build() {
  
    // Define entity type  
    $type = '';
    $build = [
      '#markup' => $this->t(''),
    ];
    $entity_type = \Drupal::routeMatch()->getParameters()->keys()[0];
    $cur_entity = \Drupal::routeMatch()->getParameter($entity_type);

    
    if($entity_type == 'node'){  
      $type = $cur_entity->getType();
      $entity = \Drupal::entityTypeManager()->getStorage($entity_type);
      $query = $entity->getQuery();
    
      $ids = $query->condition('status', 1)
        ->condition('type', $type)
        ->sort('created', 'DESC') 
        ->execute();

      // Load all items of current type node
      $nodes = $entity->loadMultiple($ids);
      $last_created = array_key_first($nodes);
      
      $view_mode = 'teaser';  
      $view_builder = \Drupal::entityTypeManager()->getViewBuilder($entity_type);
      $storage = \Drupal::EntityTypeManager()->getStorage($entity_type);
      $node = $storage->load($last_created);
      $build = $view_builder->view($node, $view_mode);
    
    }
    
    return $build;
      
  }

  /**
   * {@inheritdoc}
   */
  protected function blockAccess(AccountInterface $account) {
    return AccessResult::allowedIfHasPermission($account, 'access content');
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    
    $config = $this->getConfiguration();

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['ablock_settings'] = $form_state->getValue('ablock_settings');
  }
   
 /**
   * @return int
   */
  public function getCacheMaxAge() {
    return 0;
  }
}
