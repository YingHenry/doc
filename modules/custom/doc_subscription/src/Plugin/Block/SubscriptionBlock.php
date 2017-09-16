<?php

namespace Drupal\doc_subscription\Plugin\Block;

use \Drupal\Core\Block\BlockBase;
use \Drupal\node\Entity\Node;
use \Drupal\user\Entity\User;
use \Drupal\views\Views;

/**
 * @Block(
 *   id = "doc_subscription_block",
 *   admin_label = @Translation("Subscription block"),
 *   category = @Translation("doc_subscription"),
 * )
 */
class SubscriptionBlock extends BlockBase {

  public function build() {
    $currentUserId = \Drupal::currentUser()->id();
    $currentUser = User::load($currentUserId);
    $subcriptionValues = $currentUser->get('field_subscription')->getValue();

    // on charge
    $nids = [];

    if (!empty($subcriptionValues)) {
      $builds = [];

      foreach ($subcriptionValues as $subcriptionValue) {
        $build = $this->loadView('subscription', 'block_1', [$subcriptionValue['target_id']]);
        array_unshift($builds, $build);
      }
    }

    return $builds;
  }

  public function loadView($viewId, $displayId, $args = array())
  {
    $view = Views::getView($viewId);

    if (is_object($view)) {
      $view->setArguments($args);
      $view->setDisplay($displayId);
      $view->preExecute();
      $view->execute();
      $renderArray = $view->buildRenderable($displayId);
    }

    return $renderArray;
  }
}