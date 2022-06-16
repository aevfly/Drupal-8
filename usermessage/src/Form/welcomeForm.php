<?php

namespace Drupal\usermessage\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\user\Entity\User;
use Drupal\Core\Database\Database;

class welcomeForm extends FormBase {

  /**
   * @return string
   *   The unique string identifying the form.
   */
  public function getFormId() {
    return 'welcomeform_form';
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
  
    $user = \Drupal::currentUser();
    
    $usertext = '';
    $conn = Database::getConnection();
    $data = array();
    $query = $conn->select('usermessage', 'm')
        ->condition('id', $user->id())
        ->fields('m');
    $data = $query->execute()->fetchAssoc();

    $usertext = $data['text']; 

    $form['description'] = [
      '#type' => 'item',
      '#markup' => $this->t('Enter your welocme text.'),
    ];
    

 /*   $form['ovverride'] = array(
      '#type' => 'checkbox',
      '#title' => $this
        ->t('Override your text'),
      '#description' => $this->t('check to override'),
    );
*/
    
    $form['text'] = array(
        '#type' => 'textarea',
        '#title' => $this
           ->t('Text'),
        '#default_value' => $usertext,
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
      '#value' => $this->t('Submit'),
    ];

    return $form;

  }
  
   /**
   * Validate the text of the form
   * 
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   * 
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);

    $text = $form_state->getValue('text');

    if (empty($text)){
      // Set an error for the form element with a key of "accept".
      $form_state->setErrorByName('text', $this->t('You must enter Welcome text'));
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
	$user = \Drupal::currentUser();
	
	$conn = Database::getConnection();
    	$data = array();
    	$query = $conn->select('usermessage', 'm')
        	->condition('id', $user->id())
        	->fields('m');
    	$data = $query->execute()->fetchAssoc();
	
	if(isset($data['uid'])){
	    $fields = array(
      		'text' => $form_state->getValue('text'),
    	    );
	    //Update through the services connection
	    \Drupal::database()->update('usermessage')->fields($fields)->condition('uid', $user->id())->execute();
	}else{
		//Save to database through direct connection
		try{
			$conn = Database::getConnection();		
			$field = $form_state->getValues();	   
			$fields["text"] = $field['text'];
			$fields["uid"] = $user->id();
					
			  $conn->insert('usermessage')
				   ->fields($fields)->execute();
			  
			// Display the results.    
	    		// Messenger service
	    		$messenger = \Drupal::messenger();
	    		$messenger->addMessage('Welcome text saved');
	    	  
		} catch(Exception $ex){
			\Drupal::logger('usermessage')->error($ex->getMessage());
		}
	}
    	
    	// Redirect to home
    	//$form_state->setRedirect('<front>');

  } 
  
  
}


