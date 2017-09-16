<?php

namespace Drupal\doc_subscription\Controller;

use \Drupal\Core\Controller\ControllerBase;
use \Drupal\user\Entity\User;
use \Drupal\Core\Ajax\AjaxResponse;
use \Drupal\Core\Ajax\ReplaceCommand;

class DocSubscriptionController extends ControllerBase {
  public function toggleSubscription($js, $uid)
  {
    $currentUserId = \Drupal::currentUser()->id();
    $currentUser = User::load($currentUserId);

    // si l'utilisateur existe
    if (User::load($uid) != null) {
    	// si on ne s'est pas encore abonné
    	$add = true;
    	$subcriptionValues = $currentUser->get('field_subscription')->getValue();

    	// on regarde si l'uid existe parmi les références
    	if (!empty($subcriptionValues)) {
    		foreach ($subcriptionValues as $key => $subcriptionValue) {
    			if ($uid == $subcriptionValue['target_id']) {
    				$add = false;
    				break;
    			}
    		}
    	}

    	if ($add) {
    		$currentUser->field_subscription[] = ['target_id' => $uid];
    		$html = '<span id="subscription">' . t('Unsubscribe') . '</span>';
    	} else {
    		unset($currentUser->field_subscription[$key]);
    		$html = '<span id="subscription">' . t('Subscribe') . '</span>';
    	}

    	$currentUser->save();
    }

  	$response = new AjaxResponse();
  	$response->addCommand(new ReplaceCommand('#subscription', $html));

  	return $response;
  }
}