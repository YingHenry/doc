<?php

namespace Drupal\doc_home\Controller;

use \Drupal\Core\Controller\ControllerBase;

class DocHistoryController extends ControllerBase {
  public function home()
  {
  	
  	
    return array(
      '#theme' => 'global_search_pager_tool',
      '#data' => array(
        'paginationTool' => $paginationTool,
      ),
      '#cache' => array(
        'max-age' => 0,
      )
    );
  }
}