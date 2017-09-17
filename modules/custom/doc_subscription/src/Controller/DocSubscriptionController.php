<?php

namespace Drupal\doc_subscription\Controller;

use \Drupal\Core\Controller\ControllerBase;
use \Drupal\user\Entity\User;
use \Symfony\Component\HttpFoundation\JsonResponse;

class DocSubscriptionController extends ControllerBase {
  public function toggleSubscription($js, $uid)
  {
    $currentUserId = \Drupal::currentUser()->id();
    $currentUser = User::load($currentUserId);

    // si l'utilisateur existe
    $user = User::load($uid);

    if ($user != null) {
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
    		//$html = '<span id="subscription">' . t('Unsubscribe') . '</span>';
        $text = t('Unsubscribe');
        $user->field_follower_count->value++;
    	} else {
    		unset($currentUser->field_subscription[$key]);
    		//$html = '<span id="subscription">' . t('Subscribe') . '</span>';
        $text = t('Subscribe');
        $user->field_follower_count->value--;
    	}

    	$currentUser->save();
      $user->save();
    }

    $data = [
      'uid' => $uid,
      'text' => $text,
    ];

    return new JsonResponse($data);
  }
}