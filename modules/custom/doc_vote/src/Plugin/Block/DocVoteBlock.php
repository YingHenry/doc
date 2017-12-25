<?php

namespace Drupal\doc_vote\Plugin\Block;

use \Drupal\Core\Block\BlockBase;
use \Drupal\node\Entity\Node;
use \Drupal\user\Entity\User;
use \Drupal\views\Views;

/**
 * @Block(
 *   id = "doc_vote_block",
 *   admin_label = @Translation("Vote block"),
 *   category = @Translation("doc_vote"),
 * )
 */
class DocVoteBlock extends BlockBase {
  public function build() {
    $currentUserId = \Drupal::currentUser()->id();
    $currentUser = User::load($currentUserId);
    $subcriptionValues = $currentUser->get('field_subscription')->getValue();


    $build = [
      '#theme' => 'vote',
      '#data' => 'prout',
    ];

    return $build;
  }
}