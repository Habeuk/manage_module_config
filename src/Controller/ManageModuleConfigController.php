<?php

namespace Drupal\manage_module_config\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for manage module config routes.
 */
class ManageModuleConfigController extends ControllerBase {
  
  /**
   * Builds the response.
   */
  public function build() {
    $build['content'] = [
      '#type' => 'item',
      '#markup' => $this->t('It works!')
    ];
    
    return $build;
  }
  
  /**
   *
   * @param integer $uid
   * @param integer $plugin_id
   */
  public function ManageEntities($uid, $plugin_id) {
    return [];
  }
  
}
