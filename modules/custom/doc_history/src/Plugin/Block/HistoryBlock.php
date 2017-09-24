<?php

namespace Drupal\doc_history\Plugin\Block;

use \Drupal\Core\Block\BlockBase;
use \Drupal\node\Entity\Node;
use \Drupal\user\Entity\User;

/**
 * @Block(
 *   id = "doc_history_block",
 *   admin_label = @Translation("History block"),
 *   category = @Translation("doc_history"),
 * )
 */
class HistoryBlock extends BlockBase {

  public function build() {
    // on récupère les 5 derniers docs consultés par l'utilisateur
    $params = [
      ':uid' => \Drupal::currentUser()->id(),
    ];

    $results = db_query('SELECT nid, timestamp FROM {history} WHERE uid = :uid ORDER BY timestamp DESC LIMIT 5', $params)->fetchAll();

    foreach ($results as $result) {
      $nids[] = $result->nid;
    }

    $nodes = Node::loadMultiple($nids);

    foreach ($nodes as $key => $node) {
      if ($node->getType() != 'doc') {
        unset($nodes[$key]);
      }
    }

    $build = \Drupal::entityTypeManager()->getViewBuilder('node')->viewMultiple($nodes, 'list');

    //return $build;

    return array(
      '#theme' => 'history',
      '#data' => array(
        'build' => $build,
      ),
      '#cache' => array(
        'max-age' => 0,
      )
    );
  }
}