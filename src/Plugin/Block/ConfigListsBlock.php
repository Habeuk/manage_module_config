<?php

namespace Drupal\manage_module_config\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Liste les configuration accessible.
 *
 * @Block(
 *   id = "manage_module_config_lists",
 *   admin_label = @Translation("Liste de configuration"),
 *   category = @Translation("manage module config")
 * )
 */
class ConfigListsBlock extends BlockBase {
  
  /**
   *
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $build['content']['container'] = [
      '#theme' => 'manage_module_config_card',
      '#header' => "Configurations",
      '#content' => $this->loadAllActiveConfigs()
    ];
    return $build;
  }
  
  /**
   * Charge toue le configurationa active
   */
  protected function loadAllActiveConfigs() {
    /**
     *
     * @var \Drupal\manage_module_config\ManageModuleConfigPluginManager $manage_module_config
     */
    $manage_module_config = \Drupal::service('plugin.manager.manage_module_config');
    return $manage_module_config->getActiveConfigs();
  }
  
}
