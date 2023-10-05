<?php

namespace Drupal\manage_module_config;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;

/**
 * ManageModuleConfig plugin manager.
 */
class ManageModuleConfigPluginManager extends DefaultPluginManager {
  
  /**
   * Constructs ManageModuleConfigPluginManager object.
   *
   * @param \Traversable $namespaces
   *        An object that implements \Traversable which contains the root paths
   *        keyed by the corresponding namespace to look for plugin
   *        implementations.
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache_backend
   *        Cache backend instance to use.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *        The module handler to invoke the alter hook with.
   */
  public function __construct(\Traversable $namespaces, CacheBackendInterface $cache_backend, ModuleHandlerInterface $module_handler) {
    parent::__construct('Plugin/ManageModuleConfig', $namespaces, $module_handler, 'Drupal\manage_module_config\ManageModuleConfigInterface', 'Drupal\manage_module_config\Annotation\ManageModuleConfig');
    $this->alterInfo('manage_module_config_info');
    $this->setCacheBackend($cache_backend, 'manage_module_config_plugins');
  }
  
  /**
   * Permet de recuperer la liste des configurables accessible par le domaine
   * encours.
   */
  public function getActiveConfigs() {
    $configs = [
      '#type' => 'html_tag',
      '#tag' => 'div',
      '#attributes' => [
        'class' => [
          'row',
          'row-marg-2px'
        ]
      ]
    ];
    $plugins = $this->getDefinitions();
    foreach ($plugins as $plugin) {
      /**
       *
       * @var \Drupal\manage_module_config\ManageModuleConfigPluginBase $instance
       */
      $instance = $this->createInstance($plugin['id'], []);
      $configuration = $instance->defaultConfiguration();
      $instance->setConfiguration($configuration);
      $url = $instance->getRoute();
      if ($url) {
        $url = $url->toString();
      }
      else
        $url = NULL;
      if ($instance->getConfiguration()['enable']) {
        $configs[] = [
          '#theme' => 'manage_module_config_card_info',
          '#name' => $instance->GetName(),
          '#description' => $instance->getDescription(),
          '#icon_svg' => $instance->getIconSvg(),
          '#icon_svg_class' => 'btn-circle ' . $instance->getIconSvgClass(),
          '#route' => $url
        ];
      }
    }
    return $configs;
  }
  
}
