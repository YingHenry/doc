<?php

namespace Drupal\doc_vote\Controller;

use \Drupal\Core\Controller\ControllerBase;
use \Drupal\user\Entity\User;
use \Symfony\Component\HttpFoundation\JsonResponse;

class DocVoteController extends ControllerBase {
  public function vote($js, $action, $nid)
  {
    $nid = (int)$nid;

    if ($nid > 0) {
      $ipAddress = \Drupal::request()->getClientIp();

      // s'il a déjà voté on met à jour sinon on crée une nouvelle entrée
      $arguments = [
        'ipAddress' => $ipAddress,
        'nid' => $nid,
      ];

      $result = db_select('doc_vote')
        ->fields('doc_vote')
        ->where('ip_address = :ipAddress AND node_id = :nid', $arguments)
        ->execute()
        ->fetch();

      $fields = [
        'ip_address' => $ipAddress,
        'node_id' => $nid,
      ];

      if ($result) {
        // si la décision est différente on met à jour
        if ($result->upvote != $action) {
          $fields['id'] = $result->id;
          $fields['upvote'] = $result->upvote == '0' ? 1 : 0;
          $this->update($fields);
        } else { // si la décision est là même on annule le vote
          $fields['id'] = $result->id;
          $this->delete($fields);
        }
      } else { // s'il n'a pas voté on crée une entrée
        $fields['upvote'] = $action;
        $this->insert($fields);
      }
    }

    $data = [
      'message' => t('Your vote has been saved.'),
      'ip_address' => $ipAddress,
      'node_id' => $nid,
      'upvote' => 1,
    ];

    return new JsonResponse($data);
  }

  // retourne "1" en cas de réussite
  public function insert(array $entry) {
    $return_value = NULL;
    try {
      $return_value = db_insert('doc_vote')
        ->fields($entry)
        ->execute();
    }
    catch (\Exception $e) {
      drupal_set_message(t('db_insert failed. Message = %message, query= %query', [
        '%message' => $e->getMessage(),
        '%query' => $e->query_string,
      ]
      ), 'error');
    }
    return $return_value;
  }

  // retourne un objet en cas de réussite
  public function update(array $entry) {
    try {
      // db_update()...->execute() returns the number of rows updated.
      $count = db_update('doc_vote')
        ->fields($entry)
        ->condition('id', $entry['id'])
        ->execute();
    }
    catch (\Exception $e) {
      drupal_set_message(t('db_update failed. Message = %message, query= %query', [
        '%message' => $e->getMessage(),
        '%query' => $e->query_string,
      ]
      ), 'error');
    }
    return $count;
  }

  public function delete(array $entry) {
    db_delete('doc_vote')
      ->condition('id', $entry['id'])
      ->execute();
  }
}