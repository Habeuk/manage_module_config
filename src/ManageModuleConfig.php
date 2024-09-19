<?php

namespace Drupal\manage_module_config;

/**
 *
 * @author stephane
 *        
 */
class ManageModuleConfig {
  
  /**
   * Options list.
   *
   * @return array
   */
  static public function getPlugins() {
    /**
     *
     * @var \Drupal\manage_module_config\ManageEntitties\ManageEntittiesPluginManager $manage_module_entities
     */
    $manage_module_entities = \Drupal::service('plugin.manager.manage_module_config');
    return $manage_module_entities->getOptionsPlugins();
  }
  
}