<?php

namespace Drupal\usersubscription\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\user\Entity\User;

class subscrForm extends FormBase {

  /**
   * @return string
   *   The unique string identifying the form.
   */
  public function getFormId() {
    return 'subscrform_form';
  }



/**
   * Form constructor.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   *
   * @return array
   *   The form structure.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['description'] = [
      '#type' => 'item',
      '#markup' => $this->t('Please check if you want subscribe.'),
    ];
    

    $form['accept'] = array(
      '#type' => 'checkbox',
      '#title' => $this
        ->t('add “subscriber” role to a user'),
      '#description' => $this->t('check to accept'),
    );


    // Group submit handlers in an actions element with a key of "actions" so
    // that it gets styled correctly, and so that other modules may add actions
    // to the form.  
    $form['actions'] = [
      '#type' => 'actions',
    ];

    // Add a submit button that handles the submission of the form.
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Subscribe'),
    ];

    return $form;

  }
  
   /**
   * Validate the checkbox of the form
   * 
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   * 
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);

    $accept = $form_state->getValue('accept');

    if (empty($accept)){
      // Set an error for the form element with a key of "accept".
      $form_state->setErrorByName('accept', $this->t('You must accept the terms of use to continue'));
    }

  }
  
  
   /**
   * Form submission handler.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    // Display the results.    
    
    $user = User::load(\Drupal::currentUser()->id());
    $user->addRole('subscription');
    $user->save();

    
    // Call the Static Service Container wrapper
    // Inject the messenger service
    $messenger = \Drupal::messenger();
    $messenger->addMessage('User role assinged');
    $messenger->addMessage('Accept: '.$form_state->getValue('accept'));     
    
    // Redirect to home
    $form_state->setRedirect('<front>');

  } 
  
  
}


