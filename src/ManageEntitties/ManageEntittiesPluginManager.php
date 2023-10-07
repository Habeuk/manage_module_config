<?php

namespace Drupal\manage_module_config\ManageEntitties;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;

/**
 * ManageEntitties plugin manager.
 */
class ManageEntittiesPluginManager extends DefaultPluginManager {
  
  /**
   * Constructs ManageEntittiesPluginManager object.
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
    parent::__construct('Plugin/ManageEntitties', $namespaces, $module_handler, 'Drupal\manage_module_config\ManageEntitties\ManageEntittiesInterface', 'Drupal\manage_module_config\Annotation\ManageEntitties');
    $this->alterInfo('manage_entitties_info');
    $this->setCacheBackend($cache_backend, 'manage_entitties_plugins');
  }
  
  /**
   * Permet d'afficher les resumes des differents plugins.
   */
  public function buildResumes() {
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
    dump($plugins);
    foreach ($plugins as $plugin) {
      /**
       *
       * @var \Drupal\manage_module_config\ManageEntitties\ManageEntittiesPluginBase $instance
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
        $instance->getNumbers();
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
