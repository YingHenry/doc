<?php

namespace Drupal\doc_home\Controller;

use \Drupal\Core\Controller\ControllerBase;

class DocHomeController extends ControllerBase {
  public function home()
  {
    return array(
      '#markup' => '',
    );
  }
}