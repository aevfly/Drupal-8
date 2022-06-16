<?php

/**
  * A clone of $this with all identifiers unset, so saving it inserts a new entity into the storage system.
  *
 */ 
namespace Drupal\clonenode\Controller;

use Drupal\node\Entity\Node;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\RedirectResponse;


class CloneController extends ControllerBase
{

    /**
     * {@inheritdoc}
     */
    public function clone($id)
    {
        $node = Node::load($id);
        if ($node === null) {
            drupal_set_message(t('Node with id @id does not exist.', ['@id' => $id]), 'error');
        } else {

            $nodeDuplicate = $node->createDuplicate();
            
            foreach ($nodeDuplicate->field_paragraphs as $field) {
                $field->entity = $field->entity->createDuplicate();
            }
            $nodeDuplicate->setTitle('Clone of '. $node->getTitle());           
            $nodeDuplicate->save();

	    $messenger = \Drupal::messenger();
    	    $messenger->addMessage(t("Node has been cloned. <a href='/node/@id/edit' target='_blank'>Edit now</a>", [
                  '@id' => $nodeDuplicate->id(),
                  '@title' => $nodeDuplicate->getTitle()]
            	 ));
        }

        return new RedirectResponse('/admin/content');
    }
}
